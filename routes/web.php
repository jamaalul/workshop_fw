<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/otp', [App\Http\Controllers\Auth\OtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('auth/otp', [App\Http\Controllers\Auth\OtpController::class, 'verifyOtp'])->name('otp.verify');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    Route::get('/kategori', [DashboardController::class, 'kategori'])->name('kategori');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/buku', [DashboardController::class, 'buku'])->name('buku');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku/store', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

    Route::get('/laporan/buku', [LaporanController::class, 'bukuReport'])->name('laporan.buku');
    Route::get('/laporan/kategori', [LaporanController::class, 'kategoriReport'])->name('laporan.kategori');

    Route::get('/barang', [DashboardController::class, 'barang'])->name('barang');
    Route::get('/barang/create', [\App\Http\Controllers\BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [\App\Http\Controllers\BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [\App\Http\Controllers\BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [\App\Http\Controllers\BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [\App\Http\Controllers\BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/print-label', [\App\Http\Controllers\BarangController::class, 'printLabel'])->name('barang.printLabel');

    Route::get('/jquery/html-table', [DashboardController::class, 'jqueryHtmlTable'])->name('jquery.html-table');
    Route::get('/jquery/datatables', [DashboardController::class, 'jqueryDataTables'])->name('jquery.datatables');
    Route::get('/jquery/select', [DashboardController::class, 'jquerySelect'])->name('jquery.select');

    // Wilayah Routes
    Route::prefix('wilayah')->group(function () {
        Route::get('/', [WilayahController::class, 'index'])->name('wilayah.index');
        Route::get('/jquery', function() { return view('dashboard.wilayah.jquery'); })->name('wilayah.jquery');
        Route::get('/axios', function() { return view('dashboard.wilayah.axios'); })->name('wilayah.axios');
        Route::get('/provinsi', [WilayahController::class, 'getProvinsi'])->name('wilayah.provinsi');
        Route::get('/kota/{id}', [WilayahController::class, 'getKota'])->name('wilayah.kota');
        Route::get('/kecamatan/{id}', [WilayahController::class, 'getKecamatan'])->name('wilayah.kecamatan');
        Route::get('/kelurahan/{id}', [WilayahController::class, 'getKelurahan'])->name('wilayah.kelurahan');
    });

    // POS Routes
    Route::prefix('pos')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('pos.index');
        Route::get('/jquery', function() { return view('dashboard.pos.jquery'); })->name('pos.jquery');
        Route::get('/axios', function() { return view('dashboard.pos.axios'); })->name('pos.axios');
        Route::get('/barang/{kode}', [PosController::class, 'findBarang'])->name('pos.barang');
        Route::post('/bayar', [PosController::class, 'bayar'])->name('pos.bayar');
        // Vendor Routes
        Route::prefix('vendor')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\VendorController::class, 'dashboard'])->name('vendor.dashboard');
            Route::resource('/menus', App\Http\Controllers\VendorMenuController::class);
            Route::get('/orders', [App\Http\Controllers\VendorController::class, 'orders'])->name('vendor.orders');
        });
    });
});

// Webhook (No CSRF)
Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Guest/Customer Routes (Public)
Route::get('/canteen', [App\Http\Controllers\CanteenController::class, 'index'])->name('canteen.index');
Route::get('/canteen/menu/{vendor}', [App\Http\Controllers\CanteenController::class, 'getMenu']);
Route::post('/canteen/order', [App\Http\Controllers\CanteenController::class, 'store'])->name('canteen.order');
Route::get('/canteen/payment/{pesanan}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
Route::post('/canteen/payment/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
Route::get('/canteen/payment/status/{pesanan}', [App\Http\Controllers\PaymentController::class, 'checkStatus']);
Route::get('/canteen/payment/success/{pesanan}', [App\Http\Controllers\PaymentController::class, 'success'])->name('canteen.payment.success');

Route::get('/phpinfo', function () {
    phpinfo();
});
