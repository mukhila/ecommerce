<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin\Database\Factories\CompanySettingFactory;

class CompanySetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_name',
        'logo',
        'address',
        'phone',
        'email',
        'whatsapp_no',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    // protected static function newFactory(): CompanySettingFactory
    // {
    //     // return CompanySettingFactory::new();
    // }
}
