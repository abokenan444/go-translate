<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportAgentAvailability extends Model
{
    protected $table = 'support_agent_availability';

    protected $fillable = [
        'user_id',
        'status',
        'max_concurrent_chats',
        'current_chats',
        'last_activity_at',
        'departments',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'departments' => 'array',
    ];

    /**
     * Get the agent user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for online agents.
     */
    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    /**
     * Scope for available agents (online and not at capacity).
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'online')
            ->whereColumn('current_chats', '<', 'max_concurrent_chats');
    }

    /**
     * Check if agent is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'online' && $this->current_chats < $this->max_concurrent_chats;
    }

    /**
     * Increment chat count.
     */
    public function incrementChats(): void
    {
        $this->increment('current_chats');
        $this->touch('last_activity_at');
    }

    /**
     * Decrement chat count.
     */
    public function decrementChats(): void
    {
        if ($this->current_chats > 0) {
            $this->decrement('current_chats');
        }
        $this->touch('last_activity_at');
    }

    /**
     * Go online.
     */
    public function goOnline(): void
    {
        $this->update([
            'status' => 'online',
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Go offline.
     */
    public function goOffline(): void
    {
        $this->update([
            'status' => 'offline',
            'current_chats' => 0,
        ]);
    }

    /**
     * Set as busy.
     */
    public function setBusy(): void
    {
        $this->update([
            'status' => 'busy',
            'last_activity_at' => now(),
        ]);
    }
}
