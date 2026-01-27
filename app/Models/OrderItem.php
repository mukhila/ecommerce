<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductAttribute;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'size_label',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'total',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'attributes' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'variation_id');
    }

    /**
     * Get display name with size
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->product_name;
        if ($this->size_label) {
            $name .= " - Size: {$this->size_label}";
        }
        return $name;
    }
}
