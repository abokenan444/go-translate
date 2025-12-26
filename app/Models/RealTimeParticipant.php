<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealTimeParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'external_id',
        'display_name',
        'send_language',
        'receive_language',
        'role',
        'status',
        'is_muted',
        'is_video_enabled',
        'joined_at',
        'left_at',
        'total_speaking_time',
        'connection_quality',
    ];

    protected $casts = [
        'is_muted' => 'boolean',
        'is_video_enabled' => 'boolean',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'connection_quality' => 'array',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(RealTimeSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isConnected(): bool
    {
        return $this->status === 'connected';
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    public function canSpeak(): bool
    {
        return in_array($this->role, ['moderator', 'speaker']);
    }

    public function mute(): void
    {
        $this->update([
            'is_muted' => true,
            'status' => 'muted'
        ]);
    }

    public function unmute(): void
    {
        $this->update([
            'is_muted' => false,
            'status' => 'connected'
        ]);
    }

    public function disconnect(): void
    {
        $this->update([
            'status' => 'disconnected',
            'left_at' => now()
        ]);
    }

    public function updateConnectionQuality(array $metrics): void
    {
        $this->update([
            'connection_quality' => array_merge(
                $this->connection_quality ?? [],
                $metrics
            )
        ]);
    }
}
