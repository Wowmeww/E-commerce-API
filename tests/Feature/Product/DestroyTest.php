<?php

use App\Models\Api\Product\Category;
use App\Models\Api\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

pest()->use(RefreshDatabase::class);

test('authenticated user can delete a product', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $response = $this->withToken($token)
        ->deleteJson(route('product.destroy', $product));

    $response
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Product deleted successfully.');

    assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

test('unauthenticated user cannot delete a product', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $this->deleteJson(route('product.destroy', $product))
        ->assertUnauthorized();
});

test('deleting non-existent product returns 404', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $this->withToken($token)
        ->deleteJson(route('product.destroy', 999))
        ->assertNotFound();
});
