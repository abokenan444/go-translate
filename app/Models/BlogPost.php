<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'author_id',
        'status',
        'published_at',
        'locale',
        'category',
        'tags',
        'meta',
        'meta_title',
        'meta_description',
        'is_featured',
        'allow_comments',
        'views',
        'likes',
        'reading_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'meta' => 'array',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'views' => 'integer',
        'likes' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }
}
