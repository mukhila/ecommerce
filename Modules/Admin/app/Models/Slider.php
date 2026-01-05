<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin\Database\Factories\SliderFactory;

class Slider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'status',
        'sort_order',
    ];

    // protected static function newFactory(): SliderFactory
    // {
    //     // return SliderFactory::new();
    // }
}
