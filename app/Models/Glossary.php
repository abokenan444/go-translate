<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Glossary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'term',
        'translation',
        'language_pair',
        'context',
        'notes',
        'case_sensitive',
        'is_active',
    ];

    protected $casts = [
        'case_sensitive' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getSourceLanguage(): string
    {
        return explode('-', $this->language_pair)[0] ?? '';
    }

    public function getTargetLanguage(): string
    {
        return explode('-', $this->language_pair)[1] ?? '';
    }
}
