<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'registerApi']);
    Route::post('/login', [LoginController::class, 'loginApi']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // logout
    Route::post('/logout', [LoginController::class, 'logoutApi']);

    // ======================
    // CUSTOMER MANAGEMENT
    // ======================
    Route::apiResource('customers', CustomerController::class);

    // ======================
    // MENU
    // ======================
    Route::apiResource('menu', MenuController::class);

    // ======================
    // ORDERS
    // ======================
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus']);

    // ======================
    // HOLIDAYS
    // ======================
    Route::get('/holidays', [HolidayController::class, 'index']);

    // ======================
    // REPORTS
    // ======================
    Route::get('/reports', [ReportController::class, 'index']);
});
