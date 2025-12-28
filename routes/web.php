<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Customer\OrderController;

Route::get('/', [OrderStatusController::class, 'index']);

Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus']);


Route::get('/', function () {
    return redirect()->route('customers.index');
});

Route::resource('customers', CustomerController::class);

Route::get('/export-customers', [CustomerController::class, 'export'])->name('customers.export');


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

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');