<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingData extends Model
{
    use HasFactory;

    protected $table = 'training_data';

    protected $fillable = [
        'source_text',
        'source_language',
        'target_language',
        'translated_text',
        'tone',
        'context',
        'industry',
        'model_used',
        'user_rating',
        'user_feedback',
        'is_approved',
        'word_count',
        'tokens_used',
        'user_id',
        'project_id',
        'is_suitable_for_training',
        'contains_sensitive_data',
        'data_quality',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_suitable_for_training' => 'boolean',
        'contains_sensitive_data' => 'boolean',
        'word_count' => 'integer',
        'tokens_used' => 'integer',
        'user_rating' => 'integer',
    ];

    /**
     * Get the user that owns the training data.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that owns the training data.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope to get only approved training data.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get only suitable for training data.
     */
    public function scopeSuitableForTraining($query)
    {
        return $query->where('is_suitable_for_training', true)
                     ->where('contains_sensitive_data', false);
    }

    /**
     * Scope to get high quality data.
     */
    public function scopeHighQuality($query)
    {
        return $query->whereIn('data_quality', ['good', 'excellent'])
                     ->where('user_rating', '>=', 4);
    }

    /**
     * Scope to filter by language pair.
     */
    public function scopeLanguagePair($query, $sourceLang, $targetLang)
    {
        return $query->where('source_language', $sourceLang)
                     ->where('target_language', $targetLang);
    }

    /**
     * Get statistics for training data.
     */
    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'approved' => self::where('is_approved', true)->count(),
            'suitable_for_training' => self::suitableForTraining()->count(),
            'high_quality' => self::highQuality()->count(),
            'by_language_pair' => self::selectRaw('source_language, target_language, COUNT(*) as count')
                ->groupBy('source_language', 'target_language')
                ->get(),
            'average_rating' => self::whereNotNull('user_rating')->avg('user_rating'),
            'total_words' => self::sum('word_count'),
            'total_tokens' => self::sum('tokens_used'),
        ];
    }

    /**
     * Export data for training in JSONL format.
     */
    public static function exportForTraining($languagePair = null)
    {
        $query = self::suitableForTraining()->highQuality();
        
        if ($languagePair) {
            $query->languagePair($languagePair['source'], $languagePair['target']);
        }

        return $query->get()->map(function ($item) {
            return [
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional cultural translator. Translate the following text while preserving cultural context, emotional tone, and brand voice.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Translate from {$item->source_language} to {$item->target_language}:\n\n{$item->source_text}"
                    ],
                    [
                        'role' => 'assistant',
                        'content' => $item->translated_text
                    ]
                ],
                'metadata' => [
                    'tone' => $item->tone,
                    'context' => $item->context,
                    'industry' => $item->industry,
                    'rating' => $item->user_rating,
                ]
            ];
        });
    }
}
