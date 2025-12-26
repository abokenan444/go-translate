<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CtsCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'certificate_id',
        'user_id',
        'project_id',
        'translation_id',
        'risk_assessment_id',
        'partner_id',
        'cts_level',
        'cultural_impact_score',
        'source_language',
        'target_language',
        'source_country',
        'target_country',
        'use_case',
        'domain',
        'original_document_path',
        'translated_document_path',
        'certificate_pdf_path',
        'document_hash',
        'translation_hash',
        'qr_code_path',
        'metadata',
        'issued_at',
        'expires_at',
        'is_verified',
        'is_public',
    ];

    protected $casts = [
        'metadata' => 'array',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * Get the user that owns the certificate
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project associated with the certificate
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the risk assessment
     */
    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class);
    }

    /**
     * Get the partner (certified translator)
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get verification records
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(CtsPartner::class, 'partner_certificates')
                    ->withPivot('partner_seal_applied', 'sealed_at')
                    ->withTimestamps();
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(VerificationRegistry::class, 'cts_certificate_id');
    }

    /**
     * Generate unique certificate ID
     */
    public static function generateCertificateId(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        $count = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1;

        return sprintf('CT-CTS-%d-%s-%08d', $year, $month, $count);
    }

    /**
     * Check if certificate is still valid
     */
    public function isValid(): bool
    {
        if (!$this->is_verified) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get verification URL
     */
    public function getVerificationUrlAttribute(): string
    {
        return url("/verify/{$this->certificate_id}");
    }

    /**
     * Get CTS level badge color
     */
    public function getCtsLevelBadgeColorAttribute(): string
    {
        return match($this->cts_level) {
            'CTS-A' => 'success',
            'CTS-B' => 'info',
            'CTS-C' => 'warning',
            default => 'gray',
        };
    }
}
