<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderStatusController;

Route::put('/orders/{id}/status', [OrderStatusController::class, 'updateStatus']);