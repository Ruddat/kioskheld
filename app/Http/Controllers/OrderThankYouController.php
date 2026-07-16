<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderThankYouController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $order = $request->session()->get('kioskheld.last_order');

        if (! is_array($order) || empty($order)) {
            return redirect()
                ->route('shops.selection')
                ->with('status', 'Es wurde keine abgeschlossene Bestellung gefunden.');
        }

        return view('checkout.thank-you', [
            'order' => $order,
        ]);
    }
}
