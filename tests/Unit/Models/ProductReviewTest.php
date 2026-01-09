<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProductReview;
use App\Models\ReviewHelpfulVote;
use App\Models\User;
use App\Models\Order;
use Modules\Product\Models\Product;
use Modules\Product\Models\Category;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(): Product
    {
        $category = Category::factory()->create();
        return Product::factory()->create(['category_id' => $category->id]);
    }

    public function test_review_belongs_to_product(): void
    {
        $product = $this->createProduct();
        $review = ProductReview::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $review->product);
        $this->assertEquals($product->id, $review->product->id);
    }

    public function test_review_belongs_to_user(): void
    {
        $product = $this->createProduct();
        $user = User::factory()->create();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals($user->id, $review->user->id);
    }

    public function test_review_belongs_to_order(): void
    {
        $product = $this->createProduct();
        $order = Order::factory()->create();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);

        $this->assertInstanceOf(Order::class, $review->order);
        $this->assertEquals($order->id, $review->order->id);
    }

    public function test_scope_approved_returns_only_approved_reviews(): void
    {
        $product = $this->createProduct();

        ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'approved',
        ]);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'pending',
        ]);
        ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'rejected',
        ]);

        $approvedReviews = ProductReview::approved()->get();

        $this->assertCount(1, $approvedReviews);
        $this->assertEquals('approved', $approvedReviews->first()->status);
    }

    public function test_scope_pending_returns_only_pending_reviews(): void
    {
        $product = $this->createProduct();

        ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'approved',
        ]);
        ProductReview::factory()->count(2)->create([
            'product_id' => $product->id,
            'status' => 'pending',
        ]);

        $pendingReviews = ProductReview::pending()->get();

        $this->assertCount(2, $pendingReviews);
    }

    public function test_scope_for_product_returns_reviews_for_specific_product(): void
    {
        $product1 = $this->createProduct();
        $product2 = $this->createProduct();

        ProductReview::factory()->count(3)->create(['product_id' => $product1->id]);
        ProductReview::factory()->count(2)->create(['product_id' => $product2->id]);

        $product1Reviews = ProductReview::forProduct($product1->id)->get();

        $this->assertCount(3, $product1Reviews);
    }

    public function test_scope_by_rating_returns_reviews_in_rating_range(): void
    {
        $product = $this->createProduct();

        ProductReview::factory()->create(['product_id' => $product->id, 'rating' => 5]);
        ProductReview::factory()->create(['product_id' => $product->id, 'rating' => 4.5]);
        ProductReview::factory()->create(['product_id' => $product->id, 'rating' => 4]);
        ProductReview::factory()->create(['product_id' => $product->id, 'rating' => 3]);

        $fiveStarReviews = ProductReview::byRating(5)->get();
        $fourStarReviews = ProductReview::byRating(4)->get();

        $this->assertCount(1, $fiveStarReviews);
        $this->assertCount(2, $fourStarReviews);
    }

    public function test_approve_method_updates_status(): void
    {
        $product = $this->createProduct();
        $admin = User::factory()->create();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'pending',
        ]);

        $review->approve($admin->id);

        $this->assertEquals('approved', $review->fresh()->status);
        $this->assertNotNull($review->fresh()->approved_at);
        $this->assertEquals($admin->id, $review->fresh()->approved_by);
    }

    public function test_reject_method_updates_status(): void
    {
        $product = $this->createProduct();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'pending',
        ]);

        $review->reject();

        $this->assertEquals('rejected', $review->fresh()->status);
    }

    public function test_has_been_helpful_by_returns_correct_result(): void
    {
        $product = $this->createProduct();
        $user = User::factory()->create();
        $voter = User::factory()->create();

        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $this->assertFalse($review->hasBeenHelpfulBy($voter->id));

        ReviewHelpfulVote::create([
            'review_id' => $review->id,
            'user_id' => $voter->id,
        ]);

        $this->assertTrue($review->hasBeenHelpfulBy($voter->id));
    }

    public function test_status_badge_attribute_returns_correct_values(): void
    {
        $product = $this->createProduct();

        $pending = ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'pending',
        ]);
        $approved = ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'approved',
        ]);
        $rejected = ProductReview::factory()->create([
            'product_id' => $product->id,
            'status' => 'rejected',
        ]);

        $this->assertEquals('warning', $pending->status_badge);
        $this->assertEquals('success', $approved->status_badge);
        $this->assertEquals('danger', $rejected->status_badge);
    }

    public function test_formatted_rating_attribute(): void
    {
        $product = $this->createProduct();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'rating' => 4.5,
        ]);

        $this->assertEquals('4.5', $review->formatted_rating);
    }

    public function test_star_rating_attribute_rounds_correctly(): void
    {
        $product = $this->createProduct();

        $review1 = ProductReview::factory()->create([
            'product_id' => $product->id,
            'rating' => 4.4,
        ]);
        $review2 = ProductReview::factory()->create([
            'product_id' => $product->id,
            'rating' => 4.6,
        ]);

        $this->assertEquals(4, $review1->star_rating);
        $this->assertEquals(5, $review2->star_rating);
    }

    public function test_review_casts_verified_purchase_to_boolean(): void
    {
        $product = $this->createProduct();
        $review = ProductReview::factory()->create([
            'product_id' => $product->id,
            'is_verified_purchase' => true,
        ]);

        $this->assertIsBool($review->is_verified_purchase);
        $this->assertTrue($review->is_verified_purchase);
    }
}
