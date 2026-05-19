<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    // Public routes
    Route::post('/register', RegisterUserController::class)
        ->middleware('throttle:register');
    Route::post('/login', [AuthenticatedSessionController::class, 'login'])
        ->middleware('throttle:login');
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword'])
        ->middleware('throttle:forgot-password');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])
        ->middleware('throttle:reset-password');

    // Email verification (signed URL — no auth token required, but user must be identified)
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('verification.verify');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'logout']);
        Route::get('/me', [AuthenticatedSessionController::class, 'me']);
        Route::post('/email/verification-notification', [EmailVerificationController::class, 'resendVerification'])
            ->middleware('throttle:email-resend');
    });
});
