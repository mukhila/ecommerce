<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'name',
        'email',
        'order_id',
        'category',
        'priority',
        'status',
        'subject',
        'message',
        'attachment',
        'assigned_to',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method to generate ticket number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->ticket_number) {
                $ticket->ticket_number = 'TKT-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the user who created this ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin assigned to this ticket
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all replies for this ticket
     */
    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class, 'ticket_id');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'open' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'low' => 'secondary',
            'medium' => 'warning',
            'high' => 'danger',
            default => 'secondary'
        };
    }
}
