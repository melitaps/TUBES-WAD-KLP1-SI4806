<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================
// PUBLIC ROUTES
// ====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ====================
// AUTHENTICATED ROUTES
// ====================
Route::middleware('auth')->group(function () {

    // ====================
    // CUSTOMER (USER) AREA
    // ====================
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/menu', [CustomerOrderController::class, 'menu'])->name('menu');
        Route::get('/cart', [CustomerOrderController::class, 'cart'])->name('cart');
        Route::get('/checkout', [CustomerOrderController::class, 'checkout'])->name('checkout');
        Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [CustomerOrderController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });

    // ====================
    // ADMIN - ORDERS
    // ====================
    Route::get('/orders', [OrderStatusController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderStatusController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus'])
        ->name('orders.updateStatus');

    // ====================
    // ADMIN - MENU
    // ====================
    Route::get('/menu/export', [MenuController::class, 'export'])->name('menu.export');
    
    Route::get('/menu', [MenuController::class, 'halamanMenu'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    // ====================
    // ADMIN - CUSTOMERS
    // ====================
    Route::get('/customers/export', [CustomerController::class, 'export'])
        ->name('customers.export');

    Route::resource('customers', CustomerController::class)
        ->except(['show']);

    // ====================
    // ADMIN - REPORTS
    // ====================
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports', [ReportController::class, 'indexWeb'])->name('reports.index');
});