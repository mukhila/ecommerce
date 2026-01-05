<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin\Database\Factories\AdminFactory;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'role',
        'password',
        'status',
        'employee_code',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // protected static function newFactory(): AdminFactory
    // {
    //     // return AdminFactory::new();
    // }
}
