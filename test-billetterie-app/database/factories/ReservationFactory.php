<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Show;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'show_id' => Show::factory(),
            'quantity' => fake()->numberBetween(1, 4),
            'amount' => fake()->randomFloat(2, 20, 200),
            'status' => fake()->randomElement(['confirmed', 'pending', 'cancelled']),
            'payment_id' => fake()->uuid(),
        ];
    }
}
