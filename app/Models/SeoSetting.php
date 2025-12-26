<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page',
        'title',
        'description',
        'keywords',
        'og_image',
        'og_title',
        'og_description',
    ];

    public static function forPage(string $page): ?self
    {
        return static::where('page', $page)->first();
    }
}
