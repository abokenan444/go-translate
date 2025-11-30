<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteContent extends Model
{
    protected $table = 'website_content';
    
    protected $fillable = [
        'page_slug',
        'locale',
        'page_title',
        'sections',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'status',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    /**
     * Get content for a specific page and locale
     */
    public static function getPage($slug, $locale = 'en')
    {
        return static::where('page_slug', $slug)
            ->where('locale', $locale)
            ->where('status', 'published')
            ->first();
    }

    /**
     * Get all available locales for a page
     */
    public function getAvailableLocales()
    {
        return static::where('page_slug', $this->page_slug)
            ->where('status', 'published')
            ->pluck('locale')
            ->toArray();
    }
}
