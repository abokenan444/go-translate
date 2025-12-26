<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealTimeSession extends Model
{
    protected $table = 'realtime_sessions';

    protected $fillable = [
        'public_id',
        'owner_id',
        'type',
        'title',
        'source_language',
        'target_language',
        'source_culture_code',
        'target_culture_code',
        'bi_directional',
        'record_audio',
        'record_transcript',
        'is_active',
        'started_at',
        'ended_at',
        'max_participants',
        'metadata',
    ];

    protected $casts = [
        'bi_directional' => 'boolean',
        'record_audio' => 'boolean',
        'record_transcript' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function turns()
    {
        return $this->hasMany(RealTimeTurn::class, 'session_id');
    }

    public function participants()
    {
        return $this->hasMany(RealTimeParticipant::class, 'session_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
