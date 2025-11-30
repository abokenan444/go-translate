<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_type',
        'title',
        'subtitle',
        'content',
        'button_text',
        'button_link',
        'button_text_secondary',
        'button_link_secondary',
        'image',
        'data',
        'order',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Relations
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('section_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
