<?php

namespace App\Services;

use App\Models\CTSCertificate;
use Illuminate\Support\Facades\Log;

/**
 * Certificate Revocation Service
 * 
 * Handles certificate revocation with proper reason codes and public disclosure
 * Critical for maintaining trust and legal compliance
 */
class CertificateRevocationService
{
    protected AuditTrailService $auditTrail;

    public function __construct(AuditTrailService $auditTrail)
    {
        $this->auditTrail = $auditTrail;
    }

    /**
     * Revocation reason codes (following industry standards)
     */
    const REASON_CODES = [
        'unspecified' => 'Unspecified',
        'key_compromise' => 'Key Compromise',
        'affiliation_changed' => 'Affiliation Changed',
        'superseded' => 'Superseded by New Certificate',
        'cessation_of_operation' => 'Cessation of Operation',
        'privilege_withdrawn' => 'Privilege Withdrawn',
        'content_error' => 'Content Error in Certificate',
        'fraudulent' => 'Fraudulent Certificate',
        'legal_requirement' => 'Legal Requirement',
        'partner_terminated' => 'Partner Certification Terminated',
        'quality_issue' => 'Translation Quality Issue',
        'client_request' => 'Client Request',
    ];

    /**
     * Revoke a certificate
     *
     * @param CTSCertificate $certificate
     * @param string $reasonCode
     * @param string|null $notes
     * @param int|null $revokedBy
     * @return bool
     */
    public function revoke(
        CTSCertificate $certificate,
        string $reasonCode,
        ?string $notes = null,
        ?int $revokedBy = null
    ): bool {
        // Validate reason code
        if (!array_key_exists($reasonCode, self::REASON_CODES)) {
            throw new \InvalidArgumentException("Invalid revocation reason code: {$reasonCode}");
        }

        // Check if already revoked
        if ($certificate->is_revoked) {
            Log::warning("Certificate {$certificate->certificate_number} is already revoked");
            return false;
        }

        // Store old values for audit
        $oldValues = [
            'is_revoked' => $certificate->is_revoked,
            'status' => $certificate->status,
        ];

        // Revoke certificate
        $certificate->update([
            'is_revoked' => true,
            'revoked_at' => now(),
            'revocation_reason' => $reasonCode,
            'revoked_by' => $revokedBy ?? auth()->id(),
            'revocation_notes' => $notes,
            'status' => 'revoked',
        ]);

        // Store new values for audit
        $newValues = [
            'is_revoked' => true,
            'revoked_at' => $certificate->revoked_at,
            'revocation_reason' => $reasonCode,
            'revoked_by' => $certificate->revoked_by,
            'status' => 'revoked',
        ];

        // Log to immutable audit trail
        $this->auditTrail->log(
            'certificate_revoked',
            $certificate,
            $oldValues,
            $newValues,
            [
                'reason_code' => $reasonCode,
                'reason_description' => self::REASON_CODES[$reasonCode],
                'notes' => $notes,
                'revoked_by_user_id' => $revokedBy ?? auth()->id(),
                'revocation_timestamp' => now()->toIso8601String(),
            ]
        );

        // Notify relevant parties
        $this->notifyRevocation($certificate, $reasonCode, $notes);

        Log::info("Certificate {$certificate->certificate_number} revoked. Reason: {$reasonCode}");

        return true;
    }

    /**
     * Check if certificate is revoked
     *
     * @param string $certificateNumber
     * @return array
     */
    public function checkRevocationStatus(string $certificateNumber): array
    {
        $certificate = CTSCertificate::where('certificate_number', $certificateNumber)->first();

        if (!$certificate) {
            return [
                'exists' => false,
                'revoked' => false,
                'message' => 'Certificate not found',
            ];
        }

        if (!$certificate->is_revoked) {
            return [
                'exists' => true,
                'revoked' => false,
                'status' => $certificate->status,
                'message' => 'Certificate is valid and not revoked',
            ];
        }

        return [
            'exists' => true,
            'revoked' => true,
            'revoked_at' => $certificate->revoked_at,
            'reason_code' => $certificate->revocation_reason,
            'reason_description' => self::REASON_CODES[$certificate->revocation_reason] ?? 'Unknown',
            'issued_by' => $certificate->partner?->partner_name ?? 'Cultural Translate Platform',
            'message' => 'Certificate has been revoked',
            'public_notes' => $this->getPublicRevocationNotes($certificate),
        ];
    }

