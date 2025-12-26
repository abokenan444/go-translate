<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketMessage extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_staff',
        'is_internal_note',
        'attachments',
    ];

    protected $casts = [
        'is_staff' => 'boolean',
        'is_internal_note' => 'boolean',
        'attachments' => 'array',
    ];

    /**
     * Get the ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /**
     * Get the user who sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for staff messages.
     */
    public function scopeFromStaff($query)
    {
        return $query->where('is_staff', true);
    }

    /**
     * Scope for customer messages.
     */
    public function scopeFromCustomer($query)
    {
        return $query->where('is_staff', false);
    }

    /**
     * Scope for public messages (not internal notes).
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal_note', false);
    }
}
