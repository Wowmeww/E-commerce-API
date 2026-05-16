<?php

use App\Http\Controllers\Api\Product\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;

// category routes
Route::apiResource('category', CategoryController::class)
    ->middleware(['auth:sanctum']);

// needs auth
Route::apiResource('product', ProductController::class)
    ->only([
        'store',
        'update',
        'destroy'
    ])
    ->middleware('auth:sanctum');

Route::apiResource('product', ProductController::class)
    ->only([
        'index',
        'show'
    ]);
