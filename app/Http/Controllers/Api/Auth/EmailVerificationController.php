<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\EmailVerificationService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group('Auth', weight: 3)]
class EmailVerificationController extends Controller
{
    public function __construct(private readonly EmailVerificationService $emailVerificationService) {}

    /**
     * Verification -> Verify Email.
     *
     * **Controller:** `Api/Auth/EmailVerificationController`
     *
     *  Mark the user's email as verified.
     */
    public function verifyEmail(Request $request, string $id, string $hash): JsonResponse
    {
        $this->emailVerificationService->verifyEmail((int) $id, $hash, $request);

        return ApiResponse::success(message: 'Email verified successfully.');
    }

    /**
     * Verification -> Resend Email.
     *
     * **Controller:** `Api/Auth/EmailVerificationController`
     *
     *  Resend verification email for an authenticated user.
     */
    public function resendVerification(Request $request): JsonResponse
    {
        $this->emailVerificationService->resendVerificationEmail($request->user());

        return ApiResponse::success(message: 'Verification email resent.');
    }
}
