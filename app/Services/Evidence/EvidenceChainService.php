<?php

namespace App\Services\Evidence;

use App\Models\AuditEvent;
use Illuminate\Support\Facades\Log;

class EvidenceChainService
{
    /**
     * Record an audit event with hash chain
     *
     * @param string $action
     * @param array $data
     * @param int|null $userId
     * @param string|null $auditable_type
     * @param int|null $auditable_id
     * @return AuditEvent
     */
    public function record(
        string $action,
        array $data,
        ?int $userId = null,
        ?string $auditable_type = null,
        ?int $auditable_id = null
    ): AuditEvent {
        // Get previous hash
        $previousEvent = AuditEvent::latest('id')->first();
        $prevHash = $previousEvent?->hash ?? '0000000000000000000000000000000000000000000000000000000000000000';
        
        // Create event
        $event = AuditEvent::create([
            'action' => $action,
            'data' => $data,
            'user_id' => $userId ?? auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'auditable_type' => $auditable_type,
            'auditable_id' => $auditable_id,
            'prev_hash' => $prevHash,
            'hash' => '', // Will be calculated below
        ]);
        
        // Calculate hash
        $hash = $this->calculateHash($event);
        $event->update(['hash' => $hash]);
        
        return $event;
    }
    
    /**
     * Calculate hash for an audit event
     *
     * @param AuditEvent $event
     * @return string
     */
    protected function calculateHash(AuditEvent $event): string
    {
        $payload = json_encode([
            'id' => $event->id,
            'action' => $event->action,
            'data' => $event->data,
            'user_id' => $event->user_id,
            'ip_address' => $event->ip_address,
            'timestamp' => $event->created_at->toIso8601String(),
            'prev_hash' => $event->prev_hash,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        return hash('sha256', $payload);
    }
    
    /**
     * Verify the integrity of the evidence chain
     *
     * @param int|null $startId Start verification from this event ID
     * @param int|null $endId End verification at this event ID
     * @return array
     */
    public function verifyChain(?int $startId = null, ?int $endId = null): array
    {
        $query = AuditEvent::orderBy('id');
        
        if ($startId) {
            $query->where('id', '>=', $startId);
        }
        
        if ($endId) {
            $query->where('id', '<=', $endId);
        }
        
        $events = $query->get();
        
        if ($events->isEmpty()) {
            return ['valid' => true, 'checked' => 0, 'errors' => []];
        }
        
        $errors = [];
        $previousHash = null;
        
        foreach ($events as $event) {
            // Check if prev_hash matches previous event's hash
            if ($previousHash !== null && $event->prev_hash !== $previousHash) {
                $errors[] = [
                    'event_id' => $event->id,
                    'error' => 'prev_hash_mismatch',
                    'expected' => $previousHash,
                    'actual' => $event->prev_hash,
                ];
            }
            
            // Verify current hash
            $calculatedHash = $this->calculateHash($event);
            if ($event->hash !== $calculatedHash) {
                $errors[] = [
                    'event_id' => $event->id,
                    'error' => 'hash_mismatch',
                    'expected' => $calculatedHash,
                    'actual' => $event->hash,
                ];
            }
            
            $previousHash = $event->hash;
        }
        
        if (!empty($errors)) {
            Log::warning('Evidence chain verification failed', [
                'errors_count' => count($errors),
                'errors' => $errors,
            ]);
        }
        
        return [
            'valid' => empty($errors),
            'checked' => $events->count(),
            'errors' => $errors,
        ];
    }
    
    /**
     * Get chain statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $total = AuditEvent::count();
        $withHash = AuditEvent::whereNotNull('hash')->count();
        
        return [
            'total_events' => $total,
            'events_with_hash' => $withHash,
            'coverage_percentage' => $total > 0 ? round(($withHash / $total) * 100, 2) : 0,
        ];
    }
}
