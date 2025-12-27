<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HolidayController;

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
