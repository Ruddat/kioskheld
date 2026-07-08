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
            ->filter(
                fn($capability) => ($capability['available'] ?? false) === true
                    && ($capability['direct_order_supported'] ?? false) === true
            )
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

        $customer = $request->validate([
            'customer_name' => ['required', 'string', 'min:2', 'max:120'],
            'customer_phone' => ['required', 'string', 'min:5', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:160'],

            'street' => ['required', 'string', 'min:2', 'max:160'],
            'house_number' => ['required', 'string', 'max:30'],
            'delivery_note' => ['nullable', 'string', 'max:500'],

            'payment_method' => ['required', 'string', Rule::in($allowedPaymentMethods)],
        ], [
            'customer_name.required' => 'Bitte gib deinen Namen ein.',
            'customer_phone.required' => 'Bitte gib deine Telefonnummer ein.',
            'customer_email.email' => 'Bitte gib eine gültige E-Mail-Adresse ein.',
            'street.required' => 'Bitte gib deine Straße ein.',
            'house_number.required' => 'Bitte gib deine Hausnummer ein.',
            'payment_method.required' => 'Bitte wähle eine Zahlungsart.',
            'payment_method.in' => 'Diese Zahlungsart ist für diese Bestellung nicht verfügbar.',
        ]);

        $request->session()->put('kioskheld.checkout.customer', $customer);

        return redirect()
            ->route('checkout.show')
            ->with('status', 'Deine Lieferdaten wurden gespeichert.');
    }
}
