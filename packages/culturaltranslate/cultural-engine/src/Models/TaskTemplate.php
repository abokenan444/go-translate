<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    protected $table = 'task_templates';

    protected $fillable = [
        'key',
        'name',
        'type',
        'category',
        'industry_key',
        'base_prompt',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
