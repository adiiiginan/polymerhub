<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;


/*
|--------------------------------------------------------------------------
| ENGLISH (en) — hanya idcountry != 77
|--------------------------------------------------------------------------
*/

Route::prefix('en')->name('en.')->group(function () {

    // Public pages (no auth required)
    Route::get('/', fn() => view('en.welcome'))->name('home');
    Route::get('/brochure', fn() => view('en.frontend.brochure'))->name('brochure');
    Route::get('/contact', fn() => view('en.frontend.contact'))->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/privacy', fn() => view('en.privacy'))->name('privacy');

    Route::get('customer/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('customer.login');
    Route::post('customer/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('customer.login.submit');

    // The missing route for the zip code dropdown
    Route::post('/customer/get-zip-by-country', [DashboardController::class, 'getZipByCountry'])->name('customer.get-zip-by-country');

    // Protected pages (auth required)
    Route::middleware(['auth:customer', 'country.access:en'])->group(function () {
        // Customer
        Route::prefix('customer')->name('customer.')->group(function () {
            Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
            Route::post('/upload-payment', [DashboardController::class, 'uploadBukti'])->name('upload.payment');
            Route::patch('/address/set-primary/{address}', [DashboardController::class, 'setPrimaryAddress'])->name('address.set-primary');
            Route::get('/invoice/{id}', [DashboardController::class, 'showInvoice'])->name('invoice.show');
            Route::post('/order/receive/{id}', [DashboardController::class, 'receiveOrder'])->name('order.receive');
            Route::resource('address', AddressController::class);
            Route::get('/get-cities/{country_code}/{state_code}', [DashboardController::class, 'getCitiesByState'])->name('get.cities');
            Route::get('/get-states/{country_code}', [DashboardController::class, 'getStates'])->name('get-states');
            Route::get('/get-zip-code/{cityId}', [DashboardController::class, 'getZipCode']);
            Route::post('/validate-address', [\App\Http\Controllers\Frontend\FedExController::class, 'validateAddress'])->name('validate-address');
        });
    });

    // Produk
    Route::prefix('frontend')->name('frontend.')->group(function () {
        Route::get('/white-paper', [App\Http\Controllers\Frontend\ArticleController::class, 'index'])->name('white');
        Route::get('/brochure', fn() => view('en.frontend.brochure'))->name('brochure');
        Route::get('/contact', fn() => view('en.frontend.contact'))->name('contact');
        Route::get('/produk', [ProductController::class, 'index'])->name('produk');
        Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.show');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
        Route::post('/cart/update-quantity/{item_id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
        Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/cart/select-address/{id}', [CartController::class, 'selectAddress'])->name('cart.selectAddress');
        Route::post('/cart/update-shipping-selection', [CartController::class, 'updateShippingSelection'])->name('cart.updateShippingSelection');
        Route::post('/cart/shipping-rate', [CartController::class, 'getShippingRate'])->name('cart.getShippingRate');
    });

    Route::get('/transaksi/{id}', [CartController::class, 'transaksi'])->name('transaksi');

    Route::post('/cart/save-address', [CartController::class, 'saveAddress'])->name('cart.saveAddress');
    Route::post('/cart/save-shipping', [CartController::class, 'saveShipping'])->name('cart.saveShipping');
    Route::post('/get-fedex-rates', [\App\Http\Controllers\Frontend\FedExController::class, 'getRates'])->name('get-fedex-rates');
    Route::post('/validation', [\App\Http\Controllers\Frontend\FedExController::class, 'validateAddress'])->name('validation');
});

Route::post('customer/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('customer.logout')->middleware('auth:customer');
