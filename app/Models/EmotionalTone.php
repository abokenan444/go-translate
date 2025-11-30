<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmotionalTone extends Model
{
    protected $table = 'emotional_tones';

    protected $fillable = [
        'key',
        'name',
        'description',
        'parameters_json',
    ];

    protected $casts = [
        'parameters_json' => 'array',
    ];
}
