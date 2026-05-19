<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('limits repeated failed login attempts and returns 429 after the threshold', function () {
    $payload = ['email' => 'does-not-exist@example.test', 'password' => 'bad-password'];

    // Make 6 attempts — limiter is set to 5 per minute for 'login'
    for($i = 1; $i <= 6; $i++) {
        $response = $this->postJson('/api/auth/login', $payload);

        if($i <= 5) {
            $response->assertStatus(422);
        } else {
            $response->assertStatus(429);
        }
    }
});
