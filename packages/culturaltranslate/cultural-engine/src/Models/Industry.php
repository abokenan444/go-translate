<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $table = 'industries';

    protected $fillable = [
        'key',
        'name',
        'description',
    ];
}
