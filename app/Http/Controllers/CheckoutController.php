<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
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

        if ($validatedAt && Carbon::parse($validatedAt)->lt(now()->subMinutes(10))) {
            $request->session()->forget('kioskheld.cart');
            $request->session()->forget('kioskheld.checkout.customer');

            return redirect()
                ->route('shops.selection')
                ->with('status', 'Deine Warenkorb-Prüfung ist abgelaufen. Bitte prüfe erneut.');
        }

        $validatedCart = $validated['cart'] ?? $validated;

        return view('checkout.show', [
            'cart' => $cart,
            'validated' => $validated,
            'items' => $validatedCart['items'] ?? $cart['items'] ?? [],
            'totals' => $validatedCart['totals'] ?? $validated['totals'] ?? [],
            'customer' => $request->session()->get('kioskheld.checkout.customer', []),
            'paymentCapabilities' => $validatedCart['payment_capabilities']
                ?? $validated['payment_capabilities']
                ?? [],
        ]);
    }
}
