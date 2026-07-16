<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use Illuminate\View\View;

class CategoryIndexController extends Controller
{
    public function __invoke(): View
    {
        $categories = CatalogCategory::query()
            ->where('is_active', true)
            ->whereHas('products', fn ($query) => $query->where('is_active', true))
            ->withCount([
                'products as active_products_count' => fn ($query) =>
                    $query->where('is_active', true),
            ])
            ->orderBy('name')
            ->get();

        return view('catalog.categories.index', compact('categories'));
    }
}
