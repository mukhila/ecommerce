<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $cart->user);
        $this->assertEquals($user->id, $cart->user->id);
    }

    public function test_cart_has_many_items(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $cart = Cart::factory()->create();

        CartItem::factory()->count(3)->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $this->assertCount(3, $cart->items);
    }

    public function test_cart_calculates_subtotal_correctly(): void
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 100,
            'gst_percentage' => 18,
        ]);
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 200,
            'gst_percentage' => 18,
        ]);

        $cart = Cart::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'price' => 100,
            'quantity' => 2,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'price' => 200,
            'quantity' => 1,
        ]);

        $cart->refresh();

        // Subtotal = (100 * 2) + (200 * 1) = 400
        $this->assertEquals(400, $cart->subtotal);
    }

    public function test_cart_calculates_gst_amount_correctly(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1000,
            'gst_percentage' => 18,
        ]);

        $cart = Cart::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'price' => 1000,
            'quantity' => 1,
        ]);

        $cart->refresh();

        // GST = 1000 * 18% = 180
        $this->assertEquals(180, $cart->gst_amount);
    }

    public function test_cart_calculates_total_with_gst(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1000,
            'gst_percentage' => 18,
        ]);

        $cart = Cart::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'price' => 1000,
            'quantity' => 1,
        ]);

        $cart->refresh();

        // Total = 1000 + 180 = 1180
        $this->assertEquals(1180, $cart->total);
    }

    public function test_cart_calculates_gst_breakdown_by_rate(): void
    {
        $category = Category::factory()->create();
        $product18 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1000,
            'gst_percentage' => 18,
        ]);
        $product5 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 500,
            'gst_percentage' => 5,
        ]);

        $cart = Cart::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product18->id,
            'price' => 1000,
            'quantity' => 1,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product5->id,
            'price' => 500,
            'quantity' => 2,
        ]);

        $cart->refresh();

        $breakdown = $cart->gst_breakdown;

        // Keys are decimal strings due to casting (e.g., "18.00", "5.00")
        $this->assertCount(2, $breakdown);

        // Find the 18% GST breakdown
        $gst18 = collect($breakdown)->first(fn($item) => $item['rate'] == 18);
        $gst5 = collect($breakdown)->first(fn($item) => $item['rate'] == 5);

        $this->assertNotNull($gst18);
        $this->assertNotNull($gst5);
        $this->assertEquals(1000, $gst18['taxable_amount']);
        $this->assertEquals(180, $gst18['gst_amount']);
        $this->assertEquals(1000, $gst5['taxable_amount']);
        $this->assertEquals(50, $gst5['gst_amount']);
    }

    public function test_cart_item_count_returns_total_quantity(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $cart = Cart::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $cart->refresh();

        $this->assertEquals(5, $cart->item_count);
    }

    public function test_guest_cart_uses_session_id(): void
    {
        $sessionId = 'test-session-123';
        $cart = Cart::factory()->create([
            'user_id' => null,
            'session_id' => $sessionId,
        ]);

        $this->assertNull($cart->user_id);
        $this->assertEquals($sessionId, $cart->session_id);
    }

    public function test_empty_cart_has_zero_totals(): void
    {
        $cart = Cart::factory()->create();

        $this->assertEquals(0, $cart->subtotal);
        $this->assertEquals(0, $cart->gst_amount);
        $this->assertEquals(0, $cart->total);
        $this->assertEquals(0, $cart->item_count);
    }
}
