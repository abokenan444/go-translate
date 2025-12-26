<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CulturalRiskRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rule_code',
        'category',
        'risk_level',
        'target_language',
        'target_country',
        'keywords',
        'patterns',
        'description',
        'recommendation',
        'severity_score',
        'requires_immediate_flag',
        'is_active',
    ];

    protected $casts = [
        'keywords' => 'array',
        'patterns' => 'array',
        'requires_immediate_flag' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get active rules for specific language and country
     */
    public static function getActiveRulesFor(?string $language = null, ?string $country = null)
    {
        $query = self::where('is_active', true);

        if ($language) {
            $query->where(function($q) use ($language) {
                $q->whereNull('target_language')
                  ->orWhere('target_language', $language);
            });
        }

        if ($country) {
            $query->where(function($q) use ($country) {
                $q->whereNull('target_country')
                  ->orWhere('target_country', $country);
            });
        }

        return $query->orderBy('severity_score', 'desc')->get();
    }

    /**
     * Check if text matches this rule
     */
    public function matchesText(string $text): bool
    {
        // Check keywords
        if ($this->keywords) {
            foreach ($this->keywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    return true;
                }
            }
        }

        // Check patterns
        if ($this->patterns) {
            foreach ($this->patterns as $pattern) {
                if (@preg_match($pattern, $text)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the badge color for risk level
     */
    public function getRiskBadgeColorAttribute(): string
    {
        return match($this->risk_level) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'danger',
            default => 'gray',
        };
    }
}
