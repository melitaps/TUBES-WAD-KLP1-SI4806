<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderStatusController;

Route::post('/orders/{id}/status', [OrderStatusController::class, 'updateStatus']);