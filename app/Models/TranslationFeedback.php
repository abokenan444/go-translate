<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationFeedback extends Model
{
    protected $fillable = [
        'translation_id',
        'translation_version_id',
        'user_id',
        'rating',
        'tag',
        'comment',
        'suggested_text',
        'meta',
    ];

    protected $casts = [
        'rating' => 'integer',
        'meta' => 'array',
    ];

    public function translation(): BelongsTo
    {
        return $this->belongsTo(Translation::class);
    }

    public function translationVersion(): BelongsTo
    {
        return $this->belongsTo(TranslationVersion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
