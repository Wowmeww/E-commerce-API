<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

// ─── Register ────────────────────────────────────────────────────────────────

test('user can register', function () {
    $this->postJson('/api/auth/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => ['user' => ['id', 'name', 'email', 'email_verified'], 'token'],
        ])
        ->assertJsonPath('success', true);

    assertDatabaseHas('users', ['email' => 'jane@example.com']);
});

test('register fails with duplicate email', function () {
    User::factory()->create(['email' => 'jane@example.com']);

    $this->postJson('/api/auth/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])
        ->assertStatus(422)
        ->assertJsonPath('success', false);
});

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

// ─── Forgot Password ─────────────────────────────────────────────────────────

test('forgot password sends reset link', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->postJson('/api/auth/forgot-password', ['email' => $user->email])
        ->assertOk()
        ->assertJsonPath('success', true);

    Notification::assertSentTo($user, ResetPassword::class);
});

// ─── Reset Password ──────────────────────────────────────────────────────────

test('user can reset password', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->postJson('/api/auth/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ])
        ->assertOk()
        ->assertJsonPath('success', true);
});
