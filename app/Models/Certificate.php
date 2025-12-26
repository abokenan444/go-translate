<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $table = 'document_certificates';
    
    protected $fillable = [
        'cert_id', 'document_id', 'original_hash', 'translated_hash',
        'status', 'issued_at', 'expires_at', 'qr_code_path', 'metadata'
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];
    
    /**
     * Get the revocation record for this certificate
     */
    public function revocation()
    {
        return $this->hasOne(\App\Models\CertificateRevocation::class, 'certificate_id', 'id');
    }
    
    /**
     * Get the document this certificate belongs to
     */
    public function document()
    {
        return $this->belongsTo(\App\Models\OfficialDocument::class, 'document_id', 'id');
    }
    
    /**
     * Get user relationship (if exists via document)
     */
    public function user()
    {
        return $this->hasOneThrough(
            \App\Models\User::class,
            \App\Models\OfficialDocument::class,
            'id', // Foreign key on official_documents
            'id', // Foreign key on users
            'document_id', // Local key on certificates
            'user_id' // Local key on official_documents
        );
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($cert) {
            // Only set if fields exist in table
            if (!isset($cert->cert_id)) {
                $cert->cert_id = 'CT-' . strtoupper(Str::random(12));
            }
        });
    }

    /**
     * Check if certificate is valid
     */
    public function isValid()
    {
        $notRevoked = !$this->revocation || $this->revocation->action !== 'revoked';
        $notExpired = !$this->expires_at || now()->lessThan($this->expires_at);
        return $this->status === 'valid' && $notRevoked && $notExpired;
    }
}
