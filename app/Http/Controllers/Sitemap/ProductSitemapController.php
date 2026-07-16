<?php

namespace App\Http\Controllers\Sitemap;

use App\Http\Controllers\Controller;
use App\Models\CatalogProduct;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ProductSitemapController extends Controller
{
    public function __invoke(): Response
    {
        $products = Cache::remember(
            'catalog:sitemap:products',
            now()->addHours(6),
            fn () => CatalogProduct::query()
                ->select(['slug', 'source_updated_at', 'updated_at'])
                ->where('is_active', true)
                ->orderBy('id')
                ->get()
        );

        return response()
            ->view('sitemaps.products', compact('products'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
