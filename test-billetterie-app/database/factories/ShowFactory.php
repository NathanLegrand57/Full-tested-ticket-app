<?php

namespace Database\Factories;

use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Show>
 */
class ShowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'show_date' => fake()->dateTimeBetween('now', '+1 year'),
            'image' => fake()->imageUrl(),
            'duration' => fake()->numberBetween(60, 180),
            'price' => fake()->randomFloat(2, 10, 100),
            'places_disponibles' => fake()->numberBetween(50, 500),
        ];
    }
}