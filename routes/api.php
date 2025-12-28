
<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\AuthController;




/**
 * ==========1===========
 * unprotected routes for user registration and login
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/**
 * =========2===========
 * protected routes, only accessible with valid token
 */
Route::middleware('auth:sanctum')->group(function () {
    /**
     * =========3===========
     * User logout route
     */
    Route::post('/logout', [AuthController::class, 'logout']);
     /**
     * ORDER - MENU 3
     */
    Route::get('/order', [OrderController::class, 'index']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/order/{id}', [OrderController::class, 'show']);

    /**
     * HARI LIBUR NASIONAL
     */
    Route::get('/holidays', [HolidayController::class, 'index']);
    Route::post('/menu', [MenuController::class, 'store']);


});
