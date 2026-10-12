<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EoqController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Application Routes (Admin & Kasir)
Route::middleware('auth')->group(function () {
    // Dashboard (Dapat diakses Admin dan Kasir)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions / POS (Kasir & Admin dapat mencatat transaksi & mencetak faktur)
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');

    // Katalog & Stok Barang (Kasir & Admin dapat melihat katalog dan status stok barang)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    // ==========================================
    // KHUSUS ADMINISTRATOR (Role: Admin)
    // ==========================================
    Route::middleware('role:admin')->group(function () {
        // Pembatalan Transaksi Penjualan (Hanya Admin yang berwenang)
        Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

        // Kelola Barang (Tambah, Ubah, Hapus)
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Kategori Produk
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

        // Kelola Supplier
        Route::resource('suppliers', SupplierController::class);

        // Operasi Stok: Riwayat Pergerakan, Restock Barang Masuk & Penyesuaian Stok
        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('/movements', [StockController::class, 'movements'])->name('movements');
            Route::post('/restock', [StockController::class, 'restock'])->name('restock');
            Route::post('/adjust', [StockController::class, 'adjust'])->name('adjust');
        });

        // Algoritma EOQ & Perhitungan Stok Optimal
        Route::prefix('eoq')->name('eoq.')->group(function () {
            Route::get('/', [EoqController::class, 'index'])->name('index');
            Route::post('/calculate/{product}', [EoqController::class, 'calculate'])->name('calculate.single');
            Route::post('/calculate-all', [EoqController::class, 'calculateAll'])->name('calculate.all');
        });

        // Laporan Bisnis, Stok & Analisis Laba/Rugi
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
            Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
        });

        // Manajemen Pengguna & Hak Akses (User & Kasir Management)
        Route::resource('users', UserController::class)->except(['show']);
    });
});
