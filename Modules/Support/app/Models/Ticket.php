<?php

namespace Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Support\Database\Factories\TicketFactory;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'priority',
    ];

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    // protected static function newFactory(): TicketFactory
    // {
    //     // return TicketFactory::new();
    // }
}
