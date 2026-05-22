<?php

use App\Http\Controllers\Api\Cart\CartController;
use App\Http\Controllers\Api\Cart\CartItemController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cart', CartController::class)
    ->except('store')
    ->middleware(['auth:sanctum', 'throttle:authenticated-actions']);

Route::apiResource('cart.items', CartItemController::class)
    ->middleware(['auth:sanctum', 'throttle:authenticated-actions']);
