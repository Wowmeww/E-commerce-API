<?php

use App\Models\Api\Cart\Cart;
use App\Models\Api\Cart\CartItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('authenticated user can update a cart item quantity', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id, 'quantity' => 2]);

    $this->withToken($token)
        ->patchJson(route('cart.items.update', [$cart, $cartItem]), [
            'quantity' => 5,
        ])
        ->assertOk()
        ->assertJsonPath('data.quantity', 5)
        ->assertJsonPath('data.total_price', $cartItem->price * 5);
});

test('authenticated user cannot update another users cart item', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create();
    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

    $this->withToken($token)
        ->patchJson(route('cart.items.update', [$cart, $cartItem]), [
            'quantity' => 5,
        ])
        ->assertForbidden();
});
