<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportChatMessage extends Model
{
    protected $fillable = [
        'session_id',
        'sender_type',
        'sender_id',
        'sender_name',
        'message',
        'message_type',
        'file_url',
        'file_name',
        'is_read',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the chat session.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(SupportChatSession::class, 'session_id');
    }

    /**
     * Get the sender user (if authenticated).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope for unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for agent messages.
     */
    public function scopeFromAgent($query)
    {
        return $query->where('sender_type', 'agent');
    }

    /**
     * Scope for user messages.
     */
    public function scopeFromUser($query)
    {
        return $query->where('sender_type', 'user');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}
