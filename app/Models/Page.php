<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
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
        'show_in_header' => 'boolean',
        'show_in_footer' => 'boolean',
    ];
    
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('display_order');
    }
    
    // Removed translations relationship for now
    // Can be added back when page_translations table is created
}
