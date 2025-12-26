<?php

namespace App\Observers;

use App\Models\Document;
use App\Models\EvidenceChain;
use Illuminate\Support\Facades\Auth;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void
    {
        $this->addToEvidenceChain($document, 'document_created', [
            'title' => $document->title,
            'source_language' => $document->source_language,
            'target_language' => $document->target_language,
        ]);
    }

    /**
     * Handle the Document "updated" event.
     */
    public function updated(Document $document): void
    {
        $changes = $document->getChanges();
        
        if (!empty($changes)) {
            $this->addToEvidenceChain($document, 'document_updated', [
                'changes' => array_keys($changes),
                'old_status' => $document->getOriginal('status'),
                'new_status' => $document->status,
            ]);
        }
    }

    /**
     * Handle the Document "deleted" event.
     */
    public function deleted(Document $document): void
    {
        $this->addToEvidenceChain($document, 'document_deleted', [
            'reference_number' => $document->reference_number,
            'status' => $document->status,
        ]);
    }

    /**
     * Add event to evidence chain
     */
    private function addToEvidenceChain(Document $document, string $eventType, array $eventData): void
    {
        // Get previous hash
        $previousEvent = EvidenceChain::where('document_id', $document->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $previousHash = $previousEvent ? $previousEvent->hash : null;

        $event = EvidenceChain::create([
            'document_id' => $document->id,
            'event_type' => $eventType,
            'event_data' => $eventData,
            'actor_type' => 'user',
            'actor_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => [
                'session_id' => session()->getId(),
                'request_id' => request()->header('X-Request-ID'),
            ],
            'previous_hash' => $previousHash,
        ]);

        // Calculate and update hash
        $event->update(['hash' => $event->calculateHash()]);
    }
}
