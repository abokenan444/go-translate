<?php

namespace App\Models\Cultural;

use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    protected $table = 'task_templates';

    protected $fillable = [
        'key',
        'name',
        'description',
        'category',
        'prompt_template',
        'input_schema_json',
    ];

    protected $casts = [
        'input_schema_json' => 'array',
    ];
}
