<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'project_id',
        'assessment_type',
        'source_language',
        'target_language',
        'target_country',
        'use_case',
        'domain',
        'source_text',
        'translated_text',
        'cts_level',
        'risk_flags',
        'cultural_impact_score',
        'recommendation',
        'requires_human_review',
        'assessed_at',
    ];

    protected $casts = [
        'risk_flags' => 'array',
        'requires_human_review' => 'boolean',
        'assessed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the assessment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project associated with the assessment
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the CTS standard for this assessment
     */
    public function ctsStandard(): ?CtsStandard
    {
        return CtsStandard::where('level', $this->cts_level)->first();
    }

    /**
     * Get risk level color
     */
    public function getRiskLevelColorAttribute(): string
    {
        return match($this->cts_level) {
            'CTS-A' => 'success',
            'CTS-B' => 'info',
            'CTS-C' => 'warning',
            'CTS-R' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Get impact score badge color
     */
    public function getImpactScoreBadgeColorAttribute(): string
    {
        $score = $this->cultural_impact_score;

        return match(true) {
            $score >= 80 => 'success',
            $score >= 60 => 'info',
            $score >= 40 => 'warning',
            default => 'danger',
        };
    }

    /**
     * Check if assessment has critical risks
     */
    public function hasCriticalRisks(): bool
    {
        return $this->cts_level === 'CTS-R' || 
               $this->cultural_impact_score < 40 ||
               $this->requires_human_review;
    }
}
