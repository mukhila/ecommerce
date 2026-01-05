<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Product\Database\Factories\CouponFactory;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'status',
        'start_date',
        'expiry_date'
    ];

    // protected static function newFactory(): CouponFactory
    // {
    //     // return CouponFactory::new();
    // }
}
