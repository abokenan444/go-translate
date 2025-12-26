<?php

namespace App\Services;

use App\Models\DecisionLedgerEvent;
use Illuminate\Support\Facades\DB;

/**
 * Decision Ledger Service
 * Tamper-evident event recording system
 */
class DecisionLedgerService
{
    /**
     * Record any government decision in immutable ledger
     */
    public function record(
        string $eventType,
        int $actorUserId,
        string $actorRole,
        array $payload,
        ?int $documentId = null,
        ?int $certificateId = null,
        ?int $govEntityId = null,
        ?int $disputeId = null
    ): DecisionLedgerEvent {
        return DB::transaction(function () use (
            $eventType, $actorUserId, $actorRole, $payload,
            $documentId, $certificateId, $govEntityId, $disputeId
        ) {
            return DecisionLedgerEvent::recordEvent(
                $eventType,
                $actorUserId,
                $actorRole,
                $payload,
                $documentId,
                $certificateId,
                $govEntityId,
                $disputeId
            );
        });
    }

    /**
     * Verify hash chain integrity
     */
    public function verifyChainIntegrity(?int $fromId = null, ?int $toId = null): array
    {
        $query = DecisionLedgerEvent::orderBy('id', 'asc');
        
        if ($fromId) {
            $query->where('id', '>=', $fromId);
        }
        if ($toId) {
            $query->where('id', '<=', $toId);
        }

        $events = $query->get();
        $results = [
            'total_checked' => $events->count(),
            'valid' => 0,
            'invalid' => 0,
            'errors' => []
        ];

        $prevEvent = null;
        foreach ($events as $event) {
            // Check if prev_hash matches
            if ($prevEvent && $event->prev_hash !== $prevEvent->hash) {
                $results['invalid']++;
                $results['errors'][] = [
                    'event_id' => $event->id,
                    'error' => 'prev_hash mismatch',
                    'expected' => $prevEvent->hash,
                    'actual' => $event->prev_hash
                ];
                continue;
            }

            // Verify hash calculation
            if (!$event->verifyHash()) {
                $results['invalid']++;
                $results['errors'][] = [
                    'event_id' => $event->id,
                    'error' => 'hash verification failed'
                ];
                continue;
            }

            $results['valid']++;
            $prevEvent = $event;
        }

        $results['integrity'] = $results['invalid'] === 0 ? 'intact' : 'compromised';
        return $results;
    }

    /**
     * Get decision timeline for a document
     */
    public function getDocumentTimeline(int $documentId): array
    {
        $events = DecisionLedgerEvent::where('document_id', $documentId)
            ->orderBy('created_at', 'asc')
            ->with('actor')
            ->get();

        return $events->map(function ($event) {
            return [
                'timestamp' => $event->created_at,
                'event_type' => $event->event_type,
                'actor' => $event->actor?->name,
                'role' => $event->actor_role,
                'details' => $event->payload,
                'hash' => substr($event->hash, 0, 16) . '...'
            ];
        })->toArray();
    }

    /**
     * Get audit trail for certificate
     */
    public function getCertificateAuditTrail(int $certificateId): array
    {
        $events = DecisionLedgerEvent::where('certificate_id', $certificateId)
            ->orderBy('created_at', 'asc')
            ->with('actor')
            ->get();

        return [
            'certificate_id' => $certificateId,
            'events_count' => $events->count(),
            'timeline' => $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'timestamp' => $event->created_at->toIso8601String(),
                    'event_type' => $event->event_type,
                    'actor' => [
                        'id' => $event->actor_user_id,
                        'name' => $event->actor?->name,
                        'role' => $event->actor_role
                    ],
                    'payload' => $event->payload,
                    'hash' => $event->hash
                ];
            })->toArray()
        ];
    }

    /**
     * Export ledger for compliance/legal purposes
     */
    public function exportLedger(array $filters = []): array
    {
        $query = DecisionLedgerEvent::with('actor')->orderBy('id', 'asc');

        if (isset($filters['from_date'])) {
            $query->where('created_at', '>=', $filters['from_date']);
        }
        if (isset($filters['to_date'])) {
            $query->where('created_at', '<=', $filters['to_date']);
        }
        if (isset($filters['gov_entity_id'])) {
            $query->where('gov_entity_id', $filters['gov_entity_id']);
        }
        if (isset($filters['event_type'])) {
            $query->where('event_type', $filters['event_type']);
        }

        $events = $query->get();

        return [
            'export_timestamp' => now()->toIso8601String(),
            'filters_applied' => $filters,
            'total_events' => $events->count(),
            'integrity_check' => $this->verifyChainIntegrity(
                $events->first()?->id,
                $events->last()?->id
            ),
            'events' => $events->toArray()
        ];
    }
}
