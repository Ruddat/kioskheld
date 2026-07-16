<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutCustomerController extends Controller
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

        $paymentCapabilities =
            $validated['payment_capabilities']
            ?? data_get($validated, 'cart.payment_capabilities')
            ?? [];

        if (! is_array($paymentCapabilities)) {
            $paymentCapabilities = [];
        }

        $allowedPaymentMethods = collect($paymentCapabilities)
            ->filter(function ($capability, string $method) {
                if (($capability['available'] ?? false) !== true) {
                    return false;
                }

                if ($method === 'paypal') {
                    return true;
                }

                return ($capability['direct_order_supported'] ?? false) === true;
            })
            ->keys()
            ->values()
            ->all();

        if (empty($allowedPaymentMethods)) {
            $allowedPaymentMethods = ['cash'];
        }

        if (($validated['valid'] ?? false) !== true) {
            return redirect()
                ->route('shops.selection')
                ->with('status', 'Bitte prüfe deinen Warenkorb erneut.');
        }

        $validatedAt = $cart['validated_at'] ?? null;

        if ($validatedAt && Carbon::parse($validatedAt)->lt(now()->subMinutes(10))) {
            $request->session()->forget('kioskheld.cart');
            $request->session()->forget('kioskheld.checkout.customer');

            return redirect()
                ->route('shops.selection')
                ->with('status', 'Deine Warenkorb-Prüfung ist abgelaufen. Bitte prüfe erneut.');
        }



$items = $validated['items'] ?? [];

$minimumAgeInCart = collect($items)
    ->map(function (array $item): int {
        return (int) (
            $item['min_age']
            ?? $item['minimum_age']
            ?? data_get($item, 'age_restriction.min_age')
            ?? data_get($item, 'age_restriction.minimum_age')
            ?? data_get($item, 'product.min_age')
            ?? data_get($item, 'product.minimum_age')
            ?? data_get($item, 'variant.min_age')
            ?? data_get($item, 'variant.minimum_age')
            ?? 0
        );
    })
    ->max();

$requiresAgeConfirmation = ((int) $minimumAgeInCart) >= 16;

$rules = [
    'customer_name' => ['required', 'string', 'min:2', 'max:120'],
    'customer_phone' => ['required', 'string', 'min:5', 'max:40'],
    'customer_email' => ['nullable', 'email', 'max:160'],

    'street' => ['required', 'string', 'min:2', 'max:160'],
    'house_number' => ['required', 'string', 'max:30'],
    'delivery_note' => ['nullable', 'string', 'max:500'],

    'payment_method' => ['required', 'string', Rule::in($allowedPaymentMethods)],
];

if ($requiresAgeConfirmation) {
    $rules['age_confirmed'] = ['accepted'];
}

$customer = $request->validate($rules, [
    'customer_name.required' => 'Bitte gib deinen Namen ein.',
    'customer_phone.required' => 'Bitte gib deine Telefonnummer ein.',
    'customer_email.email' => 'Bitte gib eine gültige E-Mail-Adresse ein.',
    'street.required' => 'Bitte gib deine Straße ein.',
    'house_number.required' => 'Bitte gib deine Hausnummer ein.',
    'payment_method.required' => 'Bitte wähle eine Zahlungsart.',
    'payment_method.in' => 'Diese Zahlungsart ist für diese Bestellung nicht verfügbar.',
    'age_confirmed.accepted' => 'Bitte bestätige dein Alter für Artikel mit Altersbeschränkung.',
]);

$customer['minimum_age_confirmed'] = $requiresAgeConfirmation ? (int) $minimumAgeInCart : 0;

if (($customer['payment_method'] ?? null) === 'paypal') {
    $apiUrl = config('services.justdeliver.kioskheld_api_url');
    $apiKey = config('services.justdeliver.kioskheld_api_key');

    if (blank($apiUrl) || blank($apiKey)) {
        return redirect()
            ->route('checkout.show')
            ->with('status', 'Die PayPal-Schnittstelle ist nicht korrekt konfiguriert.');
    }

    $payload = [
        'shop_id' => (int) ($cart['shop_id'] ?? 0),
        'postcode' => $cart['postcode'] ?? null,
        'city' => $cart['city'] ?? null,
        'district' => $cart['district'] ?? null,
        'payment_method' => 'paypal',
        'source' => 'kioskheld',
        'external_session_id' => $request->session()->getId(),
        'reserve' => true,
        'items' => $cart['items'] ?? [],
    ];

    $response = \Illuminate\Support\Facades\Http::timeout(8)
        ->when(app()->environment('local'), fn ($http) => $http->withoutVerifying())
        ->withHeaders([
            'X-Kioskheld-Api-Key' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
        ->post(rtrim($apiUrl, '/') . '/cart/validate', $payload);

    $responseJson = $response->json();

    $data = is_array($responseJson)
        ? ($responseJson['data'] ?? $responseJson)
        : [];

    if (! $response->successful() || ($data['valid'] ?? false) !== true) {
        $request->session()->forget('kioskheld.cart');

        return redirect()
            ->route('shops.selection')
            ->with(
                'status',
                $responseJson['message']
                    ?? data_get($data, 'message')
                    ?? 'Bitte prüfe deinen Warenkorb erneut.'
            );
    }

    $reservation = data_get($data, 'reservation');

    if (data_get($reservation, 'requested') === true && data_get($reservation, 'reserved') !== true) {
        $request->session()->forget('kioskheld.cart');

        return redirect()
            ->route('shops.selection')
            ->with('status', 'Ein oder mehrere Artikel konnten nicht reserviert werden. Bitte prüfe deinen Warenkorb erneut.');
    }

    $request->session()->put('kioskheld.cart', array_merge($cart, [
        'payment_method' => 'paypal',
        'external_session_id' => $payload['external_session_id'],
        'validated' => $data,
        'validated_at' => now()->toIso8601String(),
        'shop' => data_get($data, 'shop'),
        'delivery' => data_get($data, 'delivery'),
        'totals' => data_get($data, 'totals'),
        'payment_methods' => data_get($data, 'payment_methods', []),
        'payment_capabilities' => data_get($data, 'payment_capabilities', []),
        'reservation' => is_array($reservation) ? $reservation : null,
    ]));
}




        $request->session()->put('kioskheld.checkout.customer', $customer);

        return redirect()
            ->route('checkout.show')
            ->with('status', 'Deine Lieferdaten wurden gespeichert.');
    }
}
