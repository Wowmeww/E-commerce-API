<?php

use App\Models\Api\Cart\Cart;
use App\Models\Api\Cart\CartItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('authenticated user can view a cart item', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

    $this->withToken($token)
        ->getJson(route('cart.items.show', [$cart, $cartItem]))
        ->assertOk()
        ->assertJsonPath('data.id', $cartItem->id);
});

test('authenticated user cannot view another users cart item', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create();
    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

    $this->withToken($token)
        ->getJson(route('cart.items.show', [$cart, $cartItem]))
        ->assertForbidden();
});
