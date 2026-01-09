<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 100, 5000);

        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(3, true),
            'price' => $price,
            'sale_price' => fake()->optional(0.3)->randomFloat(2, 50, $price - 10),
            'gst_percentage' => fake()->randomElement([5, 12, 18, 28]),
            'fabric_type' => fake()->randomElement(['Cotton', 'Silk', 'Polyester', 'Wool', 'Linen']),
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
            'average_rating' => 0,
            'review_count' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function withStock(int $quantity): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $quantity,
        ]);
    }

    public function onSale(float $salePrice = null): static
    {
        return $this->state(function (array $attributes) use ($salePrice) {
            return [
                'sale_price' => $salePrice ?? ($attributes['price'] * 0.8),
            ];
        });
    }
}
