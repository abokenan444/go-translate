<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRevocation extends Model
{
    protected $table = 'certificate_revocations';

    protected $fillable = [
        'certificate_id',
        'action', // 'revoked' or 'frozen'
        'reason',
        'legal_reference',
        'effective_from',
        'ledger_event_id',
        'jurisdiction_country',
        'jurisdiction_purpose',
        'legal_basis_code',
        'receipt_path',
        'authority_reference_id', // External authority reference
        'authority_jurisdiction', // External authority name
    ];

    protected $casts = [
        'effective_from' => 'datetime',
    ];

    /**
     * Get the certificate that was revoked/frozen
     */
    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }

    /**
     * Get the ledger event for this revocation
     */
    public function ledgerEvent()
    {
        return $this->belongsTo(DecisionLedgerEvent::class, 'ledger_event_id');
    }

    /**
     * Check if this is a permanent revocation
     */
    public function isRevoked()
    {
        return $this->action === 'revoked';
    }

    /**
     * Check if this is a temporary freeze
     */
    public function isFrozen()
    {
        return $this->action === 'frozen';
    }
}
