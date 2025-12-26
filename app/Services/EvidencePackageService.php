<?php

namespace App\Services;

use App\Models\OfficialDocument;
use App\Models\DocumentCertificate;
use App\Models\DecisionLedgerEvent;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * Evidence Package Service
 * 
 * Generates court-ready evidence ZIP containing:
 * - Original file
 * - Translated file
 * - Certificate PDF
 * - Review timeline (JSON)
 * - Decision ledger events (JSON)
 * - Hashes manifest (signed)
 */
class EvidencePackageService
{
    /**
     * Build evidence package for a document
     */
    public function build(int $documentId): array
    {
        $document = OfficialDocument::with(['certificate', 'translation', 'user'])
            ->findOrFail($documentId);

        $certificate = $document->certificate;
        if (!$certificate) {
            throw new \Exception('Document has no certificate');
        }

        // Create temp directory
        $tempDir = storage_path('app/temp/evidence_' . uniqid());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Collect files
        $files = [];

        // 1. Original document
        if ($document->file_path && Storage::exists($document->file_path)) {
            $originalPath = Storage::path($document->file_path);
            $originalDest = $tempDir . '/01_original_document.' . pathinfo($originalPath, PATHINFO_EXTENSION);
            copy($originalPath, $originalDest);
            $files[] = [
                'path' => $originalDest,
                'type' => 'original',
                'hash' => hash_file('sha256', $originalDest)
            ];
        }

        // 2. Translated document
        if ($document->translated_file_path && Storage::exists($document->translated_file_path)) {
            $translatedPath = Storage::path($document->translated_file_path);
            $translatedDest = $tempDir . '/02_translated_document.' . pathinfo($translatedPath, PATHINFO_EXTENSION);
            copy($translatedPath, $translatedDest);
            $files[] = [
                'path' => $translatedDest,
                'type' => 'translation',
                'hash' => hash_file('sha256', $translatedDest)
            ];
        }

        // 3. Certificate PDF
        if ($certificate->certificate_path && Storage::exists($certificate->certificate_path)) {
            $certPath = Storage::path($certificate->certificate_path);
            $certDest = $tempDir . '/03_certificate.pdf';
            copy($certPath, $certDest);
            $files[] = [
                'path' => $certDest,
                'type' => 'certificate',
                'hash' => hash_file('sha256', $certDest)
            ];
        }

        // 4. Review timeline
        $timeline = $this->buildTimeline($document);
        $timelinePath = $tempDir . '/04_review_timeline.json';
        file_put_contents($timelinePath, json_encode($timeline, JSON_PRETTY_PRINT));
        $files[] = [
            'path' => $timelinePath,
            'type' => 'timeline',
            'hash' => hash_file('sha256', $timelinePath)
        ];

        // 5. Decision ledger events
        $ledgerEvents = DecisionLedgerEvent::where('document_id', $documentId)
            ->orWhere('certificate_id', $certificate->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn($e) => [
                'event_uuid' => $e->event_uuid,
                'event_type' => $e->event_type,
                'actor_role' => $e->actor_role,
                'payload' => $e->payload,
                'hash' => $e->hash,
                'created_at' => $e->created_at->toIso8601String()
            ]);

        $ledgerPath = $tempDir . '/05_decision_ledger.json';
        file_put_contents($ledgerPath, json_encode($ledgerEvents, JSON_PRETTY_PRINT));
        $files[] = [
            'path' => $ledgerPath,
            'type' => 'ledger',
            'hash' => hash_file('sha256', $ledgerPath)
        ];

        // 6. Jurisdiction & compliance info
        $complianceInfo = [
            'document_id' => $document->id,
            'certificate_number' => $certificate->certificate_number,
            'jurisdiction' => [
                'country' => $document->jurisdiction_country ?? 'N/A',
                'purpose' => $document->jurisdiction_purpose ?? 'N/A'
            ],
            'issued_at' => $certificate->issued_at?->toIso8601String(),
            'status' => $certificate->status,
            'package_generated_at' => now()->toIso8601String(),
            'platform' => 'Cultural Translate',
            'verification_url' => route('verify.certificate', $certificate->certificate_number)
        ];

        $compliancePath = $tempDir . '/06_compliance_info.json';
        file_put_contents($compliancePath, json_encode($complianceInfo, JSON_PRETTY_PRINT));
        $files[] = [
            'path' => $compliancePath,
            'type' => 'compliance',
            'hash' => hash_file('sha256', $compliancePath)
        ];

        // 7. Manifest with all hashes (signed)
        $manifest = [
            'package_version' => '1.0',
            'document_id' => $documentId,
            'certificate_number' => $certificate->certificate_number,
            'generated_at' => now()->toIso8601String(),
            'files' => array_map(fn($f) => [
                'filename' => basename($f['path']),
                'type' => $f['type'],
                'sha256' => $f['hash']
            ], $files),
            'manifest_signature' => null // Will be set below
        ];

        // Sign manifest
        $manifest['manifest_signature'] = hash('sha256', json_encode($manifest));

        $manifestPath = $tempDir . '/00_MANIFEST.json';
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));

        // Create ZIP
        $zipFilename = 'evidence_' . $document->id . '_' . time() . '.zip';
        $zipPath = storage_path('app/evidence_packages/' . $zipFilename);
        
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Failed to create ZIP archive');
        }

        // Add manifest first
        $zip->addFile($manifestPath, basename($manifestPath));

        // Add all files
        foreach ($files as $file) {
            $zip->addFile($file['path'], basename($file['path']));
        }

        $zip->close();

        // Cleanup temp directory
        array_map('unlink', glob($tempDir . '/*'));
        rmdir($tempDir);

        return [
            'success' => true,
            'zip_path' => $zipPath,
            'zip_filename' => $zipFilename,
            'manifest' => $manifest,
            'file_count' => count($files) + 1 // +1 for manifest
        ];
    }

    /**
     * Build review timeline
     */
    protected function buildTimeline(OfficialDocument $document): array
    {
        return [
            'document_uploaded' => $document->created_at->toIso8601String(),
            'translation_started' => $document->translation_started_at?->toIso8601String(),
            'translation_completed' => $document->translation_completed_at?->toIso8601String(),
            'review_started' => $document->review_started_at?->toIso8601String(),
            'review_completed' => $document->review_completed_at?->toIso8601String(),
            'certificate_issued' => $document->certificate?->issued_at?->toIso8601String(),
            'total_processing_hours' => $document->created_at->diffInHours($document->completed_at ?? now())
        ];
    }
}
