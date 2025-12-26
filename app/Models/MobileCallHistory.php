<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileCallHistory extends Model
{
    protected $table = 'mobile_call_history';

    protected $fillable = [
        'user_id',
        'contact_id',
        'caller_id',
        'receiver_id',
        'session_public_id',
        'direction',
        'status',
        'caller_send_language',
        'caller_receive_language',
        'receiver_send_language',
        'receiver_receive_language',
        'duration_seconds',
        'minutes_used',
        'started_at',
        'ended_at',
        // Cost sharing fields
        'cost_payer',
        'cost_share_requested',
        'cost_share_status',
        'caller_cost_minutes',
        'receiver_cost_minutes',
        'total_cost_minutes',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'minutes_used' => 'decimal:2',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'cost_share_requested' => 'boolean',
        'caller_cost_minutes' => 'decimal:2',
        'receiver_cost_minutes' => 'decimal:2',
        'total_cost_minutes' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(MobileContact::class, 'contact_id');
    }

    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getDurationMinutesAttribute(): float
    {
        return round($this->duration_seconds / 60, 2);
    }

    public function getFormattedDurationAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
