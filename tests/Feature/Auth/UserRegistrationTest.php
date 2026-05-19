<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

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
