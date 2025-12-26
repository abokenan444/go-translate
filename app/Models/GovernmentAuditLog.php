<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernmentAuditLog extends Model
{
    protected $table = 'government_audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'verification_id',
        'action',
        'performed_by',
        'notes',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function verification(): BelongsTo
    {
        return $this->belongsTo(GovernmentVerification::class, 'verification_id');
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function getActionLabel(): string
    {
        return match ($this->action) {
            'submitted' => '📤 Submitted',
            'review_started' => '🔍 Review Started',
            'approved' => '✅ Approved',
            'rejected' => '❌ Rejected',
            'info_requested' => '❓ Info Requested',
            'info_provided' => '📝 Info Provided',
            'updated' => '✏️ Updated',
            'suspended' => '⚠️ Suspended',
            default => $this->action,
        };
    }
}
