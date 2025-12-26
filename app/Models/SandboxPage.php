<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SandboxPage extends Model
{
    protected $fillable = [
        'sandbox_instance_id',
        'template_id',
        'path',
        'original_content',
        'translated_content',
        'locale',
        'market',
        'last_translation_job_id',
    ];

    protected $casts = [
        'original_content' => 'array',
        'translated_content' => 'array',
    ];

    // Relationships
    public function sandboxInstance(): BelongsTo
    {
        return $this->belongsTo(SandboxInstance::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SandboxSiteTemplate::class, 'template_id');
    }

    public function translationJob(): BelongsTo
    {
        return $this->belongsTo(TranslationJob::class, 'last_translation_job_id');
    }

    // Helpers
    public function getFullUrl(): string
    {
        $baseUrl = $this->sandboxInstance->getFullSubdomainUrl();
        return rtrim($baseUrl, '/') . '/' . ltrim($this->path, '/');
    }

    public function isTranslated(): bool
    {
        return !empty($this->translated_content);
    }

    public function getTranslationProgress(): float
    {
        if (empty($this->original_content)) {
            return 0;
        }

        $originalKeys = $this->flattenArray($this->original_content);
        $translatedKeys = $this->flattenArray($this->translated_content ?? []);

        if (count($originalKeys) === 0) {
            return 0;
        }

        return (count($translatedKeys) / count($originalKeys)) * 100;
    }

    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}
