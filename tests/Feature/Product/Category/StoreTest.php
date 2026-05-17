<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

test('authenticated user can create a category', function () {
    $user = User::factory()->create();

    $payload = [
        'name' => 'Test Category',
        'slug' => 'test-category',
        'description' => 'A category description.',
    ];

    $response = $this->actingAs($user)
        ->postJson(route('category.store'), $payload);

    $response
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'Test Category')
        ->assertJsonPath('data.slug', 'test-category')
        ->assertJsonPath('data.description', 'A category description.');

    assertDatabaseHas('categories', [
        'name' => 'Test Category',
        'slug' => 'test-category',
        'description' => 'A category description.',
    ]);
});

test('unauthenticated user cannot create a category', function () {
    $payload = [
        'name' => 'Test Category',
        'slug' => 'test-category',
        'description' => 'A category description.',
    ];

    $this->postJson(route('category.store'), $payload)
        ->assertUnauthorized();
});