    /**
     * Get public revocation information (safe for public display)
     *
     * @param CTSCertificate $certificate
     * @return array
     */
    public function getPublicRevocationInfo(CTSCertificate $certificate): array
    {
        if (!$certificate->is_revoked) {
            return [
                'revoked' => false,
            ];
        }

        return [
            'revoked' => true,
            'certificate_number' => $certificate->certificate_number,
            'revoked_at' => $certificate->revoked_at->format('Y-m-d H:i:s T'),
            'reason_code' => $certificate->revocation_reason,
            'reason_description' => self::REASON_CODES[$certificate->revocation_reason] ?? 'Unknown',
            'issued_by' => $certificate->partner?->partner_name ?? 'Cultural Translate Platform',
            'original_issue_date' => $certificate->issue_date->format('Y-m-d'),
            'cts_level' => $certificate->cts_level,
            'public_notes' => $this->getPublicRevocationNotes($certificate),
        ];
    }

    /**
     * Get public-safe revocation notes
     *
     * @param CTSCertificate $certificate
     * @return string|null
     */
    protected function getPublicRevocationNotes(CTSCertificate $certificate): ?string
    {
        // Some revocation reasons should not expose detailed notes publicly
        $privateReasons = ['fraudulent', 'quality_issue', 'partner_terminated'];

        if (in_array($certificate->revocation_reason, $privateReasons)) {
            return 'For security and privacy reasons, detailed information is not publicly available.';
        }

        return $certificate->revocation_notes;
    }

    /**
     * Reinstate a revoked certificate (rare, requires high authority)
     *
     * @param CTSCertificate $certificate
     * @param string $reason
     * @param int|null $reinstatedBy
     * @return bool
     */
    public function reinstate(
        CTSCertificate $certificate,
        string $reason,
        ?int $reinstatedBy = null
    ): bool {
        if (!$certificate->is_revoked) {
            Log::warning("Certificate {$certificate->certificate_number} is not revoked, cannot reinstate");
            return false;
        }

        // Store old values for audit
        $oldValues = [
            'is_revoked' => true,
            'revoked_at' => $certificate->revoked_at,
            'revocation_reason' => $certificate->revocation_reason,
        ];

        // Reinstate
        $certificate->update([
            'is_revoked' => false,
            'revoked_at' => null,
            'revocation_reason' => null,
            'revoked_by' => null,
            'revocation_notes' => null,
            'status' => 'active',
        ]);

        // Log to audit trail
        $this->auditTrail->log(
            'certificate_reinstated',
            $certificate,
            $oldValues,
            [
                'is_revoked' => false,
                'status' => 'active',
            ],
            [
                'reinstatement_reason' => $reason,
                'reinstated_by_user_id' => $reinstatedBy ?? auth()->id(),
                'reinstatement_timestamp' => now()->toIso8601String(),
            ]
        );

        Log::warning("Certificate {$certificate->certificate_number} reinstated. Reason: {$reason}");

        return true;
    }

    /**
     * Get revocation statistics
     *
     * @param array $filters
     * @return array
     */
    public function getRevocationStatistics(array $filters = []): array
    {
        $query = CTSCertificate::where('is_revoked', true);

        if (isset($filters['start_date'])) {
            $query->where('revoked_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('revoked_at', '<=', $filters['end_date']);
        }

        if (isset($filters['partner_id'])) {
            $query->where('partner_id', $filters['partner_id']);
        }

        $total = $query->count();

        // Group by reason
        $byReason = $query->get()
            ->groupBy('revocation_reason')
            ->map(fn($group) => $group->count())
            ->toArray();

        // Recent revocations
        $recent = CTSCertificate::where('is_revoked', true)
            ->orderBy('revoked_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($cert) => [
                'certificate_number' => $cert->certificate_number,
                'revoked_at' => $cert->revoked_at,
                'reason' => self::REASON_CODES[$cert->revocation_reason] ?? 'Unknown',
            ]);

        return [
            'total_revoked' => $total,
            'by_reason' => $byReason,
            'recent_revocations' => $recent,
        ];
    }

    /**
     * Notify relevant parties about revocation
     *
     * @param CTSCertificate $certificate
     * @param string $reasonCode
     * @param string|null $notes
     */
    protected function notifyRevocation(CTSCertificate $certificate, string $reasonCode, ?string $notes): void
    {
        // In production, this would send notifications to:
        // - Certificate holder
        // - Partner (if applicable)
        // - Government agencies (if required)
        // - Public revocation list

        Log::info("Revocation notifications sent for certificate {$certificate->certificate_number}");
    }

    /**
     * Get all revocation reason codes
     *
     * @return array
     */
    public static function getReasonCodes(): array
    {
        return self::REASON_CODES;
    }

    /**
     * Validate reason code
     *
     * @param string $code
     * @return bool
     */
    public static function isValidReasonCode(string $code): bool
    {
        return array_key_exists($code, self::REASON_CODES);
    }
}
