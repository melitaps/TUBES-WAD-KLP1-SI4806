
<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReportController;

/**
 * 
 * unprotected routes for user registration and login
 */
Route::prefix('auth')->group(function () {
Route::post('/register', [RegisterController::class, 'registerApi']);
Route::post('/login', [LoginController::class, 'loginApi']);
});
/**
 * 
 * protected routes, only accessible with valid token
 */

Route::middleware('auth:sanctum')->group(function () {
    /**
     * 
     * User logout route
     */
    Route::post('/logout', [LoginController::class, 'logoutApi']);
     /**
     * ORDER - MENU 3
     */
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    /**
     * HARI LIBUR NASIONAL
     */
    Route::get('/holidays', [HolidayController::class, 'index']); 
  //menu
    Route::get('/menu', [MenuController::class, 'index']);
    Route::post('/menu', [MenuController::class, 'store']);
    Route::get('/menu/{id}', [MenuController::class, 'show']);
    Route::put('/menu/{id}', [MenuController::class, 'update']);
    Route::delete('/menu/{id}', [MenuController::class, 'destroy']);


    //customer
   // Route::apiResource('customers', CustomerController::class);
    
    //status order dr admin
    Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus']);

    //laporan
   Route::get('/reports', [ReportController::class, 'index']);

});
