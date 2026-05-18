<?php

use App\Models\Api\Cart\Cart;
use App\Models\Api\Cart\CartItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

pest()->use(RefreshDatabase::class);

test('authenticated user can delete a cart item', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

    $this->withToken($token)
        ->deleteJson(route('cart.items.destroy', [$cart, $cartItem]))
        ->assertOk()
        ->assertJsonPath('success', true);

    assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
});

test('unauthenticated user cannot delete a cart item', function () {
    $cart = Cart::factory()->create();
    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

    $this->deleteJson(route('cart.items.destroy', [$cart, $cartItem]))
        ->assertUnauthorized();
});
