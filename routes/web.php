<?php

use App\Http\Controllers\Admin\PartnerLeadAdminController;
use App\Http\Controllers\Admin\PartnerOnboardingAdminController;
use App\Http\Controllers\CartValidationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckoutCustomerController;
use App\Http\Controllers\CheckoutOrderController;
use App\Http\Controllers\CheckoutPaypalController;
use App\Http\Controllers\OrderThankYouController;
use App\Http\Controllers\Partner\PartnerLeadController;
use App\Http\Controllers\Partner\PartnerOnboardingController;
use App\Http\Controllers\Partner\PartnerPageController;
use App\Http\Controllers\PostcodeAvailabilityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopSelectionController;
use App\Http\Controllers\ShopShowController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::get('/plz/pruefen', PostcodeAvailabilityController::class)
    ->name('postcode.check');

Route::post('/warenkorb/pruefen', CartValidationController::class)
    ->name('cart.validate');

Route::get('/shops/auswahl', ShopSelectionController::class)
    ->name('shops.selection');

Route::get('/shops/{shopSlug}', ShopShowController::class)
    ->name('shops.show');

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


Route::view('/ueber-uns', 'pages.about')->name('about');
Route::view('/impressum', 'pages.legal.imprint')->name('legal.imprint');
Route::view('/datenschutz', 'pages.legal.privacy')->name('legal.privacy');
Route::view('/agb', 'pages.legal.terms')->name('legal.terms');
Route::view('/faq', 'pages.faq')->name('faq');



Route::get('/partner/onboarding/{token}', [PartnerOnboardingController::class, 'show'])
    ->name('partner.onboarding.show');

Route::post('/partner/onboarding/{token}', [PartnerOnboardingController::class, 'store'])
    ->name('partner.onboarding.store');

Route::get('/partner/onboarding/danke/gesendet', [PartnerOnboardingController::class, 'thankYou'])
    ->name('partner.onboarding.thank-you');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');

        Route::get('/partner-leads', [PartnerLeadAdminController::class, 'index'])
            ->name('partner-leads.index');

        Route::get('/partner-leads/{partnerLead}', [PartnerLeadAdminController::class, 'show'])
            ->name('partner-leads.show');

        Route::patch('/partner-leads/{partnerLead}/status', [PartnerLeadAdminController::class, 'updateStatus'])
            ->name('partner-leads.update-status');

            Route::post('/partner-leads/{partnerLead}/onboarding', [PartnerOnboardingAdminController::class, 'store'])
            ->name('partner-leads.onboarding.store');


    });

Route::middleware(['auth', 'vendor'])
    ->prefix('portal')
    ->name('vendor.')
    ->group(function () {
        Route::view('/', 'vendor.dashboard')->name('dashboard');
    });

require __DIR__.'/auth.php';
