<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Customer\CustOrderController as CustomerOrder;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
Route::get('/orders', [OrderStatusController::class, 'index']);
Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus']);
});







Route::get('/customers', function () {
    return redirect()->route('customers.index');
});

Route::resource('customers', CustomerController::class);
Route::get('/export-customers', [CustomerController::class, 'export'])->name('customers.export');


// ===============================
// Customer Routes - Pemesanan
// ===============================
Route::prefix('customer')->name('customer.')->group(function () {

    // Halaman menu & form pemesanan
    Route::get('/orders', [CustomerOrder::class, 'indexView'])
        ->name('orders.index');

    // Simpan pesanan
    Route::post('/orders', [CustomerOrder::class, 'store'])
        ->name('orders.store');
});

Route::middleware('auth')->group(function () {
Route::get('/reports', [ReportController::class, 'indexWeb'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

