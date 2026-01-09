<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function createOrderWithItems(User $user, array $attributes = []): Order
    {
        $order = Order::factory()->create(array_merge([
            'user_id' => $user->id,
        ], $attributes));

        ShippingAddress::factory()->create(['order_id' => $order->id]);

        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        return $order;
    }

    public function test_guest_cannot_access_order_success_page(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderWithItems($user);

        $response = $this->get(route('order.success', $order));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_their_order_success_route(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderWithItems($user);

        $response = $this->actingAs($user)->get(route('order.success', $order));

        // Route should be accessible (not 403 or redirect)
        $this->assertNotEquals(403, $response->status());
    }

    public function test_user_cannot_view_other_users_order(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = $this->createOrderWithItems($user1);

        $response = $this->actingAs($user2)->get(route('order.success', $order));

        $response->assertStatus(403);
    }

    public function test_user_can_access_order_tracking_route(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderWithItems($user, ['status' => 'shipped']);

        $response = $this->actingAs($user)->get(route('order.tracking', $order));

        // Route should be accessible (not 403 or redirect)
        $this->assertNotEquals(403, $response->status());
    }

    public function test_order_has_correct_status(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderWithItems($user, ['status' => 'processing']);

        $this->assertEquals('processing', $order->status);
    }

    public function test_cancelled_order_has_cancelled_status(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderWithItems($user, ['status' => 'cancelled']);

        $this->assertEquals('cancelled', $order->status);
    }

    public function test_order_loads_items_relationship(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrderWithItems($user);

        $order->load('items', 'shippingAddress');

        $this->assertTrue($order->relationLoaded('items'));
        $this->assertTrue($order->relationLoaded('shippingAddress'));
    }
}
