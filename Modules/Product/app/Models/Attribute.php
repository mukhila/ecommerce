<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Product\Database\Factories\AttributeFactory;

class Attribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'slug'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    // protected static function newFactory(): AttributeFactory
    // {
    //     // return AttributeFactory::new();
    // }
}
