<?php

namespace App\Http\Controllers;

use App\Support\ShopUrl;
use Illuminate\Http\Request;

class ShopSelectionController extends Controller
{
    public function __invoke(Request $request)
    {
        $postcode = session('kioskheld.postcode');
        $available = session('kioskheld.available', false);
        $mode = session('kioskheld.mode');
        $shops = session('kioskheld.shops', []);
        $delivery = session('kioskheld.delivery', []);

        if (blank($postcode) || $available !== true) {
            return redirect()
                ->route('home')
                ->with('error', 'Bitte gib zuerst deine Postleitzahl ein.');
        }

if ($mode !== 'multiple') {
    $selectedShopSlug = session('kioskheld.selected_shop_slug');

    if ($selectedShopSlug) {
        $selectedShop = collect($shops)
            ->firstWhere('slug', $selectedShopSlug);

        return redirect()->route('shops.show', [
            'citySlug' => is_array($selectedShop) ? ShopUrl::citySlug($selectedShop) : 'deine-naehe',
            'shopSlugWithId' => is_array($selectedShop) ? ShopUrl::shopSlugWithId($selectedShop) : $selectedShopSlug,
        ]);
    }

    return redirect()
        ->route('home')
        ->with('error', 'Es wurde keine Shopauswahl gefunden.');
}

        if (! is_array($shops) || count($shops) === 0) {
            return redirect()
                ->route('home')
                ->with('error', 'Für deine Postleitzahl wurden keine Kioske gefunden.');
        }

        $shopsWithDelivery = collect($shops)
            ->map(function (array $shop) use ($delivery) {
                $deliveryMatch = collect($delivery)
                    ->firstWhere('shop_id', $shop['id'] ?? null);

                return [
                    ...$shop,
                    'delivery' => $deliveryMatch,
                    'delivery_rule' => $deliveryMatch['rule'] ?? null,
                    'delivery_area' => $deliveryMatch['area'] ?? null,
                    'delivery_location' => $deliveryMatch['location'] ?? null,
                ];
            })
            ->values()
            ->toArray();

        return view('shops.selection', [
            'postcode' => $postcode,
            'city' => session('kioskheld.city'),
            'district' => session('kioskheld.district'),
            'shops' => $shopsWithDelivery,
        ]);
    }
}
