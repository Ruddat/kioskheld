<?php

namespace App\Http\Controllers\Sitemap;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CategorySitemapController extends Controller
{
    public function __invoke(): Response
    {
        $categories = Cache::remember(
            'catalog:sitemap:categories',
            now()->addHours(6),
            fn () => CatalogCategory::query()
                ->select(['slug', 'source_updated_at', 'updated_at'])
                ->where('is_active', true)
                ->whereHas(
                    'products',
                    fn ($query) => $query->where('is_active', true)
                )
                ->orderBy('id')
                ->get()
        );

        return response()
            ->view('sitemaps.categories', compact('categories'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
