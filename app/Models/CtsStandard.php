<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CtsStandard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'version',
        'level',
        'level_name',
        'description',
        'required_checks',
        'certification_rules',
        'min_impact_score',
        'max_impact_score',
        'requires_human_review',
        'is_active',
    ];

    protected $casts = [
        'required_checks' => 'array',
        'certification_rules' => 'array',
        'requires_human_review' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the badge color for this CTS level
     */
    public function getBadgeColorAttribute(): string
    {
        return match($this->level) {
            'CTS-A' => 'success',
            'CTS-B' => 'info',
            'CTS-C' => 'warning',
            'CTS-R' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Check if a score falls within this level's range
     */
    public function isScoreInRange(int $score): bool
    {
        return $score >= $this->min_impact_score && $score <= $this->max_impact_score;
    }

    /**
     * Get the appropriate CTS level for a given score
     */
    public static function getLevelForScore(int $score): ?self
    {
        return self::where('is_active', true)
            ->where('min_impact_score', '<=', $score)
            ->where('max_impact_score', '>=', $score)
            ->first();
    }
}
