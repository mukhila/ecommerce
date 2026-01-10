<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Product\Database\Factories\ProductAttributeFactory;

class ProductAttribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['product_id', 'attribute_id', 'attribute_value_id', 'stock', 'price'];

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

    // protected static function newFactory(): ProductAttributeFactory
    // {
    //     // return ProductAttributeFactory::new();
    // }
}
