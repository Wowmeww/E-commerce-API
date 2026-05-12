<?php

use App\Models\Api\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('user can view an individual product', function () {

    $product = Product::factory()->create();

    $response = $this->getJson(route('product.show', $product));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'slug',
                'short_description',
                'description',
                'price',
                'sale_price',
                'cost_price',
                'stock_quantity',
                'category_id',
                'created_at',
                'updated_at',
            ],
        ])
        ->assertJsonPath('data.id', $product->id)
        ->assertJsonPath('data.name', $product->name)
        ->assertJsonPath('data.slug', $product->slug)
        ->assertJsonPath('data.price', $product->price);
});

test('product show returns 404 for missing product', function () {

    $this->getJson(route('product.show', 1))
        ->assertNotFound();
});
