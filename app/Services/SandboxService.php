<?php

namespace App\Services;

use App\Models\PartnerApiKey;
use Illuminate\Support\Facades\Log;

class SandboxService
{
    /**
     * Check if request is in sandbox mode
     */
    public function isSandbox($request): bool
    {
        return $request->get('is_sandbox', false) === true;
    }

    /**
     * Process translation in sandbox mode
     */
    public function processSandboxTranslation($text, $from, $to): array
    {
        // Simulate translation delay
        usleep(500000); // 0.5 seconds

        return [
            'success' => true,
            'mode' => 'sandbox',
            'original_text' => $text,
            'translated_text' => "[SANDBOX] Translated: " . $text,
            'from' => $from,
            'to' => $to,
            'characters' => strlen($text),
            'timestamp' => now()->toIso8601String(),
            'warning' => 'This is a sandbox translation. No actual translation was performed.',
        ];
    }

    /**
     * Process document translation in sandbox mode
     */
    public function processSandboxDocument($file): array
    {
        // Simulate document processing
        usleep(1000000); // 1 second

        return [
            'success' => true,
            'mode' => 'sandbox',
            'document_id' => 'sandbox_' . uniqid(),
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'status' => 'completed',
            'download_url' => url('/sandbox/documents/sample.pdf'),
            'timestamp' => now()->toIso8601String(),
            'warning' => 'This is a sandbox document. No actual processing was performed.',
        ];
    }

    /**
     * Get sandbox test data
     */
    public function getTestData($type = 'translation'): array
    {
        $testData = [
            'translation' => [
                'text' => 'Hello, this is a test translation.',
                'from' => 'en',
                'to' => 'ar',
                'expected_result' => 'مرحباً، هذه ترجمة تجريبية.',
            ],
            'document' => [
                'sample_url' => url('/sandbox/samples/test-document.pdf'),
                'languages' => ['en', 'ar', 'fr', 'es', 'de'],
                'document_types' => ['birth_certificate', 'passport', 'contract', 'academic'],
            ],
            'legal' => [
                'case_types' => ['civil', 'criminal', 'commercial', 'family'],
                'document_types' => ['contract', 'agreement', 'court_order', 'affidavit'],
            ],
        ];

        return $testData[$type] ?? [];
    }

    /**
     * Log sandbox activity
     */
    public function logActivity(PartnerApiKey $apiKey, string $action, array $data = []): void
    {
        Log::channel('sandbox')->info("Sandbox Activity", [
            'partner_id' => $apiKey->partner_id,
            'api_key_id' => $apiKey->id,
            'action' => $action,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Validate sandbox request
     */
    public function validateRequest($request): array
    {
        $errors = [];

        // Check if sandbox mode is properly configured
        if (!$request->has('is_sandbox')) {
            $errors[] = 'Sandbox mode not detected';
        }

        // Check API key environment
        $apiKey = $request->get('partner_api_key');
        if ($apiKey && $apiKey->environment !== 'sandbox') {
            $errors[] = 'API key is not configured for sandbox environment';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Generate sandbox response wrapper
     */
    public function wrapResponse(array $data): array
    {
        return [
            'sandbox_mode' => true,
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
            'notice' => 'This response is from the sandbox environment. No real transactions or processing occurred.',
        ];
    }

    /**
     * Get sandbox usage statistics
     */
    public function getUsageStats(int $partnerId): array
    {
        // In sandbox, return mock statistics
        return [
            'total_requests' => rand(10, 100),
            'successful_requests' => rand(8, 90),
            'failed_requests' => rand(0, 10),
            'average_response_time' => rand(100, 500) . 'ms',
            'last_request' => now()->subMinutes(rand(1, 60))->toIso8601String(),
        ];
    }
}
