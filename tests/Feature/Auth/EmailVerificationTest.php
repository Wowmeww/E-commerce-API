<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

pest()->use(RefreshDatabase::class);

// ─── Verify Email ────────────────────────────────────────────────────────────

test('user can verify email with valid signed url', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]
    );

    $this->get($verificationUrl)
        ->assertOk()
        ->assertJsonPath('success', true);

    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeTrue();
});

test('verify email fails with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->getKey(),
            'hash' => 'invalid-hash',
        ]
    );

    // Signed middleware redirects (302) when hash is invalid
    $this->get($verificationUrl)
        ->assertStatus(302);
});

test('verify email fails when already verified', function () {
    $user = User::factory()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]
    );

    // Already verified - signed middleware passes but service throws validation
    // This returns 302 redirect as well
    $this->get($verificationUrl)
        ->assertStatus(302);
});

// ─── Resend Verification Email ───────────────────────────────────────────────

test('authenticated user can resend verification email', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/auth/email/verification-notification')
        ->assertOk()
        ->assertJsonPath('success', true);

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('resend verification fails when already verified', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/auth/email/verification-notification')
        ->assertStatus(422)
        ->assertJsonPath('success', false);
});
