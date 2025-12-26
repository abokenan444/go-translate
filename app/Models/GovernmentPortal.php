<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GovernmentPortal extends Model
{
    protected $fillable = [
        'country_code',
        'country_name',
        'country_name_native',
        'portal_slug',
        'subdomain_pattern',
        'default_language',
        'supported_languages',
        'currency_code',
        'timezone',
        'requires_certified_translation',
        'requires_notarization',
        'requires_apostille',
        'legal_disclaimer',
        'contact_email',
        'contact_phone',
        'contact_address',
        'logo_path',
        'primary_color',
        'secondary_color',
        'is_active',
        'launched_at',
    ];

    protected $casts = [
        'supported_languages' => 'array',
        'requires_certified_translation' => 'boolean',
        'requires_notarization' => 'boolean',
        'requires_apostille' => 'boolean',
        'is_active' => 'boolean',
        'launched_at' => 'datetime',
    ];

    /**
     * Get stats for this portal
     */
    public function stats(): HasMany
    {
        return $this->hasMany(GovernmentPortalStat::class, 'portal_id');
    }

    /**
     * Scope for active portals
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full URL for this portal
     */
    public function getPortalUrlAttribute(): string
    {
        $baseDomain = config('ct.government_base_domain', 'gov.culturaltranslate.com');
        
        if ($this->subdomain_pattern === 'prefix') {
            return "https://{$this->portal_slug}-gov.culturaltranslate.com";
        }
        
        return "https://{$baseDomain}/{$this->portal_slug}";
    }

    /**
     * Find portal by country code or slug
     */
    public static function findByCountryOrSlug(string $identifier): ?self
    {
        return static::where('country_code', strtoupper($identifier))
            ->orWhere('portal_slug', strtolower($identifier))
            ->first();
    }

    /**
     * Extract country code from subdomain/path
     */
    public static function extractCountryFromRequest(\Illuminate\Http\Request $request): ?string
    {
        $host = $request->getHost();
        $path = $request->path();
        
        // Check prefix pattern: nl-gov.culturaltranslate.com
        if (preg_match('/^([a-z]{2})-gov\./', $host, $matches)) {
            return strtoupper($matches[1]);
        }
        
        // Check path pattern: gov.culturaltranslate.com/nl
        if (str_contains($host, 'gov.')) {
            $segments = explode('/', trim($path, '/'));
            if (!empty($segments[0]) && strlen($segments[0]) === 2) {
                return strtoupper($segments[0]);
            }
        }
        
        return null;
    }
}
