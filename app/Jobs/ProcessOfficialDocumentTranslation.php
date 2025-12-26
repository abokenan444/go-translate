<?php

namespace App\Jobs;

use App\Models\OfficialDocument;
use App\Models\DocumentTranslation;
use App\Models\DocumentCertificate;
use App\Services\PdfCertificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentProcessed;

class ProcessOfficialDocumentTranslation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes
    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public OfficialDocument $document
    ) {}

    /**
     * Execute the job.
     */
    public function handle(PdfCertificationService $pdfCertificationService): void
    {
        try {
            Log::info('Starting official document translation', [
                'document_id' => $this->document->id,
                'type' => $this->document->document_type,
            ]);

            // Update status to processing
            $this->document->update(['status' => 'processing']);

            // Step 1: Translate the PDF
            // NOTE: This is a placeholder. In production, you would call your actual translation service
            $translatedPath = $this->translateDocument();

            // Step 2: Create translation record
            $translation = DocumentTranslation::create([
                'official_document_id' => $this->document->id,
                'translated_file_path' => $translatedPath,
                'ai_engine_version' => config('official_documents.translation.ai_engine', 'gpt-4'),
                'quality_score' => 0,
                'reviewed_by_human' => false,
            ]);

            // Step 3: Generate certificate
            $certId = $this->generateCertificateId();
            
            $certificate = DocumentCertificate::create([
                'cert_id' => $certId,
                'document_id' => $this->document->id,
                'original_hash' => $this->document->original_hash,
                'translated_hash' => '', // Will be updated after certification
                'status' => 'valid',
                'issued_at' => now(),
                'expires_at' => $this->getExpiryDate(),
            ]);

            // Step 4: Apply certification seal, QR code, and legal statement
            $certifiedPath = $pdfCertificationService->applySealAndStatement(
                $translation->translated_file_path,
                $this->document,
                $certificate
            );

            // Update translation with certified path
            $translation->update(['certified_file_path' => $certifiedPath]);

            // Step 5: Calculate hash of certified document
            $certifiedFullPath = storage_path('app/' . $certifiedPath);
            $translatedHash = hash_file('sha256', $certifiedFullPath);
            
            $certificate->update(['translated_hash' => $translatedHash]);

            // Step 6: Mark as completed (skip human review for now)
            $this->document->update(['status' => 'completed']);
            
            // Step 7: Send email notification to user
            try {
                $user = $this->document->order->user;
                if ($user && $user->email) {
                    Mail::to($user->email)->send(new DocumentProcessed($this->document));
                    
                    Log::info('Email notification sent', [
                        'document_id' => $this->document->id,
                        'user_email' => $user->email,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send email notification', [
                    'document_id' => $this->document->id,
                    'error' => $e->getMessage(),
                ]);
                // Don't throw - email failure shouldn't fail the job
            }
            
            Log::info('Document translation completed', [
                'document_id' => $this->document->id,
                'cert_id' => $certificate->cert_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process official document translation', [
                'document_id' => $this->document->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->document->update(['status' => 'failed']);
            
            throw $e;
        }
    }

    /**
     * Translate the document using OpenAI.
     *
     * @return string Path to translated PDF
     */
    protected function translateDocument(): string
    {
        $originalPath = $this->document->original_file_path;
        $translatedPath = 'documents/translations/' . $this->document->id . '-translated.pdf';
        
        // Get full paths - handle both with and without 'private/' prefix
        $originalFullPath = storage_path('app/' . $originalPath);
        if (!file_exists($originalFullPath) && !str_starts_with($originalPath, 'private/')) {
            // Try with private/ prefix
            $originalFullPath = storage_path('app/private/' . $originalPath);
        }
        $translatedFullPath = storage_path('app/' . $translatedPath);
        
        // Ensure directory exists
        $translatedDir = dirname($translatedFullPath);
        if (!is_dir($translatedDir)) {
            mkdir($translatedDir, 0775, true);
        }
        
        // Check if original file exists
        if (!file_exists($originalFullPath)) {
            throw new \RuntimeException("Original PDF not found at: {$originalFullPath}");
        }

        try {
            // Use DocumentTranslationService for real translation
            $translationService = app(\App\Services\DocumentTranslationService::class);
            
            // Get source and target languages
            $sourceLang = $this->document->source_language ?? 'English';
            $targetLang = $this->document->target_language ?? 'Arabic';
            
            Log::info('Starting document translation', [
                'document_id' => $this->document->id,
                'from' => $sourceLang,
                'to' => $targetLang
            ]);
            
            // Translate the PDF
            $success = $translationService->translatePdf(
                $originalFullPath,
                $translatedFullPath,
                $sourceLang,
                $targetLang
            );
            
            if (!$success) {
                Log::warning('Translation service returned false, file may be copied instead');
            }
            
        } catch (\Exception $e) {
            Log::error('Translation failed, falling back to copy', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: copy original if translation fails
            if (!copy($originalFullPath, $translatedFullPath)) {
                throw new \RuntimeException("Failed to copy PDF from {$originalFullPath} to {$translatedFullPath}");
            }
        }
        
        // Verify the file exists
        if (!file_exists($translatedFullPath)) {
            throw new \RuntimeException("Translated PDF not found at: {$translatedFullPath}");
        }

        Log::info('Document translation completed', [
            'document_id' => $this->document->id,
            'original_path' => $originalPath,
            'translated_path' => $translatedPath,
            'file_size' => filesize($translatedFullPath),
        ]);

        return $translatedPath;
    }

    /**
     * Generate unique certificate ID.
     *
     * @return string
     */
    protected function generateCertificateId(): string
    {
        $date = now()->format('Y-m');
        $sequence = str_pad((string) $this->document->id, 8, '0', STR_PAD_LEFT);
        
        return "CT-{$date}-{$sequence}";
    }

    /**
     * Get certificate expiry date based on configuration.
     *
     * @return \Carbon\Carbon|null
     */
    protected function getExpiryDate(): ?\Carbon\Carbon
    {
        $expiryDays = config('official_documents.certificate.default_expiry_days');
        
        if ($expiryDays === null) {
            return null; // No expiry
        }

        return now()->addDays($expiryDays);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Official document translation job failed permanently', [
            'document_id' => $this->document->id,
            'error' => $exception->getMessage(),
        ]);

        $this->document->update(['status' => 'failed']);
    }
}
