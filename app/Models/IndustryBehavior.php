<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryBehavior extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry',
        'language',
        'culture',
        'tone',
        'vocabulary_preferred',
        'vocabulary_avoid',
        'style_rules',
        'description',
        'active',
    ];

    protected $casts = [
        'vocabulary_preferred' => 'array',
        'vocabulary_avoid' => 'array',
        'style_rules' => 'array',
        'active' => 'boolean',
    ];
}
