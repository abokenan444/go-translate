<?php

namespace App\Services;

use App\Models\CtsCertificate;
use App\Models\RiskAssessment;
use App\Models\VerificationRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class CtsCertificateService
{
    /**
     * Issue a CTS certificate
     */
    public function issue(array $payload): CtsCertificate
    {
        return DB::transaction(function () use ($payload) {
            // Generate certificate ID
            $certificateId = CtsCertificate::generateCertificateId();

            // Calculate document hash
            $documentHash = $this->calculateDocumentHash($payload);

            // Create certificate record
            $certificate = CtsCertificate::create([
                'certificate_id' => $certificateId,
                'user_id' => $payload['user_id'],
                'project_id' => $payload['project_id'] ?? null,
                'risk_assessment_id' => $payload['risk_assessment_id'] ?? null,
                'partner_id' => $payload['partner_id'] ?? null,
                'cts_level' => $payload['cts_level'],
                'cultural_impact_score' => $payload['cultural_impact_score'],
                'source_language' => $payload['source_language'],
                'target_language' => $payload['target_language'],
                'target_country' => $payload['target_country'] ?? null,
                'use_case' => $payload['use_case'] ?? null,
                'domain' => $payload['domain'] ?? null,
                'original_document_path' => $payload['original_document_path'] ?? null,
                'translated_document_path' => $payload['translated_document_path'] ?? null,
                'document_hash' => $documentHash,
                'metadata' => $payload['metadata'] ?? [],
                'issued_at' => now(),
                'expires_at' => $payload['expires_at'] ?? null,
                'is_verified' => true,
                'is_public' => $payload['is_public'] ?? false,
            ]);

            // Generate QR code
            $qrCodePath = $this->generateQrCode($certificate);
            $certificate->update(['qr_code_path' => $qrCodePath]);

            // Generate certificate PDF
            $pdfPath = $this->generateCertificatePdf($certificate);
            $certificate->update(['certificate_pdf_path' => $pdfPath]);

            // Create verification registry entry
            $this->createVerificationEntry($certificate);

            return $certificate->fresh();
        });
    }

    /**
     * Calculate document hash for verification
     */
    protected function calculateDocumentHash(array $payload): string
    {
        $data = [
            'user_id' => $payload['user_id'],
            'cts_level' => $payload['cts_level'],
            'cultural_impact_score' => $payload['cultural_impact_score'],
            'source_language' => $payload['source_language'],
            'target_language' => $payload['target_language'],
            'timestamp' => now()->toIso8601String(),
        ];

        // Include document content if available
        if (isset($payload['translated_document_path']) && Storage::exists($payload['translated_document_path'])) {
            $data['document_content'] = Storage::get($payload['translated_document_path']);
        }

        return hash('sha256', json_encode($data));
    }

    /**
     * Generate QR code for certificate verification
     */
    protected function generateQrCode(CtsCertificate $certificate): string
    {
        $verificationUrl = url("/verify/{$certificate->certificate_id}");
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($verificationUrl);

        $path = "certificates/qr/{$certificate->certificate_id}.png";
        Storage::put($path, $qrCode);

        return $path;
    }

    /**
     * Generate certificate PDF
     */
    protected function generateCertificatePdf(CtsCertificate $certificate): string
    {
        $data = [
            'certificate' => $certificate,
            'qr_code_url' => Storage::url($certificate->qr_code_path),
            'verification_url' => $certificate->verification_url,
        ];

        $pdf = Pdf::loadView('certificates.cts-certificate', $data)
            ->setPaper('a4', 'portrait');

        $path = "certificates/pdf/{$certificate->certificate_id}.pdf";
        Storage::put($path, $pdf->output());

        return $path;
    }

    /**
     * Create verification registry entry
     */
    protected function createVerificationEntry(CtsCertificate $certificate): VerificationRegistry
    {
        return VerificationRegistry::create([
            'cts_certificate_id' => $certificate->id,
            'verification_code' => VerificationRegistry::generateVerificationCode(),
            'verified_at' => now(),
            'verification_count' => 0,
            'last_verified_at' => now(),
        ]);
    }

    /**
     * Verify a certificate by ID
     */
    public function verify(string $certificateId, ?array $verifierInfo = null): ?array
    {
        $certificate = CtsCertificate::where('certificate_id', $certificateId)->first();

        if (!$certificate) {
            return null;
        }

        // Record verification
        $verification = $certificate->verifications()->first();
        if ($verification) {
            $verification->recordVerification(
                $verifierInfo['ip'] ?? null,
                $verifierInfo['country'] ?? null,
                $verifierInfo['user_agent'] ?? null
            );
        }

        return [
            'certificate' => $certificate,
            'is_valid' => $certificate->isValid(),
            'verification_count' => $verification ? $verification->verification_count : 0,
            'last_verified_at' => $verification ? $verification->last_verified_at : null,
        ];
    }

    /**
     * Issue certificate from risk assessment
     */
    public function issueFromAssessment(RiskAssessment $assessment, array $additionalData = []): CtsCertificate
    {
        $payload = array_merge([
            'user_id' => $assessment->user_id,
            'project_id' => $assessment->project_id,
            'risk_assessment_id' => $assessment->id,
            'cts_level' => $assessment->cts_level,
            'cultural_impact_score' => $assessment->cultural_impact_score,
            'source_language' => $assessment->source_language,
            'target_language' => $assessment->target_language,
            'target_country' => $assessment->target_country,
            'use_case' => $assessment->use_case,
            'domain' => $assessment->domain,
        ], $additionalData);

        return $this->issue($payload);
    }
}
