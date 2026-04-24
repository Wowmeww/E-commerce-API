<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\RegisterUserService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('Auth', weight: 1)]
class RegisterUserController extends Controller
{

    /**
     * Register.
     *
     * **Controller:** `Api/Auth/RegisterUserController`
     *
     * Register a new user and issue a Sanctum token.
     *
     */
    public function __invoke(
        RegisterRequest $request,
        RegisterUserService $service
    ): JsonResponse {

        $result = $service->handle($request->validated());

        return ApiResponse::success(
            data: [
                'user' => new UserResource($result['user']),
                'token' => $result['token']
            ],
            message: 'Registration successful. Please verify your email.',
            status: 201
        );
    }
}
