<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use Illuminate\View\View;

class CategoryShowController extends Controller
{
    public function __invoke(string $categorySlug): View
    {
        $category = CatalogCategory::query()
            ->where('slug', $categorySlug)
            ->where('is_active', true)
            ->whereHas(
                'products',
                fn ($query) => $query->where('is_active', true)
            )
            ->firstOrFail();

        $products = $category->products()
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(24);

        return view(
            'catalog.categories.show',
            compact('category', 'products')
        );
    }
}
