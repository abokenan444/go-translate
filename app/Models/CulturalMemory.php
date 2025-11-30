<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulturalMemory extends Model
{
    use HasFactory;

    protected $table = 'cultural_memories';

    protected $fillable = [
        'user_id',
        'source_language',
        'target_language',
        'target_culture',
        'source_text',
        'translated_text',
        'brand_voice',
        'emotion',
        'tone',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'source_text' => 'encrypted',
        'translated_text' => 'encrypted',
    ];

    // Override attribute encryption to support per-record key tracking if custom keys provided.
    public function setSourceTextAttribute($value)
    {
        $this->attributes['source_text'] = app(\App\Services\Security\MemoryEncryptionService::class)->encrypt($value, $this->encryption_key_id);
    }

    public function getSourceTextAttribute($value)
    {
        return app(\App\Services\Security\MemoryEncryptionService::class)->decrypt($value, $this->encryption_key_id);
    }

    public function setTranslatedTextAttribute($value)
    {
        $this->attributes['translated_text'] = app(\App\Services\Security\MemoryEncryptionService::class)->encrypt($value, $this->encryption_key_id);
    }

    public function getTranslatedTextAttribute($value)
    {
        return app(\App\Services\Security\MemoryEncryptionService::class)->decrypt($value, $this->encryption_key_id);
    }
}
