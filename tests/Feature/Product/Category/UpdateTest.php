<?php

use App\Models\Api\Product\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

test('authenticated user can update a category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $payload = [
        'name' => 'Updated Category Name',
        'slug' => 'updated-category-name',
        'description' => 'Updated category description.',
    ];

    $response = $this->actingAs($user)
        ->putJson(route('category.update', $category), $payload);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'Updated Category Name')
        ->assertJsonPath('data.slug', 'updated-category-name')
        ->assertJsonPath('data.description', 'Updated category description.');

    assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Updated Category Name',
        'slug' => 'updated-category-name',
    ]);
});

test('unauthenticated user cannot update a category', function () {
    $category = Category::factory()->create();

    $payload = [
        'name' => 'Updated Category Name',
        'slug' => 'updated-category-name',
        'description' => 'Updated category description.',
    ];

    $this->putJson(route('category.update', $category), $payload)
        ->assertUnauthorized();
});
