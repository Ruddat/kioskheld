<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopShowController extends Controller
{
    public function __invoke(Request $request, string $shopSlug)
    {
        $apiUrl = config('services.justdeliver.kioskheld_api_url');
        $apiKey = config('services.justdeliver.kioskheld_api_key');

        if (blank($apiUrl) || blank($apiKey)) {
            abort(500, 'Kioskheld API ist nicht korrekt konfiguriert.');
        }

        $postcode = session('kioskheld.postcode');
        $available = session('kioskheld.available', false);
        $mode = session('kioskheld.mode');
        $selectedShopSlug = session('kioskheld.selected_shop_slug');
        $sessionShops = session('kioskheld.shops', []);

        if (blank($postcode) || $available !== true) {
            return redirect()
                ->route('home')
                ->with('error', 'Bitte gib zuerst deine Postleitzahl ein.');
        }

        if (! $this->shopIsAllowedForSession($shopSlug, $mode, $selectedShopSlug, $sessionShops)) {
            Log::warning('Kioskheld shop access blocked because shop is not available for session postcode', [
                'slug' => $shopSlug,
                'postcode' => $postcode,
                'mode' => $mode,
                'selected_shop_slug' => $selectedShopSlug,
            ]);

            return redirect()
                ->route('home')
                ->with('error', 'Dieser Kiosk liefert aktuell nicht an deine Postleitzahl.');
        }

        try {
            $shopResponse = Http::timeout(5)
                ->when(app()->environment('local'), fn ($http) => $http->withoutVerifying())
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->get(rtrim($apiUrl, '/') . '/shops/' . $shopSlug);

            if ($shopResponse->status() === 404) {
                abort(404);
            }

            if (! $shopResponse->successful()) {
                Log::warning('Kioskheld shop request failed', [
                    'slug' => $shopSlug,
                    'status' => $shopResponse->status(),
                    'body' => $shopResponse->body(),
                ]);

                abort(502, 'Shop konnte nicht geladen werden.');
            }

            $catalogResponse = Http::timeout(8)
                ->when(app()->environment('local'), fn ($http) => $http->withoutVerifying())
                ->withHeaders([
                    'X-Kioskheld-Api-Key' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->get(rtrim($apiUrl, '/') . '/shops/' . $shopSlug . '/catalog');

            if (! $catalogResponse->successful()) {
                Log::warning('Kioskheld catalog request failed', [
                    'slug' => $shopSlug,
                    'status' => $catalogResponse->status(),
                    'body' => $catalogResponse->body(),
                ]);

                abort(502, 'Katalog konnte nicht geladen werden.');
            }

            $shop = $shopResponse->json('data');
            $catalogPayload = $catalogResponse->json();

            $catalog = $catalogPayload['data'] ?? [];
            $menus = $catalogPayload['menus'] ?? [];
            $sections = $catalogPayload['sections'] ?? [];

            if (
                ! is_array($shop)
                || ! is_array($catalogPayload)
                || ! is_array($catalog)
                || ! is_array($menus)
                || ! is_array($sections)
            ) {
                Log::warning('Kioskheld shop page returned invalid payload', [
                    'slug' => $shopSlug,
                    'shop_payload' => $shopResponse->body(),
                    'catalog_payload' => $catalogResponse->body(),
                ]);

                abort(502, 'Shopdaten konnten nicht korrekt geladen werden.');
            }

            $this->storeSelectedShopInSession($request, $shop, $sessionShops);

            $groupedCatalog = collect($catalog)
                ->groupBy(fn ($product) => $product['category']['name'] ?? 'Weitere Produkte')
                ->map(fn ($products) => $products->values())
                ->toArray();

            $productsByCategoryId = collect($catalog)
                ->groupBy(fn ($product) => $product['category']['id'] ?? 'unknown')
                ->toArray();

            return view('shops.show', [
                'shop' => $shop,
                'catalog' => $catalog,
                'menus' => $menus,
                'sections' => $sections,
                'groupedCatalog' => $groupedCatalog,
                'productsByCategoryId' => $productsByCategoryId,
                'postcode' => $postcode,
                'deliveryRule' => session('kioskheld.delivery_rule'),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Kioskheld shop page exception', [
                'slug' => $shopSlug,
                'postcode' => $postcode,
                'message' => $exception->getMessage(),
            ]);

            abort(500, 'Shopseite konnte nicht geladen werden.');
        }
    }

    private function shopIsAllowedForSession(
        string $shopSlug,
        ?string $mode,
        ?string $selectedShopSlug,
        array $sessionShops
    ): bool {
        if ($mode === 'single') {
            return $selectedShopSlug === $shopSlug;
        }

        if ($mode === 'multiple') {
            return collect($sessionShops)
                ->contains(fn ($shop) => ($shop['slug'] ?? null) === $shopSlug);
        }

        return false;
    }

    private function storeSelectedShopInSession(Request $request, array $shop, array $sessionShops): void
    {
        $shopId = $shop['id'] ?? null;
        $shopSlug = $shop['slug'] ?? null;

        if (! $shopId || ! $shopSlug) {
            return;
        }

        $delivery = collect(session('kioskheld.delivery', []))
            ->firstWhere('shop_id', $shopId);

        $request->session()->put('kioskheld.selected_shop_id', $shopId);
        $request->session()->put('kioskheld.selected_shop_slug', $shopSlug);
        $request->session()->put('kioskheld.delivery_rule', $delivery['rule'] ?? null);
    }
}
