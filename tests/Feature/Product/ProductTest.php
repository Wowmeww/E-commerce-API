<?php

use App\Models\Api\Product\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('user can view products', function () {

    Product::factory(20)->create();
    $response = $this->get(route('product.index'));

    $response
        ->assertJsonStructure([
            'success',
            'message',
            'data'
        ])
        ->assertOk();
});

test('user can view a product', function () {

    $product = Product::factory()->create();

    $response = $this->get(route('product.show', $product));

    $response
        ->assertJsonStructure([
            'success',
            'message',
            'data'
        ])
        ->assertOk();
});

