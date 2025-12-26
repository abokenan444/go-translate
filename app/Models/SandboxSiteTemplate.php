<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SandboxSiteTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pages(): HasMany
    {
        return $this->hasMany(SandboxPage::class, 'template_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getPageStructure(): array
    {
        return $this->config['pages'] ?? [];
    }

    public function getSections(): array
    {
        return $this->config['sections'] ?? [];
    }

    public function getDefaultContent(string $locale = 'en'): array
    {
        return $this->config['default_content'][$locale] ?? [];
    }
}
