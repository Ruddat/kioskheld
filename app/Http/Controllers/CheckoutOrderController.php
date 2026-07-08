<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutOrderController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('kioskheld.cart');

        if (! is_array($cart)) {
            return redirect()
                ->route('shops.selection')
                ->with('status', 'Bitte prüfe zuerst deinen Warenkorb.');
        }

        $validated = $cart['validated'] ?? [];

        if (($validated['valid'] ?? false) !== true) {
            return redirect()
                ->route('shops.selection')
                ->with('status', 'Bitte prüfe deinen Warenkorb erneut.');
        }

        $validatedAt = $cart['validated_at'] ?? null;
        $ttlMinutes = max(1, (int) data_get($validated, 'reservation.ttl_minutes', 10));
        $expiresAfterMinutes = max(1, $ttlMinutes - 1);

        if (! $validatedAt || Carbon::parse($validatedAt)->lt(now()->subMinutes($expiresAfterMinutes))) {
            $request->session()->forget('kioskheld.cart');
            $request->session()->forget('kioskheld.checkout.customer');

            return redirect()
                ->route('shops.selection')
                ->with('status', 'Deine Warenkorb-Prüfung ist abgelaufen. Bitte prüfe erneut.');
        }

        $customer = $request->session()->get('kioskheld.checkout.customer');

        if (! is_array($customer) || empty($customer)) {
            return redirect()
                ->route('checkout.show')
                ->with('status', 'Bitte gib zuerst deine Lieferdaten ein.');
        }

        $apiUrl = rtrim((string) config('services.justdeliver.kioskheld_api_url'), '/');
        $apiKey = (string) config('services.justdeliver.kioskheld_api_key');

        if ($apiUrl === '' || $apiKey === '') {
            Log::error('Kioskheld JustDeliver API config missing', [
                'api_url_set' => $apiUrl !== '',
                'api_key_set' => $apiKey !== '',
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'Die Bestellschnittstelle ist nicht korrekt konfiguriert.');
        }

        $payload = $this->buildPayload($cart, $validated, $customer);
        //dd($payload);
        try {
            Log::info('Kioskheld order outgoing payload', [
                'url' => $apiUrl . '/orders',
                'payload' => $payload,
            ]);

            $response = Http::timeout(15)
                ->when(app()->environment('local'), fn($http) => $http->withoutVerifying())
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl . '/orders', $payload);
        } catch (ConnectionException $exception) {
            Log::error('Kioskheld order connection failed', [
                'url' => $apiUrl . '/orders',
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'JustDeliver ist gerade nicht erreichbar. Bitte versuche es gleich erneut.');
        } catch (\Throwable $exception) {
            Log::error('Kioskheld order unexpected exception', [
                'url' => $apiUrl . '/orders',
                'message' => $exception->getMessage(),
                'payload' => $payload,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('status', 'Die Bestellung konnte gerade nicht verarbeitet werden.');
        }

        $responseJson = $response->json();

        $data = is_array($responseJson)
            ? ($responseJson['data'] ?? $responseJson)
            : [];

        if (! $response->successful()) {
            Log::warning('Kioskheld order rejected by JustDeliver', [
                'url' => $apiUrl . '/orders',
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
                        ?? 'Die Bestellung konnte nicht ausgelöst werden. Bitte prüfe deinen Warenkorb erneut.'
                );
        }

        $request->session()->put('kioskheld.last_order', [
            'response' => $responseJson,
            'data' => $data,
            'payload' => $payload,
            'validated' => $validated,
        ]);

        $request->session()->forget('kioskheld.cart');
        $request->session()->forget('kioskheld.checkout.customer');

        return redirect()->route('checkout.thank-you');
    }

    private function buildPayload(array $cart, array $validated, array $customer): array
    {
        $location = $validated['delivery']['location'] ?? [];
        $shop = $validated['shop'] ?? [];

        return [
            'source' => 'kioskheld',
            'external_session_id' => session()->getId(),
            'reservation' => $validated['reservation'] ?? null,
            'reservation_cart_token' => data_get($validated, 'reservation.cart_token'),

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

            'payment_method' => $customer['payment_method'] ?? ($cart['payment_method'] ?? 'cash'),

            'items' => $this->buildOrderItems($cart['items'] ?? []),

            'client_validation' => [
                'validated_at' => $cart['validated_at'] ?? null,
                'items_total' => $validated['totals']['items_total'] ?? null,
                'delivery_fee' => $validated['totals']['delivery_fee'] ?? null,
                'grand_total' => $validated['totals']['grand_total'] ?? null,
                'minimum_order_value' => $validated['totals']['minimum_order_value'] ?? null,
                'currency' => $validated['totals']['currency'] ?? 'EUR',
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
                            ->map(fn(array $choice) => [
                                'choice_group_id' => (int) ($choice['choice_group_id'] ?? 0),
                                'variant_id' => (int) ($choice['variant_id'] ?? 0),
                                'quantity' => (int) ($choice['quantity'] ?? 1),
                            ])
                            ->filter(fn(array $choice) => $choice['choice_group_id'] > 0 && $choice['variant_id'] > 0)
                            ->values()
                            ->all(),
                    ];
                }

                /*
             * Einzelprodukte robust normalisieren.
             * Egal ob aus älterem Frontend "variant", "product_variant",
             * "single" oder schon "product" kommt:
             * JustDeliver bekommt "product".
             */
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
            ->filter(fn($item) => is_array($item))
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
