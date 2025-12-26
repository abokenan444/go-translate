<?php

namespace App\Services\Assignments;

use App\Models\DocumentAssignment;
use App\Models\OfficialDocument;
use App\Models\PartnerProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignmentEngineService
{
    /**
     * Offer document to eligible partners (parallel offers)
     *
     * @param OfficialDocument $document
     * @return array
     */
    public function offer(OfficialDocument $document): array
    {
        $parallelOffers = config('offers.parallel_offers', 2);
        $attemptCount = DocumentAssignment::where('official_document_id', $document->id)->count();
        
        if ($attemptCount >= config('offers.max_attempts', 7)) {
            return ['success' => false, 'reason' => 'max_attempts_reached'];
        }
        
        // Get eligible partners
        $eligiblePartners = $this->eligiblePartners($document);
        
        if ($eligiblePartners->isEmpty()) {
            return ['success' => false, 'reason' => 'no_eligible_partners'];
        }
        
        // Create parallel offers
        $created = [];
        $deadline = now()->addMinutes(config('offers.accept_deadline_minutes', 60));
        
        DB::transaction(function () use ($eligiblePartners, $document, $parallelOffers, $deadline, &$created) {
            foreach ($eligiblePartners->take($parallelOffers) as $partner) {
                $assignment = DocumentAssignment::create([
                    'official_document_id' => $document->id,
                    'partner_profile_id' => $partner->id,
                    'status' => 'pending',
                    'offered_at' => now(),
                    'accept_deadline' => $deadline,
                    'attempt_number' => DocumentAssignment::where('official_document_id', $document->id)->count() + 1,
                ]);
                
                $created[] = $assignment;
                
                // TODO: Send notification to partner via FCM/email
            }
        });
        
        return ['success' => true, 'offers_sent' => count($created), 'deadline' => $deadline];
    }
    
    /**
     * Get eligible partners for a document (sorted by suitability score)
     *
     * @param OfficialDocument $document
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function eligiblePartners(OfficialDocument $document)
    {
        $minQuality = config('offers.min_quality_score', 3.5);
        $minSla = config('offers.min_sla_score', 3.5);
        $maxConcurrent = config('offers.max_concurrent_assignments', 5);
        
        return PartnerProfile::query()
            ->whereHas('credentials', function ($q) use ($document) {
                $q->where('government_entity_id', $document->government_entity_id)
                  ->where('document_type', $document->document_type)
                  ->where('valid_until', '>=', now());
            })
            // Check availability
            ->where(function ($q) {
                $q->where('on_vacation', false)
                  ->orWhere(function ($q2) {
                      $q2->where('on_vacation', true)
                         ->where('vacation_until', '<', now());
                  });
            })
            // Check metrics (if exist)
            ->leftJoin('partner_metrics', 'partner_profiles.id', '=', 'partner_metrics.partner_profile_id')
            ->where(function ($q) use ($minQuality, $minSla) {
                $q->whereNull('partner_metrics.quality_score') // New partners
                  ->orWhere(function ($q2) use ($minQuality, $minSla) {
                      $q2->where('partner_metrics.quality_score', '>=', $minQuality)
                         ->where('partner_metrics.sla_score', '>=', $minSla);
                  });
            })
            // Check concurrent assignments limit
            ->whereDoesntHave('documentAssignments', function ($q) use ($maxConcurrent) {
                $q->whereIn('status', ['pending', 'accepted', 'in_review'])
                  ->havingRaw('COUNT(*) >= ?', [$maxConcurrent]);
            })
            // Exclude partners with existing pending offer for this document
            ->whereDoesntHave('documentAssignments', function ($q) use ($document) {
                $q->where('official_document_id', $document->id)
                  ->where('status', 'pending')
                  ->where('accept_deadline', '>', now());
            })
            ->select('partner_profiles.*')
            ->selectRaw($this->buildScoreSQL() . ' as suitability_score')
            ->orderByDesc('suitability_score')
            ->get();
    }
    
    /**
     * Build SQL for suitability scoring
     *
     * @return string
     */
    protected function buildScoreSQL(): string
    {
        $weights = config('offers.scoring_weights', [
            'quality_score' => 0.4,
            'sla_score' => 0.3,
            'accept_speed' => 0.2,
            'completion_rate' => 0.1,
        ]);
        
        return "
            COALESCE(
                (partner_metrics.quality_score * {$weights['quality_score']}) +
                (partner_metrics.sla_score * {$weights['sla_score']}) +
                (CASE 
                    WHEN partner_metrics.avg_accept_minutes > 0 
                    THEN (60.0 / partner_metrics.avg_accept_minutes) * {$weights['accept_speed']} 
                    ELSE 0 
                END) +
                (CASE 
                    WHEN (partner_metrics.jobs_completed + partner_metrics.jobs_rejected + partner_metrics.jobs_expired) > 0 
                    THEN (partner_metrics.jobs_completed::float / 
                          (partner_metrics.jobs_completed + partner_metrics.jobs_rejected + partner_metrics.jobs_expired)) * 5 * {$weights['completion_rate']}
                    ELSE 0 
                END),
                2.5
            )
        ";
    }
    
    /**
     * Expire pending offers that exceeded deadline
     *
     * @return int Number of expired offers
     */
    public function expirePendingOffers(): int
    {
        $expired = DocumentAssignment::where('status', 'pending')
            ->where('accept_deadline', '<', now())
            ->get();
        
        $count = 0;
        foreach ($expired as $assignment) {
            $assignment->update(['status' => 'expired']);
            $count++;
            
            // Update partner metrics
            if ($metrics = $assignment->partnerProfile->metrics) {
                $metrics->increment('jobs_expired');
            }
            
            Log::info('Assignment offer expired', [
                'assignment_id' => $assignment->id,
                'document_id' => $assignment->official_document_id,
                'partner_id' => $assignment->partner_profile_id,
            ]);
        }
        
        return $count;
    }
}
