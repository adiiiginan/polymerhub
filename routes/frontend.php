<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Auth\LoginController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('frontend')->name('frontend.')->group(function () {
    Route::get('/customer/login', [LoginController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/customer/login', [LoginController::class, 'login'])->name('customer.login.post');

    Route::get('/produk', [ProductController::class, 'index'])->name('produk');
    Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.show');
    Route::get('/brochure', [FrontendController::class, 'brochure'])->name('brochure');
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
    Route::get('/white-paper', [FrontendController::class, 'whitePaper'])->name('white');
    Route::get('/article/{article:heading}', [FrontendController::class, 'show'])->name('article.show');
});
