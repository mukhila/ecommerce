<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'guest_email',
        'guest_name',
        'guest_phone',
        'subtotal',
        'gst_amount',
        'gst_breakdown',
        'tax',
        'shipping_cost',
        'discount',
        'total',
        'status',
        'tracking_number',
        'courier_name',
        'estimated_delivery_date',
        'payment_status',
        'payment_method',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'gst_breakdown' => 'array',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(ShippingAddress::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }
}
