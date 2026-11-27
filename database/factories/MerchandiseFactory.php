<?php

namespace Database\Factories;

use App\Models\Merchandise;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends Factory<Merchandise>
 */
class MerchandiseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => 'merc_' . Str::ulid(),
            'name' => fake()->word(),
            'price' => fake()->numberBetween(50, 1000),
            'stock_quantity' => fake()->numberBetween(1, 100),
        ];
    }
}
