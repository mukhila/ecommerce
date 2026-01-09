<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\ShippingAddress;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_generates_order_number_on_creation(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'order_number' => null]);

        $this->assertNotNull($order->order_number);
        $this->assertStringStartsWith('ORD-', $order->order_number);
    }

    public function test_order_sets_payment_expiration_on_creation(): void
    {
        $order = Order::factory()->create(['payment_expires_at' => null]);

        $this->assertNotNull($order->payment_expires_at);
        $this->assertTrue($order->payment_expires_at->isFuture());
    }

    public function test_order_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    public function test_order_has_many_items(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $order = Order::factory()->create();

        OrderItem::factory()->count(3)->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $this->assertCount(3, $order->items);
    }

    public function test_order_has_one_shipping_address(): void
    {
        $order = Order::factory()->create();
        ShippingAddress::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(ShippingAddress::class, $order->shippingAddress);
    }

    public function test_order_can_be_cancelled(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 5,
        ]);

        $order = Order::factory()->create(['status' => 'pending']);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $result = $order->cancelOrder('Customer requested');

        $this->assertTrue($result);
        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertEquals('Customer requested', $order->fresh()->cancellation_reason);
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_cannot_cancel_already_cancelled_order(): void
    {
        $order = Order::factory()->cancelled()->create();

        $result = $order->cancelOrder('Test reason');

        $this->assertFalse($result);
    }

    public function test_order_cancellation_restores_stock(): void
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
        ]);
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 20,
        ]);

        $order = Order::factory()->create(['status' => 'processing']);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 3,
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 5,
        ]);

        $order->cancelOrder();

        $this->assertEquals(13, $product1->fresh()->stock);
        $this->assertEquals(25, $product2->fresh()->stock);
    }

    public function test_scope_expired_unpaid_returns_correct_orders(): void
    {
        // Create an expired unpaid order
        $expiredUnpaid = Order::factory()->create([
            'payment_status' => 'pending',
            'status' => 'pending',
            'payment_expires_at' => now()->subDay(),
        ]);

        // Create a paid order (should not be returned)
        Order::factory()->create([
            'payment_status' => 'paid',
            'status' => 'processing',
            'payment_expires_at' => now()->subDay(),
        ]);

        // Create a non-expired unpaid order (should not be returned)
        Order::factory()->create([
            'payment_status' => 'pending',
            'status' => 'pending',
            'payment_expires_at' => now()->addDay(),
        ]);

        // Create a delivered order (should not be returned even if expired)
        Order::factory()->create([
            'payment_status' => 'pending',
            'status' => 'delivered',
            'payment_expires_at' => now()->subDay(),
        ]);

        $expiredOrders = Order::expiredUnpaid()->get();

        $this->assertCount(1, $expiredOrders);
        $this->assertEquals($expiredUnpaid->id, $expiredOrders->first()->id);
    }

    public function test_order_casts_gst_breakdown_to_array(): void
    {
        $order = Order::factory()->create([
            'gst_breakdown' => [
                18 => ['rate' => 18, 'taxable_amount' => 1000, 'gst_amount' => 180],
            ],
        ]);

        $this->assertIsArray($order->gst_breakdown);
        $this->assertArrayHasKey(18, $order->gst_breakdown);
    }

    public function test_order_casts_decimal_fields_correctly(): void
    {
        $order = Order::factory()->create([
            'subtotal' => 1000.50,
            'gst_amount' => 180.09,
            'total' => 1280.59,
        ]);

        $this->assertEquals('1000.50', $order->subtotal);
        $this->assertEquals('180.09', $order->gst_amount);
        $this->assertEquals('1280.59', $order->total);
    }
}
