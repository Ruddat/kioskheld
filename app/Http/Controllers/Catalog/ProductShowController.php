<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\CatalogProduct;
use Illuminate\View\View;

class ProductShowController extends Controller
{
    public function __invoke(string $productSlug): View
    {
        $product = CatalogProduct::query()
            ->with([
                'category:id,name,slug',
                'variants' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('name'),
            ])
            ->where('slug', $productSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = CatalogProduct::query()
            ->where('is_active', true)
            ->whereKeyNot($product->getKey())
            ->when(
                $product->catalog_category_id,
                fn ($query) => $query->where(
                    'catalog_category_id',
                    $product->catalog_category_id
                )
            )
            ->orderBy('name')
            ->limit(6)
            ->get();

        return view(
            'catalog.products.show',
            compact('product', 'relatedProducts')
        );
    }
}
