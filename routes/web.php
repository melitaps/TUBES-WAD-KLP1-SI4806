<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderStatusController;

Route::get('/', [OrderStatusController::class, 'index']);

Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus']);