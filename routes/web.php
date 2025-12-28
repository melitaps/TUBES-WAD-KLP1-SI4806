<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\OrderController;

Route::get('/', function () {
    return view('welcome');
});
});

// ===============================
// Customer Routes - Pemesanan
// ===============================
Route::prefix('customer')->name('customer.')->group(function () {

    // Halaman menu & form pemesanan
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    // Simpan pesanan
    Route::post('/orders', [OrderController::class, 'store'])
        ->name('orders.store');
});
