<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Collection;

class AssignmentScoringService
{
    /**
     * Rank partners based on multiple criteria
     */
    public function rankPartners(Collection $partners): Collection
    {
        return $partners->sortByDesc(function(Partner $partner) {
            return $this->calculateScore($partner);
        })->values();
    }

    /**
     * Calculate partner score
     * 
     * Factors:
     * - Rating (0-5): 40% weight
     * - Acceptance rate (0-100): 30% weight
     * - On-time rate (0-100): 20% weight
     * - Availability (capacity): 10% weight
     */
    protected function calculateScore(Partner $partner): float
    {
        $ratingScore = ($partner->rating / 5.0) * 40;
        $acceptanceScore = ($partner->acceptance_rate / 100) * 30;
        $onTimeScore = ($partner->on_time_rate / 100) * 20;
        
        // Availability: more capacity = higher score
        $activeJobs = $partner->assignments()
            ->where('status', 'accepted')
            ->count();
        $availabilityRatio = 1 - ($activeJobs / max($partner->max_concurrent_jobs, 1));
        $availabilityScore = $availabilityRatio * 10;

        return $ratingScore + $acceptanceScore + $onTimeScore + $availabilityScore;
    }

    /**
     * Get detailed score breakdown (for debugging/admin view)
     */
    public function getScoreBreakdown(Partner $partner): array
    {
        $activeJobs = $partner->assignments()
            ->where('status', 'accepted')
            ->count();
        $availabilityRatio = 1 - ($activeJobs / max($partner->max_concurrent_jobs, 1));

        return [
            'rating_score' => ($partner->rating / 5.0) * 40,
            'acceptance_score' => ($partner->acceptance_rate / 100) * 30,
            'on_time_score' => ($partner->on_time_rate / 100) * 20,
            'availability_score' => $availabilityRatio * 10,
            'total_score' => $this->calculateScore($partner),
            'raw_values' => [
                'rating' => $partner->rating,
                'acceptance_rate' => $partner->acceptance_rate,
                'on_time_rate' => $partner->on_time_rate,
                'active_jobs' => $activeJobs,
                'max_jobs' => $partner->max_concurrent_jobs,
            ],
        ];
    }
}
