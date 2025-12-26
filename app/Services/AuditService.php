<?php

namespace App\Services;

use App\Models\AuditEvent;
use Illuminate\Http\Request;

class AuditService
{
    public function log(
        string $actorType,
        ?int $actorId,
        string $eventType,
        string $subjectType,
        int $subjectId,
        array $metadata = [],
        ?Request $request = null
    ): void {
        AuditEvent::create([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'event_type' => $eventType,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'metadata' => $metadata ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    public function logSystemEvent(string $eventType, string $subjectType, int $subjectId, array $metadata = []): void
    {
        $this->log('system', null, $eventType, $subjectType, $subjectId, $metadata);
    }

    public function logPartnerEvent(int $partnerId, string $eventType, string $subjectType, int $subjectId, array $metadata = [], ?Request $request = null): void
    {
        $this->log('partner', $partnerId, $eventType, $subjectType, $subjectId, $metadata, $request);
    }

    public function logUserEvent(int $userId, string $eventType, string $subjectType, int $subjectId, array $metadata = [], ?Request $request = null): void
    {
        $this->log('user', $userId, $eventType, $subjectType, $subjectId, $metadata, $request);
    }
}
