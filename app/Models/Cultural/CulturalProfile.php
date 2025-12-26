<?php

namespace App\Models\Cultural;

use Illuminate\Database\Eloquent\Model;

class CulturalProfile extends Model
{
    protected $table = 'cultural_profiles';

    protected $fillable = [
        'code',
        'name',
        'locale',
        'region',
        'description',
        'values_json',
        'examples_json',
    ];

    protected $casts = [
        'values_json'   => 'array',
        'examples_json' => 'array',
    ];
}
