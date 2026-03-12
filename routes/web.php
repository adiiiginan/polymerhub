<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\SitemapGenerator;
use App\Http\Controllers\Customer\DashboardController;





Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'submitRegistrationForm'])->name('register.submit');

Route::get('/get-cities/{countryId}', [RegisterController::class, 'getCities']);
Route::get('/get-zip-code/{cityId}', [RegisterController::class, 'getZipCode']);

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ShipController;
use App\Http\Controllers\Admin\StokOpnameController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\TransaksiInvoiceController;
use App\Http\Controllers\Frontend\CartController;

/*
|-------------------------- ------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login Routes for Admin
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'authenticate'])->name('login.authenticate');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Authenticated Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/user/detail/{id}', [AdminController::class, 'userDetail'])->name('user.detail');
        Route::post('/user/update-status', [AdminController::class, 'updateUserStatus'])->name('user.update.status');
        Route::post('/user/bulk-update-status', [AdminController::class, 'bulkUpdateStatus'])->name('user.bulk.update.status');

        Route::get('/contact', [App\Http\Controllers\Admin\ContactListController::class, 'index'])->name('contact.index');

        Route::resource('articles', 'App\Http\Controllers\Admin\ArticleController');
        Route::resource('produk', 'App\Http\Controllers\Admin\ProdukController');
        // Ship Routes
        Route::get('/ship/shipment', [ShipController::class, 'shipment'])->name('ship.shipment');
        Route::post('/ship/update-resi-lion/{id}', [ShipController::class, 'updateResiLion'])->name('admin.lion.update-resi');
        Route::post('/ship/create-shipment', [ShipController::class, 'createShipment'])->name('ship.create-shipment');
        Route::get('/ship/print_surat_jalan_dan_resi/{id}', [ShipController::class, 'print_surat_jalan'])->name('ship.print_surat_jalan_dan_resi');
        Route::get('/ship/packing-slip/{id}', [ShipController::class, 'packing_slip'])->name('ship.packing_slip');
        Route::resource('ship', ShipController::class)->where(['ship' => '[0-9]+']);

        // Stok Opname Routes
        Route::resource('stok-opname', StokOpnameController::class);
        Route::get('/stok-opname/selesai', [StokOpnameController::class, 'selesai'])->name('stok-opname.selesai');

        // Transaksi Routes
        Route::resource('invo', TransaksiInvoiceController::class)->names('transaksi.invo');
        Route::get('/transaksi/invoice/{id}', [TransaksiInvoiceController::class, 'show'])->name('transaksi.invoice_show');

        Route::get('/transaksi/invoice-paid', [TransaksiController::class, 'invoice_paid'])->name('transaksi.invoice_paid');
        Route::post('/transaksi/paid/{id}', [TransaksiInvoiceController::class, 'paid'])->name('transaksi.paid');
        Route::get('/transaksi/pending', [TransaksiController::class, 'pending'])->name('transaksi.pending');
        Route::get('/transaksi/po', [TransaksiController::class, 'po'])->name('transaksi.po');
        Route::get('/transaksi/detailpo/{id}', [TransaksiController::class, 'detailpo'])->name('Transaksi.detailpo');
        Route::get('/transaksi/completed', [TransaksiController::class, 'completed'])->name('transaksi.completed');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail');
        Route::post('/transaksi/{id}/approve', [TransaksiController::class, 'approve'])->name('transaksi.approve');

        Route::post('/transaksi/{id}/decline', [TransaksiController::class, 'decline'])->name('transaksi.decline');

        Route::post('/transaksi/paid/{id}', [TransaksiInvoiceController::class, 'paid'])->name('transaksi.paid');
        Route::post('/invoices/upload-pajak/{id}', [TransaksiInvoiceController::class, 'upload_pajak'])->name('invoices.upload_pajak');
        Route::post('invoice/update-status/{id}', [TransaksiInvoiceController::class, 'updateStatus'])->name('invoice.updateStatus');



        Route::get('user/customer', [AdminController::class, 'customer'])->name('user.customer');
        Route::get('user/verified', [AdminController::class, 'verified'])->name('user.verified');

        Route::get('/user', [AdminController::class, 'index'])->name('user.index');
        Route::get('/user/create', [AdminController::class, 'create'])->name('user.create');
        Route::post('/user', [AdminController::class, 'store'])->name('user.store');
        Route::get('/user/{user}/edit', [AdminController::class, 'edit'])->name('user.edit');
        Route::put('/user/{user}', [AdminController::class, 'update'])->name('user.update');
        Route::delete('/user/{user}', [AdminController::class, 'destroy'])->name('user.destroy');

        Route::get('/shipping/lion/print/{invoice}', [ShipController::class, 'print'])->name('lion.print');
        Route::post('/shipping/lion/update-status-after-print/{invoice}', [ShipController::class, 'updateStatusAfterPrint'])->name('ship.update-status-after-print');
    });
});





Route::get('/debug-routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'uri' => $route->uri,
            'name' => $route->getName(),
            'action' => $route->getActionName(),
            'methods' => $route->methods(),
        ];
    });
    return response()->json($routes);
});

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/location-demo', function () {
    return view('location-demo');
});


Route::post('/cart/store-lion', [CartController::class, 'storeLion'])->name('cart.store.lion');
Route::get('/checkout/success/{idtransaksi}', [CartController::class, 'success'])->name('id.frontend.checkout.success');
Route::post('/cart/set-address', [CartController::class, 'setAddress'])->name('cart.setAddress');
Route::post('/cart/set-shipping', [CartController::class, 'setShipping'])->name('cart.setShipping');
Route::post('/cart/save-shipping-details', [CartController::class, 'saveShippingDetails'])->name('cart.saveShippingDetails');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
