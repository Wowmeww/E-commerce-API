<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\PasswordService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('Auth')]
class PasswordController extends Controller
{
    public function __construct(private readonly PasswordService $passwordService) {}

    /**
     * Password -> Forgot Password.
     *
     * **Controller:** `Api/Auth/PasswordController`
     *
     * Send a password reset link to the given email.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->passwordService->sendPasswordResetLink($request->validated('email'));

        return ApiResponse::success(message: 'Password reset link sent to your email.');
    }

    /**
     * Password -> Reset Password.
     *
     * **Controller:** `Api/Auth/PasswordController`
     *
     * Reset the user's password using the provided token.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->passwordService->resetPassword($request->validated());

        return ApiResponse::success(message: 'Password has been reset successfully.');
    }
}
