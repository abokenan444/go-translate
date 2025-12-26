<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranslationType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'base_price',
        'price_per_word',
        'price_per_page',
        'estimated_time_hours',
        'requires_certification',
        'requires_notarization',
        'is_active',
        'sort_order',
        'supported_languages',
        'meta',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'price_per_word' => 'decimal:4',
        'price_per_page' => 'decimal:2',
        'requires_certification' => 'boolean',
        'requires_notarization' => 'boolean',
        'is_active' => 'boolean',
        'supported_languages' => 'array',
        'meta' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
