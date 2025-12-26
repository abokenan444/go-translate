<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DigitalSignature extends Model
{
    protected $fillable = [
        'signable_type',
        'signable_id',
        'signature_value',
        'algorithm',
        'public_key',
        'certificate_chain',
        'hash_algorithm',
        'signed_data_hash',
        'signer_id',
        'signer_role',
        'signed_at',
        'expires_at',
        'is_valid',
        'verified_at',
        'metadata',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_valid' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the signable model
     */
    public function signable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the signer user
     */
    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signer_id');
    }

    /**
     * Check if signature is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if signature is currently valid
     */
    public function isCurrentlyValid(): bool
    {
        return $this->is_valid && !$this->isExpired();
    }
}
