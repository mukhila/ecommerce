<?php

namespace Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Support\Database\Factories\TicketMessageFactory;

class TicketMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // protected static function newFactory(): TicketMessageFactory
    // {
    //     // return TicketMessageFactory::new();
    // }
}
