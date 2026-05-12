<?php

use App\Models\Api\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('user can view paginated products', function () {
    Product::factory()->count(20)->create();

    $response = $this->getJson(route('product.index'));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'current_page',
                'data',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ])
        ->assertJsonCount(15, 'data.data');
});

test('user can request a custom per page size', function () {
    Product::factory()->count(30)->create();

    $response = $this->getJson(route('product.index', ['per_page' => 10]));

    $response
        ->assertOk()
        ->assertJsonPath('data.per_page', 10)
        ->assertJsonCount(10, 'data.data');
});

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

test('product index returns empty data when there are no products', function () {
    $response = $this->getJson(route('product.index'));

    $response
        ->assertOk()
        ->assertJsonPath('data.total', 0)
        ->assertJsonCount(0, 'data.data');
});

