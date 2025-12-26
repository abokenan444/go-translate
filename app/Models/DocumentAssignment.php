<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentAssignment extends Model
{
    protected $fillable = [
        'document_id',
        'document_type',
        'partner_id',
        'offer_group_id',
        'priority_rank',
        'attempt_no',
        'status',
        'offered_at',
        'expires_at',
        'responded_at',
        'accepted_at',
        'started_at',
        'completed_at',
        'reason',
        'estimated_duration_hours',
        'actual_duration_hours',
        'reviewer_notes',
    ];

    protected $casts = [
        'offered_at' => 'datetime',
        'expires_at' => 'datetime',
        'responded_at' => 'datetime',
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_duration_hours' => 'decimal:2',
        'actual_duration_hours' => 'decimal:2',
    ];

    /**
     * Status constants
     */
    const STATUS_OFFERED = 'offered';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_TIMED_OUT = 'timed_out';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_LOST = 'lost';

    /**
     * Get the document (OfficialDocument)
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(OfficialDocument::class, 'document_id');
    }

    /**
     * Get the assigned partner
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Scope for active offers
     */
    public function scopeOffered($query)
    {
        return $query->where('status', self::STATUS_OFFERED);
    }

    /**
     * Scope for accepted assignments
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /**
     * Scope for expired offers
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_OFFERED)
                     ->whereNotNull('expires_at')
                     ->where('expires_at', '<', now());
    }

    /**
     * Scope for a specific offer group
     */
    public function scopeInOfferGroup($query, string $groupId)
    {
        return $query->where('offer_group_id', $groupId);
    }

    /**
     * Check if offer is still valid
     */
    public function isOfferValid(): bool
    {
        return $this->status === self::STATUS_OFFERED 
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if assignment is active (accepted but not completed)
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isExpired(): bool
    {
        return $this->status === 'offered' 
            && $this->expires_at 
            && $this->expires_at->isPast();
    }

    /**
     * Get time remaining for offer
     */
    public function getTimeRemainingAttribute(): ?int
    {
        if (!$this->expires_at || $this->status !== self::STATUS_OFFERED) {
            return null;
        }
        
        return max(0, now()->diffInMinutes($this->expires_at, false));
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_OFFERED => 'Pending Response',
            self::STATUS_ACCEPTED => 'In Progress',
            self::STATUS_REJECTED => 'Declined',
            self::STATUS_TIMED_OUT => 'Expired',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_LOST => 'Assigned to Another',
            default => ucfirst($this->status ?? 'Unknown'),
        };
    }

    public function timeRemaining(): ?int
    {
        if ($this->status !== 'offered' || !$this->expires_at) {
            return null;
        }
        
        return max(0, now()->diffInMinutes($this->expires_at, false));
    }
}
