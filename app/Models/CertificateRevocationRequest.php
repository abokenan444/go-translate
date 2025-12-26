<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateRevocationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_id',
        'action',
        'requested_by',
        'requested_by_role',
        'requested_at',
        'approved_by',
        'approved_by_role',
        'approved_at',
        'reason',
        'legal_reference',
        'jurisdiction_country',
        'jurisdiction_purpose',
        'legal_basis_code',
        'authority_entity_id',
        'status',
        'rejection_reason',
        'rejected_at',
        'revocation_id'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    // Relationships
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(DocumentCertificate::class, 'certificate_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function authorityEntity(): BelongsTo
    {
        return $this->belongsTo(GovEntity::class, 'authority_entity_id');
    }

    public function revocation(): BelongsTo
    {
        return $this->belongsTo(CertificateRevocation::class, 'revocation_id');
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function canBeApprovedBy(User $user): bool
    {
        // Must be pending
        if (!$this->isPending()) {
            return false;
        }

        // Must be supervisor
        if ($user->account_type !== 'gov_authority_supervisor') {
            return false;
        }

        // Cannot approve own request
        if ($this->requested_by === $user->id) {
            return false;
        }

        return true;
    }
}
