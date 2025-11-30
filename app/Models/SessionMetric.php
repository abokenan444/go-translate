<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'recorded_at',
        'avg_latency',
        'max_latency',
        'min_latency',
        'packet_loss_rate',
        'jitter',
        'avg_audio_level',
        'audio_quality',
        'video_quality',
        'active_participants',
        'total_turns',
        'total_audio_duration',
        'avg_translation_time',
        'successful_translations',
        'failed_translations',
        'reconnections',
        'disconnections',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'packet_loss_rate' => 'float',
        'jitter' => 'float',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(RealTimeSession::class, 'session_id');
    }

    public function getOverallQualityAttribute(): string
    {
        $score = 0;
        $factors = 0;

        // Latency score
        if ($this->avg_latency !== null) {
            if ($this->avg_latency < 100) $score += 3;
            elseif ($this->avg_latency < 200) $score += 2;
            else $score += 1;
            $factors++;
        }

        // Packet loss score
        if ($this->packet_loss_rate !== null) {
            if ($this->packet_loss_rate < 1) $score += 3;
            elseif ($this->packet_loss_rate < 3) $score += 2;
            else $score += 1;
            $factors++;
        }

        // Audio quality score
        if ($this->audio_quality) {
            $qualityMap = ['good' => 3, 'fair' => 2, 'poor' => 1];
            $score += $qualityMap[$this->audio_quality] ?? 0;
            $factors++;
        }

        if ($factors === 0) return 'unknown';

        $avgScore = $score / $factors;
        
        if ($avgScore >= 2.5) return 'excellent';
        if ($avgScore >= 2) return 'good';
        if ($avgScore >= 1.5) return 'fair';
        return 'poor';
    }
}
