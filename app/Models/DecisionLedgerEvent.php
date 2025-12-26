<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DecisionLedgerEvent extends Model
{
    protected $table = 'decision_ledger_events';

    public $timestamps = false; // Only created_at (immutable)

    protected $fillable = [
        'event_uuid',
        'event_type',
        'actor_user_id',
        'actor_role',
        'actor_ip',
        'document_id',
        'certificate_id',
        'gov_entity_id',
        'dispute_id',
        'payload',
        'prev_hash',
        'hash',
        'created_at'
    ];

    /**
     * Boot model - Enforce append-only (no updates/deletes)
     */
    protected static function booted()
    {
        static::updating(function () {
            throw new \Exception('Decision Ledger is append-only. Updates are not allowed.');
        });

        static::deleting(function () {
            throw new \Exception('Decision Ledger is append-only. Deletions are not allowed.');
        });
    }

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime'
    ];

    // Prevent updates/deletes
    public function update(array $attributes = [], array $options = [])
    {
        throw new \Exception('Decision ledger events are immutable');
    }

    public function delete()
    {
        throw new \Exception('Decision ledger events cannot be deleted');
    }

    public static function recordEvent(
        string $eventType,
        int $actorUserId,
        string $actorRole,
        array $payload,
        ?int $documentId = null,
        ?int $certificateId = null,
        ?int $govEntityId = null,
        ?int $disputeId = null
    ): self {
        $lastEvent = self::orderBy('id', 'desc')->first();
        $prevHash = $lastEvent ? $lastEvent->hash : null;

        $event = new self();
        $event->event_uuid = \Str::uuid();
        $event->event_type = $eventType;
        $event->actor_user_id = $actorUserId;
        $event->actor_role = $actorRole;
        $event->actor_ip = request()->ip();
        $event->document_id = $documentId;
        $event->certificate_id = $certificateId;
        $event->gov_entity_id = $govEntityId;
        $event->dispute_id = $disputeId;
        $event->payload = $payload;
        $event->prev_hash = $prevHash;
        $event->created_at = now();

        // Calculate hash
        $event->hash = self::calculateHash($event, $prevHash);
        $event->save();

        return $event;
    }

    protected static function calculateHash(self $event, ?string $prevHash): string
    {
        $data = [
            'prev_hash' => $prevHash,
            'event_type' => $event->event_type,
            'created_at' => $event->created_at->toIso8601String(),
            'actor_user_id' => $event->actor_user_id,
            'payload' => json_encode($event->payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ];

        $canonical = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return hash('sha256', $canonical);
    }

    public function verifyHash(): bool
    {
        $calculatedHash = self::calculateHash($this, $this->prev_hash);
        return $calculatedHash === $this->hash;
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(OfficialDocument::class, 'document_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
