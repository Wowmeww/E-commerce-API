<?php

use App\Models\Api\Product\Category;
use App\Models\Api\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

test('authenticated user can create a product', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $category = Category::factory()->create();

    $payload = [
        'name' => 'Test Product',
        'slug' => 'test-product',
        'short_description' => 'A short description.',
        'description' => 'A longer description for the test product.',
        'price' => 1000,
        'sale_price' => 899,
        'cost_price' => 800,
        'stock_quantity' => 100,
        'stock_status' => 'in_stock',
        'category_id' => $category->id,
        'is_active' => true,
        'is_featured' => false,
    ];

    $response = $this->withToken($token)
        ->postJson(route('product.store'), $payload);

    $response
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'Test Product')
        ->assertJsonPath('data.slug', 'test-product')
        ->assertJsonPath('data.category_id', $category->id);

    assertDatabaseHas('products', [
        'name' => 'Test Product',
        'slug' => 'test-product',
        'category_id' => $category->id,
    ]);
});

test('unauthenticated user cannot create a product', function () {
    $category = Category::factory()->create();

    $payload = [
        'name' => 'Test Product',
        'slug' => 'test-product',
        'short_description' => 'A short description.',
        'description' => 'A longer description for the test product.',
        'price' => 1000,
        'sale_price' => 899,
        'cost_price' => 800,
        'stock_quantity' => 100,
        'stock_status' => 'in_stock',
        'category_id' => $category->id,
        'is_active' => true,
        'is_featured' => false,
    ];

    $this->postJson(route('product.store'), $payload)
        ->assertUnauthorized();
});
