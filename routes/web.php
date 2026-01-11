<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route - Public
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes - Public
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes - Require Authentication
Route::middleware('auth')->group(function () {
    
    // Customer Routes - Only for customers
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/menu', [CustomerOrderController::class, 'menu'])->name('menu');
        Route::get('/cart', [CustomerOrderController::class, 'cart'])->name('cart');
        Route::get('/checkout', [CustomerOrderController::class, 'checkout'])->name('checkout');
        Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [CustomerOrderController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });
    
    // Admin Routes - Orders Management
    Route::get('/orders', [OrderStatusController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderStatusController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Admin Routes - Menu Management
    Route::get('/menu', [MenuController::class, 'halamanMenu'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
    
    // Admin Routes - Customer Management
    
    Route::get('/customers', [CustomerController::class, 'indexWeb'])->name('customers.indexWeb');
    Route::get('/export-customers', [CustomerController::class, 'export'])->name('customers.export');
    
    // Admin Routes - Reports & Statistics
    Route::get('/reports', [ReportController::class, 'indexWeb'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportPDF'])->name('reports.export');
});