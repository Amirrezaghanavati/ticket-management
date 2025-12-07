<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
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
            'title' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'file_url' => 'attachments/' . fake()->uuid() . '.pdf',
            'status' => TicketStatus::SUBMITTED,
        ];
    }

    /**
     * Indicate that the ticket is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TicketStatus::DRAFT,
        ]);
    }

    /**
     * Indicate that the ticket is submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TicketStatus::SUBMITTED,
        ]);
    }

    /**
     * Indicate that the ticket is approved by admin 1.
     */
    public function approvedByAdmin1(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TicketStatus::APPROVED_BY_ADMIN1,
        ]);
    }

    /**
     * Indicate that the ticket is approved by admin 2.
     */
    public function approvedByAdmin2(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TicketStatus::APPROVED_BY_ADMIN2,
        ]);
    }

    /**
     * Indicate that the ticket is rejected by admin 1.
     */
    public function rejectedByAdmin1(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TicketStatus::REJECTED_BY_ADMIN1,
        ]);
    }

    /**
     * Indicate that the ticket is rejected by admin 2.
     */
    public function rejectedByAdmin2(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TicketStatus::REJECTED_BY_ADMIN2,
        ]);
    }

    /**
     * Indicate that the ticket is sent to web service.
     */
    public function sentToWebService(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => TicketStatus::SENT_TO_WEBSERVICE,
        ]);
    }
}
