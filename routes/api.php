<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::apiResource('customers', CustomerController::class);
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



});
