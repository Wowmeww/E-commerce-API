<?php

use App\Http\Controllers\Api\Product\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;

// category routes
Route::apiResource('category', CategoryController::class)
    ->middleware(['auth:sanctum', 'throttle:authenticated-actions']);

// needs auth
Route::apiResource('product', ProductController::class)
    ->only([
        'store',
        'update',
        'destroy',
    ])
    ->middleware(['auth:sanctum', 'throttle:authenticated-actions']);

Route::apiResource('product', ProductController::class)
    ->only([
        'index',
        'show',
    ])
    ->middleware('throttle:public-read');
