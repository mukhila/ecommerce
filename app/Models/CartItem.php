<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductAttribute;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variation_id',
        'quantity',
        'price',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'attributes' => 'array',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'variation_id');
    }

    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get size label from variation
     */
    public function getSizeLabelAttribute(): ?string
    {
        return $this->variation?->attributeValue?->value;
    }

    /**
     * Get available stock for this item
     */
    public function getAvailableStockAttribute(): int
    {
        if ($this->variation_id) {
            return $this->variation?->stock ?? 0;
        }
        return $this->product?->stock ?? 0;
    }
}
