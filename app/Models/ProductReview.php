<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'title',
        'review_text',
        'rating',
        'status',
        'is_verified_purchase',
        'helpful_count',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_verified_purchase' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected $with = ['user'];

    /**
     * Boot method to handle cascade effects
     */
    protected static function boot()
    {
        parent::boot();

        // When review is approved/rejected, update product stats
        static::updated(function ($review) {
            if ($review->isDirty('status')) {
                $review->product->updateReviewStats();
            }
        });

        // When review is deleted, cleanup images and update product stats
        static::deleting(function ($review) {
            // Delete images from storage
            foreach ($review->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            // Update product stats
            $review->product->updateReviewStats();
        });
    }

    /**
     * Relationships
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReviewImage::class, 'review_id');
    }

    public function helpfulVotes(): HasMany
    {
        return $this->hasMany(ReviewHelpfulVote::class, 'review_id');
    }

    public function reply(): HasOne
    {
        return $this->hasOne(ReviewReply::class, 'review_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Computed Attributes
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    public function getFormattedRatingAttribute(): string
    {
        return number_format($this->rating, 1);
    }

    public function getStarRatingAttribute(): int
    {
        return (int) round($this->rating);
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating)
                     ->where('rating', '<', $rating + 1);
    }

    /**
     * Helper Methods
     */
    public function hasBeenHelpfulBy($userId): bool
    {
        return $this->helpfulVotes()->where('user_id', $userId)->exists();
    }

    public function approve($adminId = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $adminId ?? auth()->id(),
        ]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }
}
