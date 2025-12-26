<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerCredential extends Model
{
    protected $fillable = [
        'partner_id',
        'license_number',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'license_file_path',
        'id_document_path',
        'verification_status',
        'verified_by',
        'verified_at',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isValid(): bool
    {
        return $this->verification_status === 'approved' && !$this->isExpired();
    }
}
