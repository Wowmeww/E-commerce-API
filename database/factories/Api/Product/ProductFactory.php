<?php

namespace Database\Factories\Api\Product;

use App\Models\Api\Product\Category;
use App\Models\Api\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->realText(30);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'short_description' => fake()->realText(60),
            'description' => fake()->realText(),
            'price' => 1000,
            'sale_price' => 899,
            'cost_price' => 800,
            'stock_quantity' => 100,
            'category_id' => Category::factory(),
        ];
    }
}
