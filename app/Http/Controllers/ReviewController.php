<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\ReviewImage;
use App\Models\ReviewHelpfulVote;
use Modules\Product\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class ReviewController extends Controller
{
    /**
     * Show create review form
     */
    public function create(Request $request)
    {
        try {
            $productId = $request->product_id;
            $orderId = $request->order_id;

            // Verify product exists
            $product = Product::findOrFail($productId);

            // Verify order exists and belongs to user
            $order = Order::where('id', $orderId)
                         ->where('user_id', Auth::id())
                         ->where('status', 'delivered')
                         ->firstOrFail();

            // Verify product is in order
            $orderItem = $order->items()->where('product_id', $productId)->first();
            if (!$orderItem) {
                return redirect()->back()->with('error', 'Product not found in this order');
            }

            // Check if already reviewed
            $existingReview = ProductReview::where('product_id', $productId)
                                          ->where('user_id', Auth::id())
                                          ->where('order_id', $orderId)
                                          ->first();

            if ($existingReview) {
                return redirect()->route('review.edit', $existingReview->id)
                                ->with('info', 'You have already reviewed this product. You can edit your review.');
            }

            return view('reviews.create', compact('product', 'order'));

        } catch (Exception $e) {
            Log::error('Error loading review form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load review form');
        }
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'order_id' => 'required|exists:orders,id',
                'title' => 'required|string|max:100',
                'review_text' => 'required|string|min:10|max:2000',
                'rating' => 'required|numeric|min:1|max:5',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            DB::beginTransaction();

            // Verify purchase eligibility
            $order = Order::where('id', $validated['order_id'])
                         ->where('user_id', Auth::id())
                         ->where('status', 'delivered')
                         ->firstOrFail();

            // Verify product in order
            $orderItem = $order->items()->where('product_id', $validated['product_id'])->firstOrFail();

            // Check for existing review
            $existingReview = ProductReview::where('product_id', $validated['product_id'])
                                          ->where('user_id', Auth::id())
                                          ->where('order_id', $validated['order_id'])
                                          ->first();

            if ($existingReview) {
                DB::rollBack();
                return redirect()->back()->with('error', 'You have already reviewed this product');
            }

            // Create review
            $review = ProductReview::create([
                'product_id' => $validated['product_id'],
                'user_id' => Auth::id(),
                'order_id' => $validated['order_id'],
                'title' => $validated['title'],
                'review_text' => $validated['review_text'],
                'rating' => $validated['rating'],
                'status' => 'pending',
                'is_verified_purchase' => true,
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                $images = array_slice($request->file('images'), 0, 5); // Max 5 images
                foreach ($images as $index => $image) {
                    $path = $image->store('reviews', 'public');
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('product.show', $review->product->slug)
                           ->with('success', 'Thank you for your review! It will be published after admin approval.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating review: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Unable to submit review. Please try again.');
        }
    }

    /**
     * Show edit form for user's own review
     */
    public function edit($id)
    {
        try {
            $review = ProductReview::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->with(['product', 'images'])
                                  ->firstOrFail();

            return view('reviews.edit', compact('review'));

        } catch (Exception $e) {
            Log::error('Error loading review edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Review not found');
        }
    }

    /**
     * Update the user's review
     */
    public function update(Request $request, $id)
    {
        try {
            $review = ProductReview::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

            $validated = $request->validate([
                'title' => 'required|string|max:100',
                'review_text' => 'required|string|min:10|max:2000',
                'rating' => 'required|numeric|min:1|max:5',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'remove_images' => 'nullable|array',
                'remove_images.*' => 'exists:review_images,id',
            ]);

            DB::beginTransaction();

            // Update review (requires re-approval)
            $review->update([
                'title' => $validated['title'],
                'review_text' => $validated['review_text'],
                'rating' => $validated['rating'],
                'status' => 'pending',
            ]);

            // Remove selected images
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageId) {
                    $image = ReviewImage::where('id', $imageId)
                                       ->where('review_id', $review->id)
                                       ->first();
                    if ($image) {
                        if (Storage::disk('public')->exists($image->image_path)) {
                            Storage::disk('public')->delete($image->image_path);
                        }
                        $image->delete();
                    }
                }
            }

            // Add new images
            if ($request->hasFile('images')) {
                $currentImageCount = $review->images()->count();
                $maxNewImages = max(0, 5 - $currentImageCount);
                $newImages = array_slice($request->file('images'), 0, $maxNewImages);

                $nextSortOrder = $review->images()->max('sort_order') + 1;
                foreach ($newImages as $image) {
                    $path = $image->store('reviews', 'public');
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'image_path' => $path,
                        'sort_order' => $nextSortOrder++,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('product.show', $review->product->slug)
                           ->with('success', 'Review updated successfully! It will be re-reviewed by admin.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating review: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update review');
        }
    }

    /**
     * Delete user's own review
     */
    public function destroy($id)
    {
        try {
            $review = ProductReview::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

            DB::beginTransaction();
            $productSlug = $review->product->slug;
            $review->delete();
            DB::commit();

            return redirect()->route('product.show', $productSlug)
                           ->with('success', 'Review deleted successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting review: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to delete review');
        }
    }

    /**
     * Toggle helpful vote on review (AJAX)
     */
    public function toggleHelpful(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to vote'
                ], 401);
            }

            $review = ProductReview::approved()->findOrFail($id);

            // Check if user is review author
            if ($review->user_id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot vote on your own review'
                ], 400);
            }

            DB::beginTransaction();

            $vote = ReviewHelpfulVote::where('review_id', $review->id)
                                    ->where('user_id', Auth::id())
                                    ->first();

            if ($vote) {
                // Remove vote
                $vote->delete();
                $hasVoted = false;
                $message = 'Vote removed';
            } else {
                // Add vote
                ReviewHelpfulVote::create([
                    'review_id' => $review->id,
                    'user_id' => Auth::id(),
                ]);
                $hasVoted = true;
                $message = 'Marked as helpful';
            }

            $review->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'has_voted' => $hasVoted,
                'helpful_count' => $review->helpful_count,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error toggling helpful vote: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to process vote'
            ], 500);
        }
    }
}
