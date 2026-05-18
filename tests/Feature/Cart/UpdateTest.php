<?php

use App\Models\Api\Cart\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('authenticated user can update a cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);

    $this->withToken($token)
        ->patchJson(route('cart.update', $cart), ['status' => 'abandoned'])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'abandoned');
});

test('authenticated user cannot update another users cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create();

    $this->withToken($token)
        ->patchJson(route('cart.update', $cart), ['status' => 'abandoned'])
        ->assertForbidden();
});
