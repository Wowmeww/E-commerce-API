<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthenticatedSessionService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group('Auth', weight: 2)]
class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly AuthenticatedSessionService $authService) {}

    /**
     * Login.
     *
     * **Controller:** `Api/Auth/AuthenticatedSessionController`
     *
     * Authenticate user credentials and issue a token. Revoke previous tokens (single-session policy)
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return ApiResponse::success(
            data: ['user' => new UserResource($result['user']), 'token' => $result['token']],
            message: 'Login successful.'
        );
    }

    /**
     * Logout.
     *
     * **Controller:** `Api/Auth/AuthenticatedSessionController`
     *
     * Revoke the user's current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return ApiResponse::success(message: 'Logged out successfully.');
    }

    /**
     * User/Me.
     *
     * **Controller:** `Api/Auth/AuthenticatedSessionController`
     *
     * Get user information
     */
    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success(
            data: ['user' => new UserResource($request->user())],
            message: 'Authenticated user retrieved.'
        );
    }
}
