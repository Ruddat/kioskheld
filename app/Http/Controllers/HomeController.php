<?php

namespace App\Http\Controllers;

use App\Models\CatalogCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $catalogCategories = CatalogCategory::query()
            ->where('is_active', true)
            ->whereHas(
                'products',
                fn ($query) => $query->where('is_active', true)
            )
            ->withCount([
                'products as active_products_count' => fn ($query) =>
                    $query->where('is_active', true),
            ])
            ->orderByDesc('active_products_count')
            ->orderBy('name')
            ->limit(4)
            ->get();

        return view('pages.home', compact('catalogCategories'));
    }
}
