<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderThankYouController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('checkout.thank-you', [
            'order' => $request->session()->get('kioskheld.last_order', []),
        ]);
    }
}
