<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerMetric extends Model
{
    protected $fillable = [
        'partner_profile_id',
        'jobs_completed',
        'jobs_rejected',
        'jobs_expired',
        'avg_accept_minutes',
        'avg_review_hours',
        'quality_score',
        'sla_score',
        'last_active_at',
    ];

    protected $casts = [
        'jobs_completed' => 'integer',
        'jobs_rejected' => 'integer',
        'jobs_expired' => 'integer',
        'avg_accept_minutes' => 'float',
        'avg_review_hours' => 'float',
        'quality_score' => 'float',
        'sla_score' => 'float',
        'last_active_at' => 'datetime',
    ];

    public function partnerProfile()
    {
        return $this->belongsTo(PartnerProfile::class);
    }

    /**
     * Calculate overall partner score
     */
    public function getOverallScoreAttribute(): float
    {
        $weights = config('offers.scoring.weights', [
            'quality_score' => 0.4,
            'sla_score' => 0.3,
            'accept_speed' => 0.2,
            'completion_rate' => 0.1,
        ]);

        $acceptSpeed = $this->avg_accept_minutes > 0 ? (60 / $this->avg_accept_minutes) * 5 : 5;
        $acceptSpeed = min(5, max(1, $acceptSpeed));

        $totalJobs = $this->jobs_completed + $this->jobs_rejected + $this->jobs_expired;
        $completionRate = $totalJobs > 0 ? ($this->jobs_completed / $totalJobs) * 5 : 5;

        return ($this->quality_score * $weights['quality_score']) +
               ($this->sla_score * $weights['sla_score']) +
               ($acceptSpeed * $weights['accept_speed']) +
               ($completionRate * $weights['completion_rate']);
    }
}
