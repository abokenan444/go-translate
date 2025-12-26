<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * QR Code Verification Service
 * Generates QR codes for certificate verification and validates them
 */
class QRCodeVerificationService
{
    /**
     * Generate QR code for certificate verification
     *
     * @param string $certificateId
     * @param array $options
     * @return string SVG or PNG data
     */
    public function generateQRCode(string $certificateId, array $options = []): string
    {
        $verificationUrl = $this->getVerificationUrl($certificateId);
        
        $size = $options['size'] ?? 300;
        $format = $options['format'] ?? 'svg';
        $margin = $options['margin'] ?? 2;
        $errorCorrection = $options['error_correction'] ?? 'H'; // High error correction
        
        try {
            $qrCode = QrCode::size($size)
                ->margin($margin)
                ->errorCorrection($errorCorrection)
                ->format($format);
            
            // Add logo if specified
            if (isset($options['logo_path']) && file_exists($options['logo_path'])) {
                $qrCode->merge($options['logo_path'], 0.3, true);
            }
            
            return $qrCode->generate($verificationUrl);
            
        } catch (\Exception $e) {
            Log::error('QR Code generation failed', [
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Get verification URL for certificate
     *
     * @param string $certificateId
     * @return string
     */
    public function getVerificationUrl(string $certificateId): string
    {
        return url("/verify/{$certificateId}");
    }
    
    /**
     * Verify certificate by ID
     *
     * @param string $certificateId
     * @return array
     */
    public function verifyCertificate(string $certificateId): array
    {
        try {
            // Check in official_documents table
            $document = DB::table('official_documents')
                ->where('certificate_id', $certificateId)
                ->orWhere('id', $certificateId)
                ->first();
            
            if (!$document) {
                return [
                    'valid' => false,
                    'status' => 'not_found',
                    'message' => 'Certificate not found in our records',
                    'certificate_id' => $certificateId
                ];
            }
            
            // Check if certificate is revoked
            $isRevoked = DB::table('certificate_revocations')
                ->where('certificate_id', $document->certificate_id ?? $document->id)
                ->where('status', 'revoked')
                ->exists();
            
            if ($isRevoked) {
                $revocation = DB::table('certificate_revocations')
                    ->where('certificate_id', $document->certificate_id ?? $document->id)
                    ->where('status', 'revoked')
                    ->first();
                
                return [
                    'valid' => false,
                    'status' => 'revoked',
                    'message' => 'This certificate has been revoked',
                    'certificate_id' => $certificateId,
                    'revocation_reason' => $revocation->reason ?? 'Not specified',
                    'revocation_date' => $revocation->created_at ?? null
                ];
            }
            
            // Check expiry if applicable
            if (isset($document->expires_at) && $document->expires_at) {
                $expiryDate = Carbon::parse($document->expires_at);
                if ($expiryDate->isPast()) {
                    return [
                        'valid' => false,
                        'status' => 'expired',
                        'message' => 'This certificate has expired',
                        'certificate_id' => $certificateId,
                        'expiry_date' => $expiryDate->toDateTimeString()
                    ];
                }
            }
            
            // Certificate is valid
            $this->logVerificationAttempt($certificateId, 'success');
            
            return [
                'valid' => true,
                'status' => 'active',
                'message' => 'Certificate is valid and active',
                'certificate_id' => $document->certificate_id ?? $certificateId,
                'serial_number' => $document->serial_number ?? null,
                'issue_date' => $document->created_at,
                'document_type' => $document->document_type ?? 'official_translation',
                'source_language' => $document->source_language ?? null,
                'target_language' => $document->target_language ?? null,
                'translator' => $this->getTranslatorInfo($document),
                'partner' => $this->getPartnerInfo($document),
                'verification_count' => $this->getVerificationCount($certificateId)
            ];
            
        } catch (\Exception $e) {
            Log::error('Certificate verification failed', [
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'valid' => false,
                'status' => 'error',
                'message' => 'Verification system error',
                'certificate_id' => $certificateId
            ];
        }
    }
    
    /**
     * Get translator information
     *
     * @param object $document
     * @return array|null
     */
    private function getTranslatorInfo($document): ?array
    {
        if (!isset($document->translator_id)) {
            return null;
        }
        
        $translator = DB::table('users')
            ->where('id', $document->translator_id)
            ->first();
        
        if (!$translator) {
            return null;
        }
        
        return [
            'name' => $translator->name,
            'certification' => $translator->certification_number ?? null
        ];
    }
    
    /**
     * Get partner information
     *
     * @param object $document
     * @return array|null
     */
    private function getPartnerInfo($document): ?array
    {
        if (!isset($document->partner_id)) {
            return null;
        }
        
        $partner = DB::table('partners')
            ->where('id', $document->partner_id)
            ->first();
        
        if (!$partner) {
            return null;
        }
        
        return [
            'name' => $partner->name,
            'certification_type' => $partner->certification_type ?? null,
            'country' => $partner->country ?? null
        ];
    }
    
    /**
     * Get verification count for certificate
     *
     * @param string $certificateId
     * @return int
     */
    private function getVerificationCount(string $certificateId): int
    {
        return DB::table('certificate_verifications')
            ->where('certificate_id', $certificateId)
            ->count();
    }
    
    /**
     * Log verification attempt
     *
     * @param string $certificateId
     * @param string $status
     * @return void
     */
    private function logVerificationAttempt(string $certificateId, string $status): void
    {
        try {
            DB::table('certificate_verifications')->insert([
                'certificate_id' => $certificateId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'status' => $status,
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log verification attempt', [
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Generate batch QR codes
     *
     * @param array $certificateIds
     * @param array $options
     * @return array
     */
    public function generateBatchQRCodes(array $certificateIds, array $options = []): array
    {
        $results = [];
        
        foreach ($certificateIds as $certificateId) {
            try {
                $qrCode = $this->generateQRCode($certificateId, $options);
                $results[$certificateId] = [
                    'success' => true,
                    'qr_code' => $qrCode
                ];
            } catch (\Exception $e) {
                $results[$certificateId] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Save QR code to file
     *
     * @param string $certificateId
     * @param string $path
     * @param array $options
     * @return bool
     */
    public function saveQRCodeToFile(string $certificateId, string $path, array $options = []): bool
    {
        try {
            $qrCode = $this->generateQRCode($certificateId, array_merge($options, ['format' => 'png']));
            file_put_contents($path, $qrCode);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to save QR code to file', [
                'certificate_id' => $certificateId,
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
