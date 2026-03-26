<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $fillable = [
        'route_name',
        'title',
        'description',
        'keywords',
        'robots',
        'canonical_url',
        'type',
        'image'
    ];
}
