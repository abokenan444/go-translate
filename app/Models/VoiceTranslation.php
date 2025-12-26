<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoiceTranslation extends Model
{
    protected $fillable = [
        'user_id',
        'user_subscription_id',
        'audio_file_path',
        'source_language',
        'target_language',
        'audio_duration_seconds',
        'audio_file_size',
        'transcribed_text',
        'transcription_confidence',
        'translated_text',
        'translation_quality_score',
        'output_audio_path',
        'voice_name',
        'output_duration_seconds',
        'cost',
        'pricing_model',
        'status',
        'error_message',
        'processing_time_ms',
        'ip_address',
        'metadata',
    ];

    protected $casts = [
        'transcription_confidence' => 'decimal:2',
        'translation_quality_score' => 'decimal:2',
        'cost' => 'decimal:4',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    /**
     * Calculate cost based on duration
     */
    public static function calculateCost(int $durationSeconds, string $pricingModel = 'per_second'): float
    {
        switch ($pricingModel) {
            case 'per_second':
                return $durationSeconds * 0.001; // $0.001 per second
            case 'per_minute':
                return ceil($durationSeconds / 60) * 0.05; // $0.05 per minute
            case 'flat_rate':
                return 0.10; // $0.10 flat
            default:
                return 0;
        }
    }

    /**
     * Get statistics
     */
    public static function getStatistics($userId, string $period = 'month')
    {
        $query = static::where('user_id', $userId)
            ->where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        return [
            'total_translations' => $query->count(),
            'total_duration' => $query->sum('audio_duration_seconds'),
            'total_cost' => $query->sum('cost'),
            'avg_confidence' => $query->avg('transcription_confidence'),
            'avg_quality' => $query->avg('translation_quality_score'),
        ];
    }
}
