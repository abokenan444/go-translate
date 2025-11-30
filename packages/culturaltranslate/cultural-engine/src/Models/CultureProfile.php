<?php

namespace CulturalTranslate\CulturalEngine\Models;

use Illuminate\Database\Eloquent\Model;

class CultureProfile extends Model
{
    protected $table = 'culture_profiles';

    protected $fillable = [
        'key',
        'name',
        'locale',
        'country_code',
        'description',
        'audience_notes',
        'constraints',
        'is_default',
    ];

    protected $casts = [
        'constraints' => 'array',
        'is_default'  => 'boolean',
    ];
}
