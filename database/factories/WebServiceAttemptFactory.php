<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebServiceAttempt>
 */
class WebServiceAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'attempt_number' => fake()->numberBetween(1, 5),
            'success' => fake()->boolean(),
            'http_status_code' => fake()->randomElement([200, 201, 400, 500]),
            'response_message' => fake()->optional()->sentence(),
            'error_message' => fake()->optional()->sentence(),
            'attempted_at' => now(),
        ];
    }
}
