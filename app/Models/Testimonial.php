<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'company',
        'avatar',
        'content',
        'rating',
        'locale',
        'source',
        'source_url',
        'is_active',
        'is_featured',
        'is_verified',
        'sort_order',
        'testimonial_date',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'testimonial_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
