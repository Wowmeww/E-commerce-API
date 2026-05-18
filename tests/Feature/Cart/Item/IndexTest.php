<?php

use App\Models\Api\Cart\Cart;
use App\Models\Api\Cart\CartItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('authenticated user can list cart items', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);

    CartItem::factory()->count(2)->create(['cart_id' => $cart->id]);

    $this->withToken($token)
        ->getJson(route('cart.items.index', $cart))
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(2, 'data');
});

test('unauthenticated user cannot list cart items', function () {
    $cart = Cart::factory()->create();

    $this->getJson(route('cart.items.index', $cart))
        ->assertUnauthorized();
});
