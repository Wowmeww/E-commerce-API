<?php

use App\Models\Api\Cart\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('authenticated user can view a cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);

    $this->withToken($token)
        ->getJson(route('cart.show', $cart))
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $cart->id);
});

test('authenticated user cannot view another users cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create();

    $this->withToken($token)
        ->getJson(route('cart.show', $cart))
        ->assertForbidden();
});
