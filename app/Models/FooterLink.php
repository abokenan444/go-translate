<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FooterLink extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_en',
        'title_ar',
        'url',
        'section',
        'display_order',
        'is_active',
        'open_in_new_tab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'display_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function getTitle(string $locale = 'en'): string
    {
        return $locale === 'ar' ? $this->title_ar : $this->title_en;
    }
}
