<?php

use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;

// needs auth
Route::apiResource('product', ProductController::class)
    ->except('index')
    ->middleware('auth:sanctum');
Route::apiResource('product', ProductController::class)
    ->only('index');
