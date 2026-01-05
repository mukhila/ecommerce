<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Product\Database\Factories\ProductImageFactory;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['product_id', 'image_path', 'is_primary', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // protected static function newFactory(): ProductImageFactory
    // {
    //     // return ProductImageFactory::new();
    // }
}
