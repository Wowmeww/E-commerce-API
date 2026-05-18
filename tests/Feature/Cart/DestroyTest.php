<?php

use App\Models\Api\Cart\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

pest()->use(RefreshDatabase::class);

test('authenticated user can delete a cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create(['user_id' => $user->id]);

    $this->withToken($token)
        ->deleteJson(route('cart.destroy', $cart))
        ->assertOk()
        ->assertJsonPath('success', true);

    assertDatabaseMissing('carts', ['id' => $cart->id]);
});

test('authenticated user cannot delete another users cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $cart = Cart::factory()->create();

    $this->withToken($token)
        ->deleteJson(route('cart.destroy', $cart))
        ->assertForbidden();
});
