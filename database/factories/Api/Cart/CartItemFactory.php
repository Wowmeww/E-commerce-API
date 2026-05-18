<?php

namespace Database\Factories\Api\Cart;

use App\Models\Api\Cart\Cart;
use App\Models\Api\Cart\CartItem;
use App\Models\Api\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);

        return [
            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'price' => 1000,
            'total_price' => 1000 * $quantity,
            'product_name' => fake()->word(),
        ];
    }
}
