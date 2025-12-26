<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
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
        'is_active' => 'boolean',
        'data' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
