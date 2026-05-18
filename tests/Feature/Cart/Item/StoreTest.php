<?php

use App\Models\Api\Cart\Cart;
use App\Models\Api\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

test('authenticated user can add an item to the cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create();

    $this->withToken($token)
        ->postJson(route('cart.items.store', $cart), [
            'product_id' => $product->id,
            'quantity' => 3,
        ])
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.cart_id', $cart->id)
        ->assertJsonPath('data.quantity', 3);

    assertDatabaseHas('cart_items', [
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);
});

test('unauthenticated user cannot add a cart item', function () {
    $cart = Cart::factory()->create();
    $product = Product::factory()->create();

    $this->postJson(route('cart.items.store', $cart), [
        'product_id' => $product->id,
        'quantity' => 1,
    ])
        ->assertUnauthorized();
});
