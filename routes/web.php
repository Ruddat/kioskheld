<?php

use App\Http\Controllers\CartValidationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckoutCustomerController;
use App\Http\Controllers\CheckoutOrderController;
use App\Http\Controllers\CheckoutPaypalController;
use App\Http\Controllers\OrderThankYouController;
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

Route::view('/ueber-uns', 'pages.about')->name('about');
Route::view('/impressum', 'pages.legal.imprint')->name('legal.imprint');
Route::view('/datenschutz', 'pages.legal.privacy')->name('legal.privacy');


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
    });

Route::middleware(['auth', 'vendor'])
    ->prefix('portal')
    ->name('vendor.')
    ->group(function () {
        Route::view('/', 'vendor.dashboard')->name('dashboard');
    });

require __DIR__.'/auth.php';
