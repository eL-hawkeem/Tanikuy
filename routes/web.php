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
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Application Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products (Kelola Barang)
    Route::resource('products', ProductController::class);

    // Categories (Kategori Produk)
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // Suppliers (Kelola Supplier)
    Route::resource('suppliers', SupplierController::class);

    // Transactions / POS (Pencatatan Penjualan & Riwayat)
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    // Stock Management (Pemantauan Stok & Audit Log)
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/movements', [StockController::class, 'movements'])->name('movements');
        Route::post('/restock', [StockController::class, 'restock'])->name('restock');
        Route::post('/adjust', [StockController::class, 'adjust'])->name('adjust');
    });

    // EOQ & Needs Forecasting (Algoritma EOQ)
    Route::prefix('eoq')->name('eoq.')->group(function () {
        Route::get('/', [EoqController::class, 'index'])->name('index');
        Route::post('/calculate/{product}', [EoqController::class, 'calculate'])->name('calculate.single');
        Route::post('/calculate-all', [EoqController::class, 'calculateAll'])->name('calculate.all');
    });

    // Business Reports (Laporan Bisnis)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
    });
});
