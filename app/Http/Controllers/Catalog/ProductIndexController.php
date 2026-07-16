<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\CatalogProduct;
use Illuminate\View\View;

class ProductIndexController extends Controller
{
    public function __invoke(): View
    {
        $products = CatalogProduct::query()
            ->with('category:id,name,slug')
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(24);

        return view('catalog.products.index', compact('products'));
    }
}
