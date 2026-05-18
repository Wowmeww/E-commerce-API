<?php

use App\Models\Api\Cart\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('authenticated user can list their carts', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    Cart::factory()->count(2)->create(['user_id' => $user->id]);
    Cart::factory()->create();

    $this->withToken($token)
        ->getJson(route('cart.index'))
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(2, 'data.data');
});

test('unauthenticated user cannot list carts', function () {
    $this->getJson(route('cart.index'))
        ->assertUnauthorized();
});
