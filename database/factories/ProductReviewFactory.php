<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Order;
use Modules\Product\Models\Product;

class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'title' => fake()->sentence(4),
            'review_text' => fake()->paragraphs(2, true),
            'rating' => fake()->numberBetween(1, 5),
            'status' => 'pending',
            'is_verified_purchase' => true,
            'helpful_count' => 0,
            'approved_at' => null,
            'approved_by' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    public function withRating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating,
        ]);
    }

    public function withHelpfulVotes(int $count): static
    {
        return $this->state(fn (array $attributes) => [
            'helpful_count' => $count,
        ]);
    }
}
