<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranslationVersion extends Model
{
    protected $fillable = [
        'translation_id',
        'user_id',
        'source_text',
        'translated_text',
        'model',
        'tone',
        'culture_code',
        'is_suggested',
        'is_approved',
        'score',
    ];

    protected $casts = [
        'is_suggested' => 'boolean',
        'is_approved' => 'boolean',
        'score' => 'integer',
    ];

    public function translation(): BelongsTo
    {
        return $this->belongsTo(Translation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(TranslationFeedback::class);
    }

    /**
     * Approve this version
     */
    public function approve(): void
    {
        $this->update(['is_approved' => true]);
    }

    /**
     * Increment score
     */
    public function incrementScore(int $amount = 1): void
    {
        $this->increment('score', $amount);
    }
}
