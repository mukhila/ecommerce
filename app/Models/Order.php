<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderCancelled;

class Order extends Model
{
    use HasFactory;
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
        'payment_expires_at',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'gst_breakdown' => 'array',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_expires_at' => 'datetime',
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

            // Set payment expiration (7 days from creation)
            if (!$order->payment_expires_at) {
                $order->payment_expires_at = now()->addDays(7);
            }
        });
    }

    /**
     * Cancel the order and restore stock
     *
     * @param string|null $reason
     * @return bool
     */
    public function cancelOrder(?string $reason = null): bool
    {
        return DB::transaction(function () use ($reason) {
            // Prevent double cancellation
            if ($this->status === 'cancelled') {
                Log::warning('Attempted to cancel already cancelled order', [
                    'order_id' => $this->id,
                    'order_number' => $this->order_number
                ]);
                return false;
            }

            // Restore stock for each order item
            foreach ($this->items()->with(['product', 'variation'])->get() as $item) {
                // Restore variation stock if applicable
                if ($item->variation_id && $item->variation) {
                    $item->variation->increment('stock', $item->quantity);
                    Log::info('Variation stock restored', [
                        'product_id' => $item->product_id,
                        'variation_id' => $item->variation_id,
                        'size' => $item->size_label,
                        'quantity' => $item->quantity,
                        'new_stock' => $item->variation->fresh()->stock
                    ]);
                } elseif ($item->product) {
                    // Restore product stock
                    $item->product->increment('stock', $item->quantity);
                    Log::info('Product stock restored', [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'new_stock' => $item->product->fresh()->stock
                    ]);
                } else {
                    Log::warning('Cannot restore stock - product deleted', [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name
                    ]);
                }
            }

            // Update order status
            $this->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason
            ]);

            // Send notification
            $this->sendCancellationNotification($reason);

            Log::info('Order cancelled', [
                'order_id' => $this->id,
                'order_number' => $this->order_number,
                'reason' => $reason
            ]);

            return true;
        });
    }

    /**
     * Send cancellation notification to user or guest
     *
     * @param string|null $reason
     * @return void
     */
    protected function sendCancellationNotification(?string $reason): void
    {
        try {
            if ($this->user) {
                $this->user->notify(new OrderCancelled($this, $reason));
            } elseif ($this->shippingAddress && $this->shippingAddress->email) {
                Notification::route('mail', $this->shippingAddress->email)
                    ->notify(new OrderCancelled($this, $reason));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send cancellation notification', [
                'order_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Scope to get orders eligible for auto-cancellation
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiredUnpaid($query)
    {
        return $query
            ->where('payment_status', '!=', 'paid')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->where('payment_expires_at', '<', now());
    }
}
