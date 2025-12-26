<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentEmailDomain extends Model
{
    protected $fillable = [
        'domain',
        'country_code',
        'country_name',
        'entity_type',
        'is_active',
        'requires_additional_verification',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_additional_verification' => 'boolean',
    ];

    /**
     * Scope: Active domains only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: By country
     */
    public function scopeCountry($query, string $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    /**
     * Check if a domain is valid
     */
    public static function isValid(string $domain): bool
    {
        return self::where('domain', $domain)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get all active government domains
     */
    public static function getAllActiveDomains(): array
    {
        return self::active()
            ->pluck('domain')
            ->toArray();
    }
}
