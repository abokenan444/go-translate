<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportChatSession extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'visitor_name',
        'visitor_email',
        'status',
        'agent_id',
        'department',
        'started_at',
        'ended_at',
        'rating',
        'feedback',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user for this chat session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the support agent.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the messages for this session.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(SupportChatMessage::class, 'session_id');
    }

    /**
     * Generate unique session ID.
     */
    public static function generateSessionId(): string
    {
        return 'CHAT-' . strtoupper(uniqid()) . '-' . time();
    }

    /**
     * Scope for active sessions.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['waiting', 'active']);
    }

    /**
     * Scope for waiting sessions.
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Mark session as active with agent.
     */
    public function assignAgent(User $agent): void
    {
        $this->update([
            'agent_id' => $agent->id,
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    /**
     * Close the chat session.
     */
    public function close(): void
    {
        $this->update([
            'status' => 'closed',
            'ended_at' => now(),
        ]);
    }
}
