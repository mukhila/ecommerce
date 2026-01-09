<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 500, 10000);
        $gstAmount = $subtotal * 0.18;
        $shippingCost = $subtotal >= 3000 ? 0 : 100;
        $total = $subtotal + $gstAmount + $shippingCost;

        return [
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => User::factory(),
            'guest_email' => null,
            'guest_name' => null,
            'guest_phone' => null,
            'subtotal' => $subtotal,
            'gst_amount' => $gstAmount,
            'gst_breakdown' => [
                18 => [
                    'rate' => 18,
                    'taxable_amount' => $subtotal,
                    'gst_amount' => $gstAmount,
                ]
            ],
            'tax' => $gstAmount,
            'shipping_cost' => $shippingCost,
            'discount' => 0,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'razorpay',
            'payment_expires_at' => now()->addDays(7),
            'razorpay_order_id' => null,
            'razorpay_payment_id' => null,
            'razorpay_signature' => null,
            'notes' => null,
            'cancellation_reason' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
            'razorpay_payment_id' => 'pay_' . fake()->regexify('[A-Za-z0-9]{14}'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancellation_reason' => 'Customer requested cancellation',
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'payment_status' => 'paid',
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);
    }

    public function shipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'shipped',
            'payment_status' => 'paid',
            'tracking_number' => fake()->regexify('[A-Z]{2}[0-9]{9}[A-Z]{2}'),
            'courier_name' => fake()->randomElement(['DHL', 'FedEx', 'BlueDart', 'DTDC']),
        ]);
    }

    public function cod(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cod',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_expires_at' => now()->subDay(),
            'payment_status' => 'pending',
        ]);
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'guest_email' => fake()->email(),
            'guest_name' => fake()->name(),
            'guest_phone' => fake()->phoneNumber(),
        ]);
    }
}
