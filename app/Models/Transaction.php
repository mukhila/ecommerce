<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'gateway_transaction_id',
        'gateway_reference',
        'amount',
        'currency',
        'status',
        'payment_method',
        'raw_response',
        'error_message'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_response' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
