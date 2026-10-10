<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

// ---- Tamu (belum login) ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
});

// ---- Halaman internal: wajib login ----
Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Khusus admin. Didaftarkan SEBELUM route products.show agar /products/create tidak tertangkap {product}.
    Route::middleware(EnsureRole::class . ':admin')->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show']);
        Route::resource('users', UserController::class)->only(['index', 'create', 'store']);
    });

    // Admin dan staff boleh melihat produk
    Route::resource('products', ProductController::class)->only(['index', 'show']);

    // Transaksi stok: admin dan staff
    Route::get('/stock/in', [StockController::class, 'createIn'])->name('stock.in.create');
    Route::post('/stock/in', [StockController::class, 'storeIn'])->name('stock.in.store');
    Route::get('/stock/out', [StockController::class, 'createOut'])->name('stock.out.create');
    Route::post('/stock/out', [StockController::class, 'storeOut'])->name('stock.out.store');
    Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history');
});
