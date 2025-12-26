<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryCulturalProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'country_code',
        'country_name',
        'languages',
        'religious_sensitivities',
        'political_sensitivities',
        'social_norms',
        'legal_requirements',
        'taboo_topics',
        'preferred_communication_style',
        'cultural_tolerance_score',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'languages' => 'array',
        'religious_sensitivities' => 'array',
        'political_sensitivities' => 'array',
        'social_norms' => 'array',
        'legal_requirements' => 'array',
        'taboo_topics' => 'array',
        'preferred_communication_style' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get profile by country code
     */
    public static function getByCountryCode(string $countryCode): ?self
    {
        return self::where('country_code', strtoupper($countryCode))
            ->where('is_active', true)
            ->first();
    }

    /**
     * Check if a topic is taboo in this country
     */
    public function isTabooTopic(string $topic): bool
    {
        if (!$this->taboo_topics) {
            return false;
        }

        foreach ($this->taboo_topics as $taboo) {
            if (stripos($topic, $taboo) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get tolerance level description
     */
    public function getToleranceLevelAttribute(): string
    {
        $score = $this->cultural_tolerance_score;

        return match(true) {
            $score >= 80 => 'Very High',
            $score >= 60 => 'High',
            $score >= 40 => 'Medium',
            $score >= 20 => 'Low',
            default => 'Very Low',
        };
    }
}
