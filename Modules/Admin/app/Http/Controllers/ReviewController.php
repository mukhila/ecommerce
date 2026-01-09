<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\ReviewReply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display list of all reviews
     */
    public function index(Request $request)
    {
        try {
            $query = ProductReview::with(['product', 'user', 'reply'])
                                 ->orderBy('created_at', 'desc');

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by rating
            if ($request->has('rating') && $request->rating) {
                $query->byRating($request->rating);
            }

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('review_text', 'like', "%{$search}%")
                      ->orWhereHas('product', function($productQuery) use ($search) {
                          $productQuery->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $reviews = $query->paginate(20);

            // Statistics
            $stats = [
                'total' => ProductReview::count(),
                'pending' => ProductReview::pending()->count(),
                'approved' => ProductReview::approved()->count(),
                'rejected' => ProductReview::where('status', 'rejected')->count(),
                'average_rating' => ProductReview::approved()->avg('rating') ?? 0,
            ];

            return view('admin::reviews.index', compact('reviews', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error loading reviews: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Unable to load reviews');
        }
    }

    /**
     * Display single review details
     */
    public function show($id)
    {
        try {
            $review = ProductReview::with(['product', 'user', 'order', 'images', 'reply.admin'])
                                  ->findOrFail($id);

            return view('admin::reviews.show', compact('review'));

        } catch (\Exception $e) {
            Log::error('Error loading review: ' . $e->getMessage());
            return redirect()->route('admin.reviews.index')->with('error', 'Review not found');
        }
    }

    /**
     * Approve review
     */
    public function approve($id)
    {
        try {
            $review = ProductReview::findOrFail($id);

            DB::beginTransaction();
            $review->approve(Auth::id());
            DB::commit();

            return redirect()->back()->with('success', 'Review approved successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving review: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to approve review');
        }
    }

    /**
     * Reject review
     */
    public function reject($id)
    {
        try {
            $review = ProductReview::findOrFail($id);

            DB::beginTransaction();
            $review->reject();
            DB::commit();

            return redirect()->back()->with('success', 'Review rejected');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting review: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to reject review');
        }
    }

    /**
     * Update review status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,approved,rejected'
            ]);

            $review = ProductReview::findOrFail($id);

            DB::beginTransaction();

            if ($request->status === 'approved') {
                $review->approve(Auth::id());
            } else {
                $review->update(['status' => $request->status]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Review status updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating review status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update review status');
        }
    }

    /**
     * Store or update admin reply
     */
    public function reply(Request $request, $id)
    {
        try {
            $request->validate([
                'reply_text' => 'required|string|min:10|max:1000'
            ]);

            $review = ProductReview::findOrFail($id);

            DB::beginTransaction();

            // Update existing reply or create new one
            ReviewReply::updateOrCreate(
                ['review_id' => $review->id],
                [
                    'admin_id' => Auth::id(),
                    'reply_text' => $request->reply_text,
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', 'Reply posted successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error posting reply: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to post reply');
        }
    }

    /**
     * Delete review (admin only)
     */
    public function destroy($id)
    {
        try {
            $review = ProductReview::findOrFail($id);

            DB::beginTransaction();
            $review->delete();
            DB::commit();

            return redirect()->route('admin.reviews.index')
                           ->with('success', 'Review deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting review: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to delete review');
        }
    }

    /**
     * Bulk action on reviews
     */
    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'action' => 'required|in:approve,reject,delete',
                'review_ids' => 'required|array',
                'review_ids.*' => 'exists:product_reviews,id',
            ]);

            DB::beginTransaction();

            $reviews = ProductReview::whereIn('id', $request->review_ids)->get();

            foreach ($reviews as $review) {
                match($request->action) {
                    'approve' => $review->approve(Auth::id()),
                    'reject' => $review->reject(),
                    'delete' => $review->delete(),
                };
            }

            DB::commit();

            $message = match($request->action) {
                'approve' => 'Reviews approved successfully',
                'reject' => 'Reviews rejected successfully',
                'delete' => 'Reviews deleted successfully',
            };

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to perform bulk action');
        }
    }
}
