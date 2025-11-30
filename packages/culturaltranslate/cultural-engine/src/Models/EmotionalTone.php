<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class EmotionalTone extends Model
{
    protected $table = 'emotional_tones';

    protected $fillable = [
        'key',
        'label',
        'description',
        'intensity',
    ];
}
