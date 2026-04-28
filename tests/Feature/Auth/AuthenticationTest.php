<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;

pest()->use(RefreshDatabase::class);


// ─── Login ───────────────────────────────────────────────────────────────────

test('user can login', function () {
    $user = User::factory()->create(['password' => bcrypt('secret123')]);

    $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'secret123',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['data' => ['user', 'token']]);
});

test('login fails with wrong password', function () {
    $user = User::factory()->create(['password' => bcrypt('secret123')]);

    $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ])
        ->assertStatus(422)
        ->assertJsonPath('success', false);
});

// ─── Logout ──────────────────────────────────────────────────────────────────

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/auth/logout')
        ->assertOk()
        ->assertJsonPath('success', true);

    assertDatabaseCount('personal_access_tokens', 0);
});


// ─── Me ──────────────────────────────────────────────────────────────────────

test('authenticated user can fetch profile', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/auth/me')
        ->assertOk()
        ->assertJsonPath('data.user.email', $user->email);
});

test('unauthenticated user cannot access profile', function () {
    $this->getJson('/api/auth/me')->assertStatus(401);
});
