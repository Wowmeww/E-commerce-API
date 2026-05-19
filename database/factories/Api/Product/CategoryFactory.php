<?php

namespace Database\Factories\Api\Product;

use App\Models\Api\Product\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
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
            'description' => fake()->realText(),
        ];
    }
}
