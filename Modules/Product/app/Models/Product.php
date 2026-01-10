<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'gst_percentage',
        'fabric_type',
        'stock',
        'is_active',
        'is_featured',
        'average_rating',
        'review_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Get only size attributes for this product (convenience method)
     * Returns AttributeValue models via belongsToMany through product_attributes
     */
    public function sizes()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_attributes',
            'product_id',
            'attribute_value_id'
        )->whereHas('attribute', function($query) {
            $query->where('slug', 'size');
        })->withPivot(['stock', 'price']);
    }

    /**
     * Get size attributes with full ProductAttribute data
     */
    public function sizeAttributes()
    {
        return $this->hasMany(ProductAttribute::class)
            ->whereHas('attribute', function($query) {
                $query->where('slug', 'size');
            });
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class)
                    ->where('status', 'approved')
                    ->latest();
    }

    /**
     * Get the final selling price (sale_price or price)
     */
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Calculate GST amount
     */
    public function getGstAmountAttribute()
    {
        return ($this->final_price * $this->gst_percentage) / 100;
    }

    /**
     * Get price including GST
     */
    public function getPriceWithGstAttribute()
    {
        return $this->final_price + $this->gst_amount;
    }

    /**
     * Update review statistics (count and average rating)
     */
    public function updateReviewStats()
    {
        $approvedReviews = $this->reviews()->where('status', 'approved');

        $this->update([
            'review_count' => $approvedReviews->count(),
            'average_rating' => $approvedReviews->avg('rating') ?? 0,
        ]);
    }

    /**
     * Get review summary breakdown by star rating
     */
    public function getReviewSummaryAttribute()
    {
        return [
            '5' => $this->reviews()->where('status', 'approved')->byRating(5)->count(),
            '4' => $this->reviews()->where('status', 'approved')->byRating(4)->count(),
            '3' => $this->reviews()->where('status', 'approved')->byRating(3)->count(),
            '2' => $this->reviews()->where('status', 'approved')->byRating(2)->count(),
            '1' => $this->reviews()->where('status', 'approved')->byRating(1)->count(),
        ];
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
