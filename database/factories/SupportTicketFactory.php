<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketFactory extends Factory
{
    protected $model = SupportTicket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'email' => fake()->email(),
            'order_id' => null,
            'category' => fake()->randomElement(['General', 'Order Issue', 'Payment', 'Product', 'Returns', 'Shipping', 'Other']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => 'open',
            'subject' => fake()->sentence(5),
            'message' => fake()->paragraphs(2, true),
            'attachment' => null,
            'assigned_to' => null,
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }

    public function forGuest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    public function withOrderId(string $orderId): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $orderId,
            'category' => 'Order Issue',
        ]);
    }
}
