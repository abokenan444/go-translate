<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'primary_language',
        'primary_market',
        'tone_preferences',
        'forbidden_words',
        'preferred_words',
        'style_guide',
        'is_active',
    ];

    protected $casts = [
        'tone_preferences' => 'array',
        'forbidden_words' => 'array',
        'preferred_words' => 'array',
        'style_guide' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
