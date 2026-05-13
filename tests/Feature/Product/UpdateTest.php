<?php

use App\Models\Api\Product\Category;
use App\Models\Api\Product\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

pest()->use(RefreshDatabase::class);

test('authenticated user can update a product', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $updatedData = [
        'name' => 'Updated Product',
        'slug' => 'updated-product',
        'short_description' => 'Updated short description.',
        'description' => 'Updated longer description.',
        'price' => 1500,
        'sale_price' => 1299,
        'cost_price' => 1200,
        'stock_quantity' => 50,
        'stock_status' => 'in_stock',
        'category_id' => $category->id,
        'is_active' => false,
        'is_featured' => true,
    ];

    $response = $this->withToken($token)
        ->putJson(route('product.update', $product), $updatedData);

    $response
        ->assertSuccessful()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'Updated Product')
        ->assertJsonPath('data.slug', 'updated-product')
        ->assertJsonPath('data.price', 1500);

    assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Product',
        'slug' => 'updated-product',
        'price' => 1500,
    ]);
});

test('unauthenticated user cannot update a product', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $updatedData = [
        'name' => 'Updated Product',
        'slug' => 'updated-product',
        'price' => 1500,
        'category_id' => $category->id,
    ];

    $this->putJson(route('product.update', $product), $updatedData)
        ->assertUnauthorized();
});

test('updating non-existent product returns 404', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $category = Category::factory()->create();

    $updatedData = [
        'name' => 'Updated Product',
        'slug' => 'updated-product',
        'price' => 1500,
        'category_id' => $category->id,
    ];

    $this->withToken($token)
        ->putJson(route('product.update', 9999), $updatedData)
        ->assertNotFound();
});

test('validation errors when updating product with invalid data', function (array $invalidData, array $expectedErrors) {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    // Create another product for duplicate slug test
    if(isset($invalidData['slug']) && $invalidData['slug'] === 'existing-slug') {
        Product::factory()->create(['slug' => 'existing-slug', 'category_id' => $category->id]);
    }

    $response = $this->withToken($token)
        ->putJson(route('product.update', $product), $invalidData);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors($expectedErrors);
})->with([
            'empty name' => [
                ['name' => '', 'slug' => 'test', 'price' => 100, 'category_id' => 1],
                ['name'],
            ],
            'invalid price' => [
                ['name' => 'Test', 'slug' => 'test', 'price' => 'invalid', 'category_id' => 1],
                ['price'],
            ],
            'duplicate slug' => [
                ['name' => 'Test', 'slug' => 'existing-slug', 'price' => 100, 'category_id' => 1],
                ['slug'],
            ],
            'non-existent category' => [
                ['name' => 'Test', 'slug' => 'test', 'price' => 100, 'category_id' => 999],
                ['category_id'],
            ],
            'negative stock' => [
                ['name' => 'Test', 'slug' => 'test', 'price' => 100, 'category_id' => 1, 'stock_quantity' => -1],
                ['stock_quantity'],
            ],
        ]);

test('authenticated user can partially update a product', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'name' => 'Original Name',
        'price' => 1000,
        'category_id' => $category->id,
    ]);

    $partialData = [
        'name' => 'Partially Updated Name',
    ];

    $response = $this->withToken($token)
        ->putJson(route('product.update', $product), $partialData);

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Partially Updated Name')
        ->assertJsonPath('data.price', 1000); // unchanged

    assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Partially Updated Name',
        'price' => 1000,
    ]);
});

test('updating product with same data succeeds', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'name' => 'Same Name',
        'slug' => 'same-slug',
        'price' => 1000,
        'category_id' => $category->id,
    ]);

    $sameData = [
        'name' => 'Same Name',
        'slug' => 'same-slug',
        'price' => 1000,
        'category_id' => $category->id,
    ];

    $response = $this->withToken($token)
        ->putJson(route('product.update', $product), $sameData);

    $response->assertSuccessful();

    assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Same Name',
        'slug' => 'same-slug',
    ]);
});
