<?php

use App\Http\Controllers\Admin\PartnerLeadAdminController;
use App\Http\Controllers\Admin\PartnerOnboardingAdminController;
use App\Http\Controllers\Admin\PartnerOnboardingImportController;
use App\Http\Controllers\Admin\PartnerOnboardingStatusController;
use App\Http\Controllers\Catalog\CategoryIndexController;
use App\Http\Controllers\Catalog\CategoryShowController;
use App\Http\Controllers\Catalog\ProductIndexController;
use App\Http\Controllers\Catalog\ProductShowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seo\SitemapController;
use App\Http\Controllers\Sitemap\CategorySitemapController;
use App\Http\Controllers\Sitemap\ProductSitemapController;
use App\Http\Controllers\Sitemap\SitemapIndexController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Standardsprache
|--------------------------------------------------------------------------
|
| Die Startseite ohne Sprachpräfix leitet auf Deutsch weiter.
|
*/

Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('seo.sitemap.index');

Route::get(
    '/sitemaps/{locale}.xml',
    [SitemapController::class, 'locale']
)
    ->whereIn('locale', config('localization.supported'))
    ->name('seo.sitemap.locale');


Route::permanentRedirect('/', '/de');

/*
|--------------------------------------------------------------------------
| Lokalisierte öffentliche Routen
|--------------------------------------------------------------------------
|
| Enthält Marketing, Shop, Warenkorb, Checkout, Partner und Onboarding.
|
*/

Route::prefix('{locale}')
    ->whereIn('locale', config('localization.supported'))
    ->middleware('locale')
    ->group(base_path('routes/localized.php'));

/*
|--------------------------------------------------------------------------
| Benutzerbereich
|--------------------------------------------------------------------------
|
| Zunächst nicht lokalisiert.
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Adminbereich
|--------------------------------------------------------------------------
|
| Der Adminbereich bleibt zunächst ohne Sprachpräfix.
|
*/

Route::prefix('{locale}')
    ->whereIn('locale', config('localization.supported', ['de', 'en', 'tr']))
    ->middleware(SetLocale::class)
    ->group(function () {
        Route::middleware(['auth', 'admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::view('/', 'admin.dashboard')
                    ->name('dashboard');

                Route::get(
                    '/partner-leads',
                    [PartnerLeadAdminController::class, 'index']
                )->name('partner-leads.index');

                Route::get(
                    '/partner-leads/{partnerLead}',
                    [PartnerLeadAdminController::class, 'show']
                )->name('partner-leads.show');

                Route::patch(
                    '/partner-leads/{partnerLead}/status',
                    [PartnerLeadAdminController::class, 'updateStatus']
                )->name('partner-leads.update-status');

                Route::post(
                    '/partner-leads/{partnerLead}/onboarding',
                    [PartnerOnboardingAdminController::class, 'store']
                )->name('partner-leads.onboarding.store');

                Route::post(
                    '/partner-onboardings/{partnerOnboarding}/import',
                    PartnerOnboardingImportController::class
                )->name('partner-onboardings.import');

                Route::post(
                    '/partner-onboardings/{partnerOnboarding}/status',
                    PartnerOnboardingStatusController::class
                )->name('partner-onboardings.status');
            });
    });



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




/*
|--------------------------------------------------------------------------
| Händlerportal
|--------------------------------------------------------------------------
|
| Das Händlerportal bleibt zunächst ohne Sprachpräfix.
|
*/

Route::middleware(['auth', 'vendor'])
    ->prefix('portal')
    ->name('vendor.')
    ->group(function () {
        Route::view('/', 'vendor.dashboard')
            ->name('dashboard');
    });

require __DIR__.'/auth.php';
