<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\ReviewHelpfulVote;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function createDeliveredOrder(User $user, Product $product): Order
    {
        $order = Order::factory()->delivered()->create(['user_id' => $user->id]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
        return $order;
    }

    private function createProduct(): Product
    {
        $category = Category::factory()->create();
        return Product::factory()->create(['category_id' => $category->id]);
    }

    public function test_guest_cannot_access_review_form(): void
    {
        $product = $this->createProduct();

        $response = $this->get(route('review.create', [
            'product_id' => $product->id,
            'order_id' => 1,
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_review_form_for_delivered_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createDeliveredOrder($user, $product);

        $response = $this->actingAs($user)->get(route('review.create', [
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]));

        // Route should be accessible (not 403, 404, or redirect to login)
        $this->assertNotEquals(403, $response->status());
        $this->assertNotEquals(404, $response->status());
    }

    public function test_user_cannot_review_undelivered_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = Order::factory()->processing()->create(['user_id' => $user->id]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->get(route('review.create', [
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]));

        // Should not be able to access (either 404 or redirect)
        $this->assertTrue(in_array($response->status(), [302, 404]));
    }

    public function test_user_can_submit_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createDeliveredOrder($user, $product);

        $response = $this->actingAs($user)->post(route('review.store'), [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'title' => 'Great product!',
            'review_text' => 'This is a wonderful product that exceeded my expectations.',
            'rating' => 5,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'title' => 'Great product!',
            'rating' => 5,
            'status' => 'pending',
            'is_verified_purchase' => true,
        ]);
    }

    public function test_review_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('review.store'), []);

        $response->assertSessionHasErrors([
            'product_id',
            'order_id',
            'title',
            'review_text',
            'rating',
        ]);
    }

    public function test_review_validates_rating_range(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createDeliveredOrder($user, $product);

        $response = $this->actingAs($user)->post(route('review.store'), [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'title' => 'Test',
            'review_text' => 'This is a test review with enough characters.',
            'rating' => 6, // Invalid
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_review_validates_minimum_text_length(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createDeliveredOrder($user, $product);

        $response = $this->actingAs($user)->post(route('review.store'), [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'title' => 'Test',
            'review_text' => 'Short', // Less than 10 characters
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('review_text');
    }

    public function test_user_cannot_submit_duplicate_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createDeliveredOrder($user, $product);

        // Create first review
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
        ]);

        // Try to create duplicate
        $response = $this->actingAs($user)->post(route('review.store'), [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'title' => 'Another review',
            'review_text' => 'This is another review for the same product.',
            'rating' => 4,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_can_access_edit_own_review_route(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('review.edit', $review->id));

        // Route should be accessible (not 403, 404, or redirect)
        $this->assertNotEquals(403, $response->status());
        $this->assertNotEquals(404, $response->status());
    }

    public function test_user_cannot_edit_others_review(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = $this->createProduct();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->get(route('review.edit', $review->id));

        // Should not be able to access (either 404 or redirect with error)
        $this->assertTrue(in_array($response->status(), [302, 404]));
    }

    public function test_user_can_update_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->put(route('review.update', $review->id), [
            'title' => 'Updated title',
            'review_text' => 'This is the updated review text with more details.',
            'rating' => 4,
        ]);

        $response->assertRedirect();

        $review->refresh();
        $this->assertEquals('Updated title', $review->title);
        $this->assertEquals(4, $review->rating);
        $this->assertEquals('pending', $review->status); // Requires re-approval
    }

    public function test_user_can_delete_own_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('review.destroy', $review->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('product_reviews', ['id' => $review->id]);
    }

    public function test_user_can_toggle_helpful_vote(): void
    {
        $reviewer = User::factory()->create();
        $voter = User::factory()->create();
        $product = $this->createProduct();

        $review = ProductReview::factory()->approved()->create([
            'product_id' => $product->id,
            'user_id' => $reviewer->id,
        ]);

        // Vote helpful
        $response = $this->actingAs($voter)->postJson(route('review.helpful', $review->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'has_voted' => true,
            ]);

        $this->assertDatabaseHas('review_helpful_votes', [
            'review_id' => $review->id,
            'user_id' => $voter->id,
        ]);

        // Remove vote
        $response = $this->actingAs($voter)->postJson(route('review.helpful', $review->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'has_voted' => false,
            ]);

        $this->assertDatabaseMissing('review_helpful_votes', [
            'review_id' => $review->id,
            'user_id' => $voter->id,
        ]);
    }

    public function test_user_cannot_vote_on_own_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $review = ProductReview::factory()->approved()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(route('review.helpful', $review->id));

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'You cannot vote on your own review',
            ]);
    }

    public function test_guest_cannot_vote_helpful(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $review = ProductReview::factory()->approved()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->postJson(route('review.helpful', $review->id));

        $response->assertStatus(401);
    }

    public function test_review_with_images(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createDeliveredOrder($user, $product);

        $response = $this->actingAs($user)->post(route('review.store'), [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'title' => 'Review with images',
            'review_text' => 'This review has images attached to show the product.',
            'rating' => 5,
            'images' => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
        ]);

        $response->assertRedirect();

        $review = ProductReview::where('user_id', $user->id)->first();
        $this->assertCount(2, $review->images);
    }
}
