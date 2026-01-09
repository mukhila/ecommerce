<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Services\RazorpayService;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;
use Mockery;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $attributes = []): Product
    {
        $category = Category::factory()->create();
        return Product::factory()->create(array_merge([
            'category_id' => $category->id,
            'stock' => 10,
            'is_active' => true,
            'price' => 1000,
            'gst_percentage' => 18,
        ], $attributes));
    }

    private function createCartWithItems(User $user, array $products = null): Cart
    {
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        if ($products === null) {
            $product = $this->createProduct();
            $products = [['product' => $product, 'quantity' => 2]];
        }

        foreach ($products as $item) {
            CartItem::factory()->create([
                'cart_id' => $cart->id,
                'product_id' => $item['product']->id,
                'price' => $item['product']->sale_price ?? $item['product']->price,
                'quantity' => $item['quantity'],
            ]);
        }

        return $cart;
    }

    private function getValidCheckoutData(): array
    {
        return [
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9876543210',
            'address_line1' => '123 Main Street',
            'address_line2' => 'Apt 4',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'postal_code' => '400001',
            'country' => 'India',
            'payment_method' => 'cod',
            'notes' => 'Please handle with care',
        ];
    }

    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_checkout(): void
    {
        $user = User::factory()->create();
        $this->createCartWithItems($user);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertStatus(200);
    }

    public function test_user_with_empty_cart_is_redirected(): void
    {
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
    }

    public function test_user_can_place_cod_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 10]);
        $this->createCartWithItems($user, [['product' => $product, 'quantity' => 2]]);

        $response = $this->actingAs($user)->post(route('checkout.process'), $this->getValidCheckoutData());

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('shipping_addresses', [
            'full_name' => 'John Doe',
            'city' => 'Mumbai',
        ]);

        // Stock should be reduced
        $this->assertEquals(8, $product->fresh()->stock);
    }

    public function test_order_items_are_created_correctly(): void
    {
        $user = User::factory()->create();
        $product1 = $this->createProduct(['price' => 500, 'stock' => 10]);
        $product2 = $this->createProduct(['price' => 300, 'stock' => 10]);

        $this->createCartWithItems($user, [
            ['product' => $product1, 'quantity' => 2],
            ['product' => $product2, 'quantity' => 1],
        ]);

        $this->actingAs($user)->post(route('checkout.process'), $this->getValidCheckoutData());

        $order = Order::where('user_id', $user->id)->first();

        $this->assertCount(2, $order->items);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);
    }

    public function test_cart_is_cleared_after_checkout(): void
    {
        $user = User::factory()->create();
        $this->createCartWithItems($user);

        $this->actingAs($user)->post(route('checkout.process'), $this->getValidCheckoutData());

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_checkout_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $this->createCartWithItems($user);

        $response = $this->actingAs($user)->post(route('checkout.process'), []);

        $response->assertSessionHasErrors([
            'full_name',
            'email',
            'phone',
            'address_line1',
            'city',
            'state',
            'postal_code',
            'country',
            'payment_method',
        ]);
    }

    public function test_checkout_validates_payment_method(): void
    {
        $user = User::factory()->create();
        $this->createCartWithItems($user);

        $data = $this->getValidCheckoutData();
        $data['payment_method'] = 'invalid_method';

        $response = $this->actingAs($user)->post(route('checkout.process'), $data);

        $response->assertSessionHasErrors('payment_method');
    }

    public function test_checkout_fails_if_product_out_of_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 1]);
        $this->createCartWithItems($user, [['product' => $product, 'quantity' => 5]]);

        $response = $this->actingAs($user)->post(route('checkout.process'), $this->getValidCheckoutData());

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    public function test_order_calculates_free_shipping_correctly(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 4000, 'gst_percentage' => 0]); // Above 3000 threshold
        $this->createCartWithItems($user, [['product' => $product, 'quantity' => 1]]);

        $this->actingAs($user)->post(route('checkout.process'), $this->getValidCheckoutData());

        $order = Order::where('user_id', $user->id)->first();
        $this->assertEquals(0, $order->shipping_cost);
    }

    public function test_order_charges_shipping_for_small_orders(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 500, 'gst_percentage' => 0]); // Below 3000 threshold
        $this->createCartWithItems($user, [['product' => $product, 'quantity' => 1]]);

        $this->actingAs($user)->post(route('checkout.process'), $this->getValidCheckoutData());

        $order = Order::where('user_id', $user->id)->first();
        $this->assertEquals(100, $order->shipping_cost);
    }

    public function test_razorpay_checkout_creates_order(): void
    {
        $user = User::factory()->create();
        $this->createCartWithItems($user);

        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('createOrder')
                ->once()
                ->andReturn([
                    'success' => true,
                    'razorpay_order_id' => 'order_test123',
                    'amount' => 100000,
                    'currency' => 'INR',
                ]);
        });

        $data = $this->getValidCheckoutData();
        $data['payment_method'] = 'razorpay';

        $response = $this->actingAs($user)->post(route('checkout.process'), $data);

        // Order should be created
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'razorpay',
        ]);
    }
}
