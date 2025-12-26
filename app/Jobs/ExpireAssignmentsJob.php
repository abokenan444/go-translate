<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\DocumentAssignment;
use App\Notifications\AssignmentTimedOutNotification;
use App\Services\AuditService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireAssignmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(AuditService $audit): void
    {
        $now = now();
        $maxAttempts = (int) config('ct.max_assignment_attempts', 7);

        // Step 1: Find and expire timed-out assignments
        $expiredOffers = DocumentAssignment::query()
            ->where('status', 'offered')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->limit(500)
            ->get();

        \Log::info("ExpireAssignmentsJob: Found {$expiredOffers->count()} expired assignments");

        foreach ($expiredOffers as $offer) {
            $offer->status = 'timed_out';
            $offer->responded_at = $now;
            $offer->save();

            $audit->logSystem(
                'assignment.timed_out',
                Document::class,
                $offer->document_id,
                [
                    'assignment_id' => $offer->id,
                    'offer_group_id' => $offer->offer_group_id,
                    'partner_id' => $offer->partner_id,
                ]
            );

            // Notify partner (optional)
            if ($offer->partner && $offer->partner->email) {
                try {
                    $offer->partner->notify(new AssignmentTimedOutNotification($offer));
                } catch (\Exception $e) {
                    \Log::error("Failed to send timeout notification", [
                        'partner_id' => $offer->partner_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Step 2: Find documents that need reassignment
        // Get document IDs that have recent timed out/rejected assignments
        $docIds = DocumentAssignment::query()
            ->whereIn('status', ['timed_out', 'rejected', 'cancelled'])
            ->where('updated_at', '>=', $now->copy()->subMinutes(120))
            ->distinct()
            ->pluck('document_id');

        \Log::info("ExpireAssignmentsJob: Checking {$docIds->count()} documents for reassignment");

        foreach ($docIds as $docId) {
            $doc = Document::find($docId);
            if (!$doc) {
                continue;
            }

            // Skip if already locked
            if (!is_null($doc->locked_assignment_id)) {
                continue;
            }

            // Check if there are still active offered assignments
            $activeOffered = DocumentAssignment::query()
                ->where('document_id', $doc->id)
                ->where('attempt_no', $doc->assignment_attempts)
                ->where('status', 'offered')
                ->exists();

            if ($activeOffered) {
                // Still waiting for response, don't reassign yet
                continue;
            }

            // All offers in current attempt have been responded to (or timed out)
            // Time to reassign

            if ($doc->assignment_attempts < $maxAttempts) {
                \Log::info("ExpireAssignmentsJob: Reassigning document {$doc->id}, attempt {$doc->assignment_attempts}/{$maxAttempts}");
                
                // Dispatch new offer job
                OfferAssignmentsJob::dispatch($doc->id);
            } else {
                // Max attempts reached - escalate to admin
                \Log::warning("ExpireAssignmentsJob: Document {$doc->id} reached max attempts ({$maxAttempts}), escalating to admin");
                
                $audit->logSystem(
                    'assignment.escalated_admin',
                    Document::class,
                    $doc->id,
                    [
                        'attempts' => $doc->assignment_attempts,
                        'max_attempts' => $maxAttempts,
                        'reason' => 'No partners available or all rejected/timed out',
                    ]
                );

                $doc->status = 'escalated';
                $doc->save();

                // TODO: Notify admins about escalation
                // AdminEscalationNotification::send($doc);
            }
        }

        \Log::info("ExpireAssignmentsJob completed");
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['assignment', 'expire', 'scheduled'];
    }
}
