<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('authenticated user can create a cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withToken($token)
        ->postJson(route('cart.store'), [
            'status' => 'active',
        ])
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'active');
});

test('unauthenticated user cannot create a cart', function () {
    $this->postJson(route('cart.store'), [
        'status' => 'active',
    ])
        ->assertUnauthorized();
});
