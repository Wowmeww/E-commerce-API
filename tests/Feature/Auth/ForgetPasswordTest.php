<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

pest()->use(RefreshDatabase::class);

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
