<?php

use App\Models\Api\Product\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

pest()->use(RefreshDatabase::class);

test('authenticated user can delete a category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($user)
        ->deleteJson(route('category.destroy', $category));

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Category deleted.');

    assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('unauthenticated user cannot delete a category', function () {
    $category = Category::factory()->create();

    $this->deleteJson(route('category.destroy', $category))
        ->assertUnauthorized();
});
