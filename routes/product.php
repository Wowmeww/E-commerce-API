<?php

use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;

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
