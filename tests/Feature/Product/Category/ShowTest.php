<?php

use App\Models\Api\Product\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('user can view a single category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($user)->getJson(route('category.show', $category));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'description',
                'created_at',
                'updated_at',
            ],
        ])
        ->assertJsonPath('data.id', $category->id)
        ->assertJsonPath('data.name', $category->name)
        ->assertJsonPath('data.slug', $category->slug)
        ->assertJsonPath('data.description', $category->description);
});

test('unauthenticated user cannot access category show', function () {
    $category = Category::factory()->create();

    $response = $this->getJson(route('category.show', $category));

    $response->assertUnauthorized();
});

test('category show returns 404 for missing category', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('category.show', ['category' => 999]));

    $response->assertNotFound();
});
