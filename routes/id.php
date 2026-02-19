<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\LionParcelController;
use App\Http\Controllers\Frontend\CartController;

/*
|--------------------------------------------------------------------------
| INDONESIA (id) — hanya idcountry = 77
|--------------------------------------------------------------------------
*/

Route::prefix('id')->name('id.')->group(function () {

    // Authentication
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });

    // Public pages (no auth required)
    Route::get('/', fn() => view('id.welcome'))->name('home');
    Route::get('/privacy', fn() => view('id.privacy'))->name('privacy');

    // Frontend routes that might not need auth
    Route::prefix('frontend')->name('frontend.')->group(function () {
        Route::get('/white-paper', [ArticleController::class, 'index'])->name('white');
        Route::get('/brochure', fn() => view('id.frontend.brochure'))->name('brochure');
        Route::get('/contact', fn() => view('id.frontend.contact'))->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
        Route::get('/produk', [ProductController::class, 'index'])->name('produk');
        Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.show');
        Route::post('/lionparcel/get-services', [LionParcelController::class, 'getServices'])->name('lionparcel.get-services');
    });

    // Protected pages (auth required)
    Route::middleware(['auth:customer', 'country.access:indo'])->group(function () {
        // Customer
        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
            Route::get('/address/create', [DashboardController::class, 'createAddress'])->name('address.create');
            Route::post('/address', [DashboardController::class, 'buatalamat'])->name('address.store');
            Route::get('/address/{address}/edit', [DashboardController::class, 'editAddress'])->name('address.edit');
            Route::put('/address/{address}', [DashboardController::class, 'updateAddress'])->name('address.update');
            Route::delete('/address/{address}', [DashboardController::class, 'destroyAddress'])->name('address.destroy');
            Route::patch('/address/set-primary/{address}', [DashboardController::class, 'setPrimaryAddress'])->name('address.set-primary');
            Route::post('/get-states-by-country', [DashboardController::class, 'getStates'])->name('get-states-by-country');
            Route::post('/get-cities', [DashboardController::class, 'getCities'])->name('get.cities');
            Route::post('/get-districts-by-city', [DashboardController::class, 'getDistricts'])->name('get-districts-by-city');
            Route::post('/get-zip-code', [DashboardController::class, 'getZipCode'])->name('get-zip-code');
            Route::post('/get-zip-by-country', [DashboardController::class, 'getZipByCountry'])->name('get-zip-by-country');
            Route::get('/get-zip-code/{cityId}', [DashboardController::class, 'getZipCode']);
            Route::post('/validate-address', [DashboardController::class, 'validateAddress'])->name('validate-address');
        });

        // Cart and Checkout
        Route::prefix('frontend')->name('frontend.')->group(function () {
            Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
            Route::post('/cart/add', [CartController::class, 'storeLion'])->name('cart.add');
            Route::delete('/cart/remove/{item}', [CartController::class, 'destroy'])->name('cart.remove');
            Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
            Route::post('/cart/checkout-lion-parcel', [CartController::class, 'checkoutLionParcel'])->name('cart.checkout-lion-parcel');
            Route::post('/cart/set-address', [CartController::class, 'setAddress'])->name('cart.setAddress');
            Route::post('/cart/set-shipping', [CartController::class, 'setShipping'])->name('cart.setShipping');
            Route::post('/cart/update-shipping-selection', [CartController::class, 'updateShippingSelection'])->name('cart.updateShippingSelection');
        });

        Route::get('/transaksi/{id}', [CartController::class, 'transaksi'])->name('transaksi');
    });
});

Route::post('customer/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('customer.logout')->middleware('auth:customer');
