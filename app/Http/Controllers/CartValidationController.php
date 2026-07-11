<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CartValidationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shop_id' => ['nullable', 'integer', 'min:1'],
            'payment_method' => ['nullable', 'string', 'max:40'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.type' => ['required', 'string', Rule::in(['product', 'menu'])],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],

            'items.*.variant_id' => ['nullable', 'integer', 'min:1'],
            'items.*.menu_id' => ['nullable', 'integer', 'min:1'],

            'items.*.choices' => ['nullable', 'array'],
            'items.*.choices.*.choice_group_id' => ['required_with:items.*.choices', 'integer', 'min:1'],
            'items.*.choices.*.variant_id' => ['required_with:items.*.choices', 'integer', 'min:1'],
            'items.*.choices.*.quantity' => ['required_with:items.*.choices', 'integer', 'min:1', 'max:99'],
        ], [
            'items.required' => 'Dein Warenkorb ist leer.',
            'items.array' => 'Der Warenkorb konnte nicht korrekt gelesen werden.',
            'items.min' => 'Dein Warenkorb ist leer.',
            'items.*.type.required' => 'Ein Artikel im Warenkorb ist ungültig.',
            'items.*.type.in' => 'Ein Artikel im Warenkorb hat einen ungültigen Typ.',
            'items.*.quantity.required' => 'Eine Artikelmenge im Warenkorb ist ungültig.',
            'items.*.choices.*.choice_group_id.required_with' => 'Eine Menü-Auswahl ist ungültig.',
            'items.*.choices.*.variant_id.required_with' => 'Eine Menü-Auswahl ist ungültig.',
            'items.*.choices.*.quantity.required_with' => 'Eine Menü-Auswahlmenge ist ungültig.',
        ]);

        $validator->after(function ($validator) use ($request) {
            foreach ($request->input('items', []) as $index => $item) {
                $type = $item['type'] ?? null;

                if ($type === 'product' && empty($item['variant_id'])) {
                    $validator->errors()->add(
                        "items.$index.variant_id",
                        'Ein Produkt im Warenkorb ist ungültig.'
                    );
                }

                if ($type === 'menu' && empty($item['menu_id'])) {
                    $validator->errors()->add(
                        "items.$index.menu_id",
                        'Ein Sparpaket im Warenkorb ist ungültig.'
                    );
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        $apiUrl = config('services.justdeliver.kioskheld_api_url');
        $apiKey = config('services.justdeliver.kioskheld_api_key');

        if (blank($apiUrl) || blank($apiKey)) {
            Log::error('Kioskheld cart validation API config missing', [
                'api_url_present' => filled($apiUrl),
                'api_key_present' => filled($apiKey),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Die Warenkorb-Prüfung ist noch nicht korrekt konfiguriert.',
                'data' => null,
            ], 500);
        }

        $shopId = $request->integer('shop_id') ?: session('kioskheld.selected_shop_id');
        $postcode = session('kioskheld.postcode');
        $city = session('kioskheld.city');
        $district = session('kioskheld.district');

        if (blank($shopId) || blank($postcode)) {
            return response()->json([
                'ok' => false,
                'message' => 'Bitte prüfe zuerst deine Postleitzahl und wähle einen Kiosk aus.',
                'data' => null,
            ], 409);
        }

        $items = collect($request->input('items', []))
            ->map(function (array $item) {
                if (($item['type'] ?? null) === 'menu') {
                    return [
                        'type' => 'menu',
                        'menu_id' => (int) $item['menu_id'],
                        'quantity' => (int) $item['quantity'],
                        'choices' => collect($item['choices'] ?? [])
                            ->map(fn(array $choice) => [
                                'choice_group_id' => (int) $choice['choice_group_id'],
                                'variant_id' => (int) $choice['variant_id'],
                                'quantity' => (int) $choice['quantity'],
                            ])
                            ->values()
                            ->all(),
                    ];
                }

                return [
                    'type' => 'product',
                    'variant_id' => (int) $item['variant_id'],
                    'quantity' => (int) $item['quantity'],
                ];
            })
            ->values()
            ->all();

        $payload = [
            'shop_id' => (int) $shopId,
            'postcode' => $postcode,
            'city' => $city,
            'district' => $district,
            'payment_method' => $request->string('payment_method')->toString() ?: 'cash',
            'source' => 'kioskheld',
            'external_session_id' => $request->session()->getId(),
            'reserve' => true,
            'items' => $items,
        ];

        try {
            Log::info('Kioskheld cart validate outgoing payload', [
                'payload' => $payload,
            ]);

            $response = Http::timeout(8)
                ->when(app()->environment('local'), fn($http) => $http->withoutVerifying())
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post(rtrim($apiUrl, '/') . '/cart/validate', $payload);

            $responseJson = $response->json();
            $data = is_array($responseJson)
                ? ($responseJson['data'] ?? $responseJson)
                : null;

            Log::debug('Kioskheld cart validation response shape', [
                'response_json_keys' => is_array($responseJson) ? array_keys($responseJson) : null,
                'data_keys' => is_array($data) ? array_keys($data) : null,
                'response_payment_capabilities' => is_array($responseJson)
                    ? ($responseJson['payment_capabilities'] ?? null)
                    : null,
                'data_payment_capabilities' => is_array($data)
                    ? ($data['payment_capabilities'] ?? null)
                    : null,
                'data_cart_payment_capabilities' => is_array($data)
                    ? data_get($data, 'cart.payment_capabilities')
                    : null,

                'reservation' => is_array($data)
                    ? ($data['reservation'] ?? null)
                    : null,
            ]);


            if (! $response->successful()) {
                Log::warning('Kioskheld cart validation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'shop_id' => $shopId,
                    'postcode' => $postcode,
                    'city' => $city,
                    'district' => $district,
                    'payload' => $payload,
                ]);

                $request->session()->forget('kioskheld.cart');

                return response()->json([
                    'ok' => false,
                    'message' => $responseJson['message']
                        ?? 'Der Warenkorb konnte nicht validiert werden.',
                    'data' => $data,
                ], $response->status());
            }

            $isValid = ($data['valid'] ?? false) === true;

            $reservation = data_get($data, 'reservation');

            $reservationRequested = data_get($reservation, 'requested') === true;
            $reservationReserved = data_get($reservation, 'reserved') === true;

            if ($isValid && $reservationRequested && ! $reservationReserved) {
                $request->session()->forget('kioskheld.cart');

                Log::warning('Kioskheld cart reservation failed', [
                    'shop_id' => $shopId,
                    'postcode' => $postcode,
                    'reservation' => $reservation,
                    'items' => $items,
                ]);

                return response()->json([
                    'ok' => true,
                    'message' => 'Ein oder mehrere Artikel konnten nicht reserviert werden. Bitte prüfe deinen Warenkorb erneut.',
                    'data' => $data,
                    'checkout_url' => null,
                ]);
            }



            if (! $isValid) {
                $request->session()->forget('kioskheld.cart');

                $missingMinimum = (float) data_get($data, 'totals.missing_minimum_order_value', 0);
                $minimumOrderValue = (float) data_get($data, 'totals.minimum_order_value', 0);

                $firstError = collect(data_get($data, 'errors', []))
                    ->first();

                $errorCode = is_array($firstError)
                    ? ($firstError['code'] ?? null)
                    : (is_string($firstError) ? $firstError : null);

                $errorMessage = is_array($firstError)
                    ? ($firstError['message'] ?? ($firstError['detail'] ?? null))
                    : null;

                $messages = [
                    'SHOP_NOT_AVAILABLE' => 'Dieser Kiosk ist aktuell nicht verfügbar.',
                    'ADDRESS_NOT_DELIVERABLE' => 'Dieser Kiosk liefert aktuell nicht an deine Adresse.',
                    'VARIANT_NOT_AVAILABLE' => 'Ein Artikel ist aktuell nicht mehr verfügbar.',
                    'PRICE_NOT_AVAILABLE' => 'Für einen Artikel konnte kein aktueller Preis ermittelt werden.',
                    'PRODUCT_OUT_OF_STOCK' => 'Ein Artikel ist aktuell nicht ausreichend verfügbar.',
                    'EMPTY_CART' => 'Dein Warenkorb ist leer.',
                    'MINIMUM_ORDER_NOT_REACHED' => 'Der Mindestbestellwert ist noch nicht erreicht.',
                    'PAYMENT_METHOD_NOT_ALLOWED' => 'Diese Zahlungsart ist für diesen Shop aktuell nicht erlaubt.',
                    'MENU_CHOICE_INVALID' => 'Eine Auswahl im Sparpaket ist nicht mehr gültig.',
                    'MENU_CHOICE_OUT_OF_STOCK' => 'Eine Auswahl im Sparpaket ist aktuell nicht ausreichend verfügbar.',
                ];

                $message = $errorMessage
                    ?? ($errorCode ? ($messages[$errorCode] ?? null) : null)
                    ?? ($responseJson['message'] ?? null)
                    ?? data_get($data, 'message')
                    ?? 'Der Warenkorb wurde geprüft, ist aber noch nicht gültig.';

                if ($missingMinimum > 0) {
                    $message = 'Noch '
                        . number_format($missingMinimum, 2, ',', '.')
                        . ' € Warenwert bis zum Mindestbestellwert. Pfand zählt nicht zum Mindestbestellwert.';

                    if ($minimumOrderValue > 0) {
                        $message .= ' Mindestbestellwert: ' . number_format($minimumOrderValue, 2, ',', '.') . ' €.';
                    }
                }

                Log::debug('Kioskheld cart validation invalid response', [
                    'message' => $message,
                    'error_code' => $errorCode,
                    'errors' => data_get($data, 'errors'),
                    'items' => data_get($data, 'items'),
                    'totals' => data_get($data, 'totals'),
                ]);

                return response()->json([
                    'ok' => true,
                    'message' => $message,
                    'data' => $data,
                    'checkout_url' => null,
                ]);
            }

            $reservation = data_get($data, 'reservation');

            $request->session()->put('kioskheld.cart', [
                'shop_id' => (int) $shopId,
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,

                'payment_method' => $payload['payment_method'],
                'external_session_id' => $payload['external_session_id'],

                'items' => $items,

                'validated' => $data,
                'validated_at' => now()->toIso8601String(),

                'shop' => data_get($data, 'shop'),
                'delivery' => data_get($data, 'delivery'),
                'totals' => data_get($data, 'totals'),
                'payment_methods' => data_get($data, 'payment_methods', []),
                'payment_capabilities' => data_get($data, 'payment_capabilities', []),
                'reservation' => is_array($reservation) ? $reservation : null,
            ]);

            return response()->json([
                'ok' => true,
                'message' => $responseJson['message']
                    ?? 'Warenkorb wurde geprüft.',
                'data' => $data,
                'checkout_url' => route('checkout.show'),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Kioskheld cart validation exception', [
                'message' => $exception->getMessage(),
                'shop_id' => $shopId,
                'postcode' => $postcode,
                'city' => $city,
                'district' => $district,
                'payload' => $payload ?? null,
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Die Warenkorb-Prüfung ist gerade nicht erreichbar.',
                'data' => null,
            ], 500);
        }
    }
}
