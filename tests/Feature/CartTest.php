<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $attributes = []): Product
    {
        $category = Category::factory()->create();
        return Product::factory()->create(array_merge([
            'category_id' => $category->id,
            'stock' => 10,
            'is_active' => true,
        ], $attributes));
    }

    public function test_guest_can_view_cart_page(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_view_cart_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('cart.index'));

        $response->assertStatus(200);
    }

    public function test_guest_can_add_product_to_cart(): void
    {
        $product = $this->createProduct(['price' => 500]);

        $response = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product added to cart successfully',
            ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_authenticated_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['price' => 500]);

        $response = $this->actingAs($user)->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id]);
    }

    public function test_cannot_add_inactive_product_to_cart(): void
    {
        $product = $this->createProduct(['is_active' => false]);

        $response = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'This product is no longer available',
            ]);
    }

    public function test_cannot_add_out_of_stock_product_to_cart(): void
    {
        $product = $this->createProduct(['stock' => 0]);

        $response = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'This product is currently out of stock',
            ]);
    }

    public function test_cannot_add_more_than_available_stock(): void
    {
        $product = $this->createProduct(['stock' => 5]);

        $response = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Only 5 items available in stock',
            ]);
    }

    public function test_adding_same_product_increases_quantity(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 20]);

        // Create cart with existing item
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
            'attributes' => null,
        ]);

        // Add same product again via controller
        $response = $this->actingAs($user)->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(200);

        $cart->refresh();

        // Get total quantity for this product
        $totalQuantity = $cart->items()->where('product_id', $product->id)->sum('quantity');
        $this->assertEquals(5, $totalQuantity);
    }

    public function test_can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 20]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);

        $response = $this->actingAs($user)->patchJson(route('cart.update', $cartItem->id), [
            'quantity' => 5,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(5, $cartItem->fresh()->quantity);
    }

    public function test_cannot_update_cart_item_beyond_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 5]);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);

        $response = $this->actingAs($user)->patchJson(route('cart.update', $cartItem->id), [
            'quantity' => 10,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Only 5 items available in stock',
            ]);
    }

    public function test_can_remove_item_from_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->deleteJson(route('cart.remove', $cartItem->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Item removed from cart',
            ]);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_can_clear_entire_cart(): void
    {
        $user = User::factory()->create();
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
        ]);

        $response = $this->actingAs($user)->deleteJson(route('cart.clear'));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cart cleared successfully',
            ]);

        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_clearing_empty_cart_returns_error(): void
    {
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson(route('cart.clear'));

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Cart is already empty',
            ]);
    }

    public function test_can_get_cart_count(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $cart = Cart::factory()->create(['user_id' => $user->id]);
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

        $response = $this->actingAs($user)->getJson(route('cart.count'));

        $response->assertStatus(200)
            ->assertJsonStructure(['count', 'total']);

        $this->assertEquals(5, $response->json('count'));
    }

    public function test_add_to_cart_validates_product_id(): void
    {
        $response = $this->postJson(route('cart.add'), [
            'product_id' => 99999,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
    }

    public function test_add_to_cart_validates_quantity(): void
    {
        $product = $this->createProduct();

        $response = $this->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => -1,
        ]);

        $response->assertStatus(422);
    }

    public function test_cart_uses_sale_price_when_available(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct([
            'price' => 1000,
            'sale_price' => 800,
        ]);

        $this->actingAs($user)->postJson(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cart = Cart::where('user_id', $user->id)->first();
        $cartItem = $cart->items()->first();

        $this->assertEquals(800, $cartItem->price);
    }
}
