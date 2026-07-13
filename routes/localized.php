<?php

use App\Http\Controllers\CartValidationController;
use App\Http\Controllers\Catalog\CategoryIndexController;
use App\Http\Controllers\Catalog\CategoryShowController;
use App\Http\Controllers\Catalog\ProductIndexController;
use App\Http\Controllers\Catalog\ProductShowController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckoutCustomerController;
use App\Http\Controllers\CheckoutOrderController;
use App\Http\Controllers\CheckoutPaypalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderThankYouController;
use App\Http\Controllers\Partner\PartnerLeadController;
use App\Http\Controllers\Partner\PartnerOnboardingController;
use App\Http\Controllers\Partner\PartnerPageController;
use App\Http\Controllers\PostcodeAvailabilityController;
use App\Http\Controllers\ShopSelectionController;
use App\Http\Controllers\ShopShowController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
    ->name('home');

Route::get('/plz/pruefen', PostcodeAvailabilityController::class)
    ->name('postcode.check');

Route::post('/warenkorb/pruefen', CartValidationController::class)
    ->name('cart.validate');

Route::get('/shops/auswahl', ShopSelectionController::class)
    ->name('shops.selection');

Route::get('/kiosk/{citySlug}/{shopSlugWithId}', ShopShowController::class)
    ->name('shops.show');

Route::get('/shops/{shopSlug}', function (string $shopSlug) {
    return redirect()->route('shops.show', [
        'citySlug' => 'deine-naehe',
        'shopSlugWithId' => $shopSlug,
    ]);
})->name('shops.legacy');

Route::get('/kasse', CheckoutController::class)
    ->name('checkout.show');

Route::post('/kasse/kundendaten', CheckoutCustomerController::class)
    ->name('checkout.customer.store');

Route::post('/kasse/bestellen', CheckoutOrderController::class)
    ->name('checkout.order.store');

Route::post('/kasse/paypal/create', [CheckoutPaypalController::class, 'create'])
    ->name('checkout.paypal.create');

Route::get('/kasse/paypal/success', [CheckoutPaypalController::class, 'success'])
    ->name('checkout.paypal.success');

Route::get('/kasse/paypal/cancel', [CheckoutPaypalController::class, 'cancel'])
    ->name('checkout.paypal.cancel');

Route::get('/bestellung/danke', OrderThankYouController::class)
    ->name('checkout.thank-you');

Route::get('/partner', [PartnerPageController::class, 'index'])
    ->name('partner.index');

Route::get('/partner/registrieren', [PartnerPageController::class, 'register'])
    ->name('partner.register');

Route::post('/partner/registrieren', [PartnerLeadController::class, 'store'])
    ->name('partner.store');

Route::get('/partner/danke', [PartnerPageController::class, 'thankYou'])
    ->name('partner.thank-you');

Route::view('/ueber-uns', 'pages.about')
    ->name('about');

Route::view('/impressum', 'pages.legal.imprint')
    ->name('legal.imprint');

Route::view('/datenschutz', 'pages.legal.privacy')
    ->name('legal.privacy');

Route::view('/agb', 'pages.legal.terms')
    ->name('legal.terms');

Route::view('/faq', 'pages.faq')
    ->name('faq');

Route::get('/produkte', ProductIndexController::class)
    ->name('catalog.products.index');

Route::get('/produkte/{productSlug}', ProductShowController::class)
    ->name('catalog.products.show');

Route::get('/kategorien', CategoryIndexController::class)
    ->name('catalog.categories.index');

Route::get('/kategorien/{categorySlug}', CategoryShowController::class)
    ->name('catalog.categories.show');


Route::get('/partner/onboarding/{token}', [PartnerOnboardingController::class, 'show'])
    ->name('partner.onboarding.show');

Route::post('/partner/onboarding/{token}', [PartnerOnboardingController::class, 'store'])
    ->name('partner.onboarding.store');

Route::get('/partner/onboarding/danke/gesendet', [PartnerOnboardingController::class, 'thankYou'])
    ->name('partner.onboarding.thank-you');
