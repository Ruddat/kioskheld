<?php

use App\Http\Controllers\Catalog\CategoryIndexController;
use App\Http\Controllers\Catalog\CategoryShowController;
use App\Http\Controllers\Catalog\ProductIndexController;
use App\Http\Controllers\Catalog\ProductShowController;
use App\Http\Controllers\Sitemap\CategorySitemapController;
use App\Http\Controllers\Sitemap\ProductSitemapController;
use App\Http\Controllers\Sitemap\SitemapIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', SitemapIndexController::class)
    ->name('sitemap');

Route::get('/sitemaps/products.xml', ProductSitemapController::class)
    ->name('sitemaps.products');

Route::get('/sitemaps/categories.xml', CategorySitemapController::class)
    ->name('sitemaps.categories');

Route::prefix('{locale}')
    ->whereIn('locale', config('localization.supported'))
    ->middleware('locale')
    ->group(function (): void {
        Route::get('/produkte', ProductIndexController::class)
            ->name('catalog.products.index');

        Route::get('/produkte/{productSlug}', ProductShowController::class)
            ->name('catalog.products.show');

        Route::get('/kategorien', CategoryIndexController::class)
            ->name('catalog.categories.index');

        Route::get('/kategorien/{categorySlug}', CategoryShowController::class)
            ->name('catalog.categories.show');
    });
