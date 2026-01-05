<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewHelpfulVote extends Model
{
    protected $fillable = [
        'review_id',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        // Increment helpful count when vote is created
        static::created(function ($vote) {
            $vote->review->increment('helpful_count');
        });

        // Decrement helpful count when vote is deleted
        static::deleted(function ($vote) {
            $vote->review->decrement('helpful_count');
        });
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'review_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
