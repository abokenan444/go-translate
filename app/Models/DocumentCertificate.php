<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'cert_id',
        'document_id',
        'original_hash',
        'translated_hash',
        'status',
        'issued_at',
        'expires_at',
        'qr_code_path',
        'metadata',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the document that owns the certificate.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(OfficialDocument::class, 'document_id');
    }

    /**
     * Check if certificate is valid.
     */
    public function isValid(): bool
    {
        if ($this->status !== 'valid') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if certificate is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Revoke the certificate.
     */
    public function revoke(?string $reason = null): void
    {
        $metadata = $this->metadata ?? [];
        $metadata['revoked_at'] = now()->toIso8601String();
        
        if ($reason) {
            $metadata['revoke_reason'] = $reason;
        }

        $this->update([
            'status' => 'revoked',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return url('/verify/' . $this->cert_id);
    }

    /**
     * Get API verification URL.
     */
    public function getApiVerificationUrlAttribute(): string
    {
        return url('/api/certificates/' . $this->cert_id);
    }

    /**
     * Scope for filtering valid certificates.
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'valid');
    }

    /**
     * Scope for filtering revoked certificates.
     */
    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }

    /**
     * Scope for filtering expired certificates.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Generate unique certificate ID.
     */
    public static function generateCertId(): string
    {
        $date = now()->format('Y-m');
        $random = str_pad((string) mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        
        return "CT-{$date}-{$random}";
    }
}
