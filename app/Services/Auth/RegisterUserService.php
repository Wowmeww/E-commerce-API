<?php

namespace App\Services\Auth;

use App\Models\Api\Cart\Cart;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterUserService
{
    /**
     * Register a new user and issue a Sanctum token.
     */
    public function handle(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        // Create a cart for the new user
        Cart::create(['user_id' => $user->id]);

        $token = $user->createToken('api-token')->plainTextToken;

        return compact('user', 'token');
    }
}
