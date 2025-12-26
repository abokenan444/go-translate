<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\DocumentAssignment;
use App\Notifications\NewAssignmentOfferedNotification;
use App\Services\AssignmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OfferAssignmentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $documentId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * Execute the job.
     */
    public function handle(AssignmentService $service): void
    {
        $document = Document::find($this->documentId);

        if (!$document) {
            \Log::warning("OfferAssignmentsJob: Document {$this->documentId} not found");
            return;
        }

        // Only offer when ready
        if (!in_array($document->status, ['awaiting_reviewer', 'assigned'], true)) {
            \Log::info("OfferAssignmentsJob: Document {$this->documentId} not in correct status: {$document->status}");
            return;
        }

        // Offer to parallel partners
        $service->offerParallel($document);

        // Send notifications to offered partners
        $offers = DocumentAssignment::query()
            ->where('document_id', $document->id)
            ->where('attempt_no', $document->assignment_attempts)
            ->where('status', 'offered')
            ->with('partner')
            ->get();

        foreach ($offers as $offer) {
            if ($offer->partner && $offer->partner->email) {
                try {
                    $offer->partner->notify(new NewAssignmentOfferedNotification($offer));
                    
                    \Log::info("Sent assignment notification", [
                        'partner_id' => $offer->partner_id,
                        'assignment_id' => $offer->id,
                        'document_id' => $document->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Failed to send assignment notification", [
                        'partner_id' => $offer->partner_id,
                        'assignment_id' => $offer->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['assignment', 'document:' . $this->documentId];
    }
}
