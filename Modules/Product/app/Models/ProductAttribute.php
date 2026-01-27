<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
        'stock',
        'price',
        'is_active'
    ];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    /**
     * Scope: Only active variations
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Only in-stock variations
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope: Only size attributes
     */
    public function scopeSizeOnly(Builder $query): Builder
    {
        return $query->whereHas('attribute', fn($q) => $q->where('slug', 'size'));
    }

    /**
     * Check if variation is available (active + in stock)
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    /**
     * Get effective price (variation price or fallback to product price)
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->price !== null) {
            return (float) $this->price;
        }
        return (float) ($this->product->sale_price ?? $this->product->price);
    }

    /**
     * Get size label
     */
    public function getSizeLabelAttribute(): ?string
    {
        return $this->attributeValue?->value;
    }

    /**
     * Check if this variation is used in any order
     */
    public function hasOrders(): bool
    {
        return \App\Models\OrderItem::where('variation_id', $this->id)->exists();
    }

    /**
     * Deduct stock with locking (for concurrent order safety)
     */
    public function deductStock(int $quantity): bool
    {
        return static::where('id', $this->id)
            ->where('stock', '>=', $quantity)
            ->lockForUpdate()
            ->update(['stock' => \DB::raw("stock - {$quantity}")]) > 0;
    }

    /**
     * Restore stock (for order cancellation)
     */
    public function restoreStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }
}
