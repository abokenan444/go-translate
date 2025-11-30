<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulturalProfile extends Model
{
    protected $table = 'cultural_profiles';
    
    protected $fillable = [
        'culture_code',
        'culture_name',
        'native_name',
        'description',
        'characteristics',
        'preferred_tones',
        'taboos',
        'special_styles',
        'symbols_references',
        'formality_level',
        'directness',
        'uses_honorifics',
        'emotional_expressiveness',
        'common_expressions',
        'marketing_preferences',
        'business_etiquette',
        'text_direction',
        'date_formats',
        'number_formats',
        'currency_symbol',
        'system_prompt',
        'translation_guidelines',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'uses_honorifics' => 'boolean',
        'is_active' => 'boolean',
        'emotional_expressiveness' => 'integer',
        'priority' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('culture_name');
    }
}
