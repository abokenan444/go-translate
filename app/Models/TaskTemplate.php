<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'slug',
        'base_prompt',
        'default_source_lang',
        'default_target_lang',
        'default_tone_slug',
        'default_industry_slug',
        'default_culture_slug',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
