<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentAssignment;
use App\Models\Partner;
use App\Notifications\AssignmentLostNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssignmentService
{
    public function __construct(
        private PartnerEligibilityService $eligibility,
        private AuditService $audit
    ) {}

    /**
     * Offer document to multiple partners in parallel
     * This is the main entry point for auto-assignment
     *
     * @param Document $document
     * @return void
     */
    public function offerParallel(Document $document): void
    {
        $parallelCount = max(1, (int) config('ct.parallel_offers', 2));
        $ttlMinutes = (int) config('ct.assignment_ttl_minutes', 60);

        // Find eligible partners
        $candidates = $this->eligibility->eligiblePartners($document, 20);
        
        if ($candidates->isEmpty()) {
            // No candidates found - escalate later
            $this->audit->logSystem(
                'assignment.no_candidates',
                Document::class,
                $document->id,
                [
                    'jurisdiction_country' => $document->jurisdiction_country,
                    'source_lang' => $document->source_lang,
                    'target_lang' => $document->target_lang,
                    'document_type' => $document->document_type,
                ]
            );
            
            // Mark for admin review
            $document->status = 'awaiting_reviewer';
            $document->save();
            return;
        }

        // Take top N candidates
        $selected = $candidates->take($parallelCount)->values();
        
        $offerGroupId = (string) Str::uuid();
        $attemptNo = max(1, (int) $document->assignment_attempts + 1);

        $now = Carbon::now();
        $expires = $now->copy()->addMinutes($ttlMinutes);

        // Create parallel offers in a transaction
        DB::transaction(function () use ($document, $selected, $offerGroupId, $attemptNo, $now, $expires) {
            foreach ($selected as $idx => $partner) {
                DocumentAssignment::create([
                    'document_id' => $document->id,
                    'partner_id' => $partner->id,
                    'offer_group_id' => $offerGroupId,
                    'priority_rank' => $idx + 1,
                    'attempt_no' => $attemptNo,
                    'status' => 'offered',
                    'offered_at' => $now,
                    'expires_at' => $expires,
                ]);
            }

            $document->assignment_attempts = $attemptNo;
            $document->status = 'assigned';
            $document->save();
        });

        $this->audit->logSystem(
            'assignment.offered_parallel',
            Document::class,
            $document->id,
            [
                'offer_group_id' => $offerGroupId,
                'attempt_no' => $attemptNo,
                'partners' => $selected->pluck('id')->all(),
                'partner_count' => $selected->count(),
                'expires_at' => $expires->toIso8601String(),
            ]
        );
    }

    /**
     * Partner accepts an offered assignment
     * Race-safe: first accept wins by locking document row
     *
     * @param int $assignmentId
     * @param int $partnerId
     * @param \Illuminate\Http\Request|null $request
     * @return array
     */
    public function acceptAssignment(int $assignmentId, int $partnerId, ?\Illuminate\Http\Request $request = null): array
    {
        return DB::transaction(function () use ($assignmentId, $partnerId, $request) {
            /** @var DocumentAssignment $assignment */
            $assignment = DocumentAssignment::query()->lockForUpdate()->findOrFail($assignmentId);

            // Authorization check
            if ((int)$assignment->partner_id !== (int)$partnerId) {
                abort(403, 'Unauthorized');
            }

            // Can only accept if still offered
            if ($assignment->status !== 'offered') {
                return [
                    'status' => 'not_offered',
                    'assignment_status' => $assignment->status,
                    'message' => 'This assignment is no longer available.'
                ];
            }

            // Check if expired
            if ($assignment->isExpired()) {
                $assignment->status = 'timed_out';
                $assignment->responded_at = now();
                $assignment->save();
                
                return [
                    'status' => 'expired',
                    'message' => 'This assignment has expired.'
                ];
            }

            /** @var Document $document */
            $document = Document::query()->lockForUpdate()->findOrFail($assignment->document_id);

            // Check if document already locked by another assignment
            if (!is_null($document->locked_assignment_id)) {
                // This partner lost the race
                $assignment->status = 'lost';
                $assignment->responded_at = now();
                $assignment->save();

                $this->audit->logPartner(
                    $partnerId,
                    'assignment.accept_lost',
                    Document::class,
                    $document->id,
                    [
                        'assignment_id' => $assignment->id,
                        'locked_assignment_id' => $document->locked_assignment_id,
                    ],
                    $request
                );

                // Notify partner they lost
                if ($assignment->partner) {
                    $assignment->partner->notify(new AssignmentLostNotification($document->id));
                }

                return [
                    'status' => 'lost',
                    'locked_assignment_id' => $document->locked_assignment_id,
                    'message' => 'Another reviewer was selected.'
                ];
            }

            // Winner! Lock the document
            $document->locked_assignment_id = $assignment->id;
            $document->reviewer_partner_id = $partnerId;
            $document->status = 'in_review';
            $document->save();

            $assignment->status = 'accepted';
            $assignment->accepted_at = now();
            $assignment->responded_at = now();
            $assignment->save();

            // Cancel other parallel offers in same group
            DocumentAssignment::query()
                ->where('offer_group_id', $assignment->offer_group_id)
                ->where('id', '!=', $assignment->id)
                ->where('status', 'offered')
                ->update([
                    'status' => 'cancelled',
                    'responded_at' => now(),
                    'reason' => 'Another partner accepted',
                ]);

            // Notify cancelled partners
            $cancelledAssignments = DocumentAssignment::query()
                ->where('offer_group_id', $assignment->offer_group_id)
                ->where('id', '!=', $assignment->id)
                ->where('status', 'cancelled')
                ->with('partner')
                ->get();

            foreach ($cancelledAssignments as $cancelled) {
                if ($cancelled->partner) {
                    $cancelled->partner->notify(new AssignmentLostNotification($document->id));
                }
            }

            $this->audit->logPartner(
                $partnerId,
                'assignment.accepted',
                Document::class,
                $document->id,
                [
                    'assignment_id' => $assignment->id,
                    'offer_group_id' => $assignment->offer_group_id,
                    'cancelled_count' => $cancelledAssignments->count(),
                ],
                $request
            );

            return [
                'status' => 'accepted',
                'document_id' => $document->id,
                'assignment_id' => $assignment->id,
                'message' => 'Assignment accepted successfully. You are now the reviewer.'
            ];
        });
    }

    /**
     * Partner rejects an offered assignment
     *
     * @param int $assignmentId
     * @param int $partnerId
     * @param string $reason
     * @param \Illuminate\Http\Request|null $request
     * @return array
     */
    public function rejectAssignment(int $assignmentId, int $partnerId, string $reason = 'other', ?\Illuminate\Http\Request $request = null): array
    {
        return DB::transaction(function () use ($assignmentId, $partnerId, $reason, $request) {
            $assignment = DocumentAssignment::query()->lockForUpdate()->findOrFail($assignmentId);

            // Authorization check
            if ((int)$assignment->partner_id !== (int)$partnerId) {
                abort(403, 'Unauthorized');
            }

            // Can only reject if still offered
            if ($assignment->status !== 'offered') {
                return [
                    'status' => 'not_offered',
                    'assignment_status' => $assignment->status,
                    'message' => 'This assignment cannot be rejected.'
                ];
            }

            $assignment->status = 'rejected';
            $assignment->reason = $reason;
            $assignment->responded_at = now();
            $assignment->save();

            $this->audit->logPartner(
                $partnerId,
                'assignment.rejected',
                Document::class,
                $assignment->document_id,
                [
                    'assignment_id' => $assignment->id,
                    'offer_group_id' => $assignment->offer_group_id,
                    'reason' => $reason,
                ],
                $request
            );

            return [
                'status' => 'rejected',
                'assignment_id' => $assignment->id,
                'message' => 'Assignment rejected. The system will reassign it.'
            ];
        });
    }

    /**
     * Mark assignment as completed
     */
    public function completeAssignment(int $assignmentId, int $partnerId): array
    {
        return DB::transaction(function () use ($assignmentId, $partnerId) {
            $assignment = DocumentAssignment::query()->lockForUpdate()->findOrFail($assignmentId);

            if ((int)$assignment->partner_id !== (int)$partnerId) {
                abort(403);
            }

            if ($assignment->status !== 'accepted') {
                return [
                    'status' => 'invalid_state',
                    'message' => 'Assignment is not in accepted state.'
                ];
            }

            $assignment->status = 'completed';
            $assignment->completed_at = now();
            $assignment->save();

            $this->audit->logPartner(
                $partnerId,
                'assignment.completed',
                Document::class,
                $assignment->document_id,
                ['assignment_id' => $assignment->id]
            );

            return [
                'status' => 'completed',
                'assignment_id' => $assignment->id,
                'message' => 'Assignment completed successfully.'
            ];
        });
    }
}
