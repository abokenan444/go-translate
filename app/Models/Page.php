<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'show_in_header',
        'show_in_footer',
        'header_order',
        'footer_order',
        'footer_column',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'show_in_header' => 'boolean',
        'show_in_footer' => 'boolean',
        'header_order' => 'integer',
        'footer_order' => 'integer',
    ];

    // Relations
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    public function activeSections(): HasMany
    {
        return $this->hasMany(PageSection::class)->where('is_active', true)->orderBy('order');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeInHeader($query)
    {
        return $query->where('show_in_header', true)->orderBy('header_order');
    }

    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true)->orderBy('footer_order');
    }

    public function scopeInFooterColumn($query, $column)
    {
        return $query->where('show_in_footer', true)
                     ->where('footer_column', $column)
                     ->orderBy('footer_order');
    }
}
