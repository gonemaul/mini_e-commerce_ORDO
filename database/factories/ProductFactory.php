<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        return [
            'category_id' => rand(1,100),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000), // Harga antara 10 sampai 1000 dengan 2 digit desimal
            'stock' => $this->faker->numberBetween(0, 100), // Stok antara 0 sampai 100
            'sold' => $this->faker->numberBetween(0, 100),
        ];
    }
}