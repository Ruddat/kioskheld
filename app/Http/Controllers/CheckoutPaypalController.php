<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutPaypalController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('kioskheld.cart');
        $customer = $request->session()->get('kioskheld.checkout.customer');

        if (! is_array($cart)) {
            return redirect()
                ->route('shops.selection')
                ->with('status', 'Bitte prüfe zuerst deinen Warenkorb.');
        }

        if (! is_array($customer) || empty($customer)) {
            return redirect()
                ->route('checkout.show')
                ->with('status', 'Bitte gib zuerst deine Lieferdaten ein.');
        }

        if (($customer['payment_method'] ?? null) !== 'paypal') {
            return redirect()
                ->route('checkout.show')
                ->with('status', 'Bitte wähle PayPal als Zahlungsart aus.');
        }

        $validated = $cart['validated'] ?? [];

        if (($validated['valid'] ?? false) !== true) {
            return redirect()
                ->route('shops.selection')
                ->with('status', 'Bitte prüfe deinen Warenkorb erneut.');
        }

        $reservation = $cart['reservation'] ?? data_get($validated, 'reservation');

        if (data_get($reservation, 'requested') === true && data_get($reservation, 'reserved') !== true) {
            $request->session()->forget('kioskheld.cart');
            $request->session()->forget('kioskheld.checkout.customer');

            return redirect()
                ->route('shops.selection')
                ->with('status', 'Ein oder mehrere Artikel konnten nicht reserviert werden. Bitte prüfe deinen Warenkorb erneut.');
        }

        $validatedAt = $cart['validated_at'] ?? null;
        $ttlMinutes = max(1, (int) data_get($reservation, 'ttl_minutes', 10));
        $expiresAfterMinutes = max(1, $ttlMinutes - 1);

        if (! $validatedAt || Carbon::parse($validatedAt)->lt(now()->subMinutes($expiresAfterMinutes))) {
            $request->session()->forget('kioskheld.cart');
            $request->session()->forget('kioskheld.checkout.customer');

            return redirect()
                ->route('shops.selection')
                ->with('status', 'Deine Warenkorb-Prüfung ist abgelaufen. Bitte prüfe erneut.');
        }

        $apiUrl = rtrim((string) config('services.justdeliver.kioskheld_api_url'), '/');
        $apiKey = (string) config('services.justdeliver.kioskheld_api_key');

        if ($apiUrl === '' || $apiKey === '') {
            Log::error('Kioskheld PayPal API config missing', [
                'api_url_set' => $apiUrl !== '',
                'api_key_set' => $apiKey !== '',
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'Die PayPal-Schnittstelle ist nicht korrekt konfiguriert.');
        }

        $payload = $this->buildPayload($cart, $validated, $customer);

        try {
            Log::info('Kioskheld PayPal create outgoing payload', [
                'url' => $apiUrl . '/paypal/create',
                'payload' => $payload,
            ]);

            $response = Http::timeout(15)
                ->when(app()->environment('local'), fn ($http) => $http->withoutVerifying())
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl . '/paypal/create', $payload);
        } catch (ConnectionException $exception) {
            Log::error('Kioskheld PayPal create connection failed', [
                'url' => $apiUrl . '/paypal/create',
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'PayPal ist gerade nicht erreichbar. Bitte versuche es gleich erneut.');
        } catch (\Throwable $exception) {
            Log::error('Kioskheld PayPal create unexpected exception', [
                'url' => $apiUrl . '/paypal/create',
                'message' => $exception->getMessage(),
                'payload' => $payload,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'Die PayPal-Zahlung konnte gerade nicht vorbereitet werden.');
        }

        $responseJson = $response->json();

        $data = is_array($responseJson)
            ? ($responseJson['data'] ?? $responseJson)
            : [];

        if (! $response->successful()) {
            Log::warning('Kioskheld PayPal create rejected by JustDeliver', [
                'url' => $apiUrl . '/paypal/create',
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $responseJson,
                'payload' => $payload,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with(
                    'status',
                    $responseJson['message']
                        ?? data_get($responseJson, 'error.message')
                        ?? 'Die PayPal-Zahlung konnte nicht gestartet werden.'
                );
        }

        $approvalUrl = data_get($data, 'paypal.approval_url');
        $paypalOrderId = data_get($data, 'paypal.paypal_order_id');
        $orderId = data_get($data, 'order.id');

        if (blank($approvalUrl) || blank($paypalOrderId) || blank($orderId)) {
            Log::error('Kioskheld PayPal create response incomplete', [
                'json' => $responseJson,
                'data' => $data,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'PayPal konnte nicht gestartet werden. Bitte versuche es erneut.');
        }

        $request->session()->put('kioskheld.paypal', [
            'order_id' => $orderId,
            'paypal_order_id' => $paypalOrderId,
        ]);

        return redirect()->away($approvalUrl);
    }

    public function success(Request $request): RedirectResponse
    {
        $paypal = $request->session()->get('kioskheld.paypal');

        if (! is_array($paypal)) {
            return redirect()
                ->route('checkout.show')
                ->with('status', 'Die PayPal-Zahlung konnte nicht zugeordnet werden.');
        }

        $apiUrl = rtrim((string) config('services.justdeliver.kioskheld_api_url'), '/');
        $apiKey = (string) config('services.justdeliver.kioskheld_api_key');

        if ($apiUrl === '' || $apiKey === '') {
            return redirect()
                ->route('checkout.show')
                ->with('status', 'Die PayPal-Schnittstelle ist nicht korrekt konfiguriert.');
        }

        $payload = [
            'order_id' => $paypal['order_id'] ?? null,
            'paypal_order_id' => $paypal['paypal_order_id'] ?? null,
            'external_session_id' => $request->session()->getId(),
        ];

        try {
            $response = Http::timeout(15)
                ->when(app()->environment('local'), fn ($http) => $http->withoutVerifying())
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl . '/paypal/capture', $payload);
        } catch (\Throwable $exception) {
            Log::error('Kioskheld PayPal capture failed', [
                'message' => $exception->getMessage(),
                'payload' => $payload,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'Die PayPal-Zahlung konnte gerade nicht bestätigt werden.');
        }

        $responseJson = $response->json();

        $data = is_array($responseJson)
            ? ($responseJson['data'] ?? $responseJson)
            : [];

        if (! $response->successful() || ($data['captured'] ?? false) !== true) {
            Log::warning('Kioskheld PayPal capture not completed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $responseJson,
                'payload' => $payload,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with(
                    'status',
                    $responseJson['message']
                        ?? data_get($data, 'message')
                        ?? 'Die PayPal-Zahlung wurde nicht abgeschlossen.'
                );
        }

        $request->session()->put('kioskheld.last_order', [
            'response' => $responseJson,
            'data' => $data,
            'payment_method' => 'paypal',
        ]);

        $request->session()->forget('kioskheld.cart');
        $request->session()->forget('kioskheld.checkout.customer');
        $request->session()->forget('kioskheld.paypal');

        return redirect()->route('checkout.thank-you');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $paypal = $request->session()->get('kioskheld.paypal');

        if (is_array($paypal)) {
            $apiUrl = rtrim((string) config('services.justdeliver.kioskheld_api_url'), '/');
            $apiKey = (string) config('services.justdeliver.kioskheld_api_key');

            if ($apiUrl !== '' && $apiKey !== '') {
                $payload = [
                    'order_id' => $paypal['order_id'] ?? null,
                    'external_session_id' => $request->session()->getId(),
                ];

                try {
                    Http::timeout(10)
                        ->when(app()->environment('local'), fn ($http) => $http->withoutVerifying())
                        ->withHeaders([
                            'X-Kioskheld-Api-Key' => $apiKey,
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ])
                        ->post($apiUrl . '/paypal/cancel', $payload);
                } catch (\Throwable $exception) {
                    Log::warning('Kioskheld PayPal cancel failed', [
                        'message' => $exception->getMessage(),
                        'payload' => $payload,
                    ]);
                }
            }
        }

        $request->session()->forget('kioskheld.paypal');

        return redirect()
            ->route('checkout.show')
            ->with('status', 'Die PayPal-Zahlung wurde abgebrochen.');
    }

    private function buildPayload(array $cart, array $validated, array $customer): array
    {
        $location = $validated['delivery']['location'] ?? [];
        $shop = $validated['shop'] ?? [];
        $reservation = $cart['reservation'] ?? data_get($validated, 'reservation');

        return [
            'source' => 'kioskheld',
            'external_session_id' => $cart['external_session_id'] ?? session()->getId(),
            'reservation' => $reservation,
            'reservation_cart_token' => data_get($reservation, 'cart_token'),

            'shop' => [
                'id' => $shop['id'] ?? ($cart['shop_id'] ?? null),
                'slug' => $shop['slug'] ?? null,
                'name' => $shop['name'] ?? null,
            ],

            'customer' => [
                'name' => $customer['customer_name'] ?? null,
                'phone' => $customer['customer_phone'] ?? null,
                'email' => $customer['customer_email'] ?? null,
            ],

            'delivery_address' => [
                'street' => $customer['street'] ?? null,
                'house_number' => $customer['house_number'] ?? null,
                'postal_code' => $location['postal_code'] ?? $cart['postcode'] ?? null,
                'city' => $location['city'] ?? $cart['city'] ?? null,
                'district' => $location['district'] ?? $cart['district'] ?? null,
                'lat' => $location['lat'] ?? null,
                'lng' => $location['lng'] ?? null,
                'note' => $customer['delivery_note'] ?? null,
            ],

            'payment_method' => 'paypal',

            'return_url' => route('checkout.paypal.success'),
            'cancel_url' => route('checkout.paypal.cancel'),

            'items' => $this->buildOrderItems($cart['items'] ?? []),

            'client_validation' => [
                'validated_at' => $cart['validated_at'] ?? null,
                'items_total' => $validated['totals']['items_total'] ?? null,
                'delivery_fee' => $validated['totals']['delivery_fee'] ?? null,
                'grand_total' => $validated['totals']['grand_total'] ?? null,
                'minimum_order_value' => $validated['totals']['minimum_order_value'] ?? null,
                'currency' => $validated['totals']['currency'] ?? 'EUR',
                'reservation_cart_token' => data_get($reservation, 'cart_token'),
                'reservation_ttl_minutes' => data_get($reservation, 'ttl_minutes'),
            ],
        ];
    }

    private function buildOrderItems(array $items): array
    {
        return collect($items)
            ->map(function (array $item) {
                $type = $item['type'] ?? null;

                if ($type === 'menu') {
                    return [
                        'type' => 'menu',
                        'menu_id' => (int) ($item['menu_id'] ?? 0),
                        'quantity' => (int) ($item['quantity'] ?? 1),
                        'choices' => collect($item['choices'] ?? [])
                            ->map(fn (array $choice) => [
                                'choice_group_id' => (int) ($choice['choice_group_id'] ?? 0),
                                'variant_id' => (int) ($choice['variant_id'] ?? 0),
                                'quantity' => (int) ($choice['quantity'] ?? 1),
                            ])
                            ->filter(fn (array $choice) => $choice['choice_group_id'] > 0 && $choice['variant_id'] > 0)
                            ->values()
                            ->all(),
                    ];
                }

                if (
                    $type === 'product'
                    || $type === 'variant'
                    || $type === 'product_variant'
                    || $type === 'single'
                    || ! empty($item['variant_id'])
                ) {
                    return [
                        'type' => 'product',
                        'variant_id' => (int) ($item['variant_id'] ?? 0),
                        'quantity' => (int) ($item['quantity'] ?? 1),
                    ];
                }

                return null;
            })
            ->filter(fn ($item) => is_array($item))
            ->filter(function (array $item) {
                if (($item['type'] ?? null) === 'menu') {
                    return ! empty($item['menu_id']);
                }

                if (($item['type'] ?? null) === 'product') {
                    return ! empty($item['variant_id']);
                }

                return false;
            })
            ->values()
            ->all();
    }
}
