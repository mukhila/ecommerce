<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get cart subtotal (price excluding GST)
     */
    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Get total GST amount for all items
     */
    public function getGstAmountAttribute(): float
    {
        return $this->items->sum(function ($item) {
            if ($item->product && $item->product->gst_percentage) {
                $itemSubtotal = $item->price * $item->quantity;
                return ($itemSubtotal * $item->product->gst_percentage) / 100;
            }
            return 0;
        });
    }

    /**
     * Get total including GST
     */
    public function getTotalAttribute(): float
    {
        return $this->subtotal + $this->gst_amount;
    }

    /**
     * Get GST breakdown by rate
     */
    public function getGstBreakdownAttribute(): array
    {
        $breakdown = [];

        foreach ($this->items as $item) {
            if ($item->product && $item->product->gst_percentage) {
                $rate = $item->product->gst_percentage;
                $itemSubtotal = $item->price * $item->quantity;
                $gstAmount = ($itemSubtotal * $rate) / 100;

                if (!isset($breakdown[$rate])) {
                    $breakdown[$rate] = [
                        'rate' => $rate,
                        'taxable_amount' => 0,
                        'gst_amount' => 0
                    ];
                }

                $breakdown[$rate]['taxable_amount'] += $itemSubtotal;
                $breakdown[$rate]['gst_amount'] += $gstAmount;
            }
        }

        return $breakdown;
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }
}
