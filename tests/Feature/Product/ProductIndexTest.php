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



test('product index returns empty data when there are no products', function () {
    $response = $this->getJson(route('product.index'));

    $response
        ->assertOk()
        ->assertJsonPath('data.total', 0)
        ->assertJsonCount(0, 'data.data');
});

