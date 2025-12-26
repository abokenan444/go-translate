<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TranslationCache;
use App\Models\GuestUsageTracking;
use App\Services\TranslationService;
use App\Services\PromptLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicTranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Public translation endpoint with daily limit
     */
    public function translate(Request $request)
    {
        $startTime = microtime(true);
        
        // Validation
        $validator = Validator::make($request->all(), [
            'source_text' => 'required|string|max:5000',
            'source_lang' => 'nullable|string|size:2',
            'target_lang' => 'required|string|size:2',
            'content_type' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 422);
        }

        $sourceText = $request->input('source_text');
        $sourceLang = $request->input('source_lang', 'auto');
        $targetLang = $request->input('target_lang');
        $contentType = $request->input('content_type', 'general');

        // Check daily limit for guests
        if (!auth()->check()) {
            $ipAddress = $request->ip();
            $fingerprint = $request->header('X-Fingerprint');
            $cookieId = $request->cookie('guest_translation_id');
            
            $canTranslate = GuestUsageTracking::canTranslate($ipAddress, $fingerprint, $cookieId);
            
            if (!$canTranslate['allowed']) {
                return response()->json([
                    'success' => false,
                    'message' => $this->getBlockMessage($canTranslate),
                    'data' => $canTranslate,
                    'requires_registration' => true,
                    'register_url' => route('register'),
                ], 429);
            }
        }

        // Check cache first
        $cachedTranslation = TranslationCache::findTranslation(
            $sourceText,
            $sourceLang,
            $targetLang,
            $contentType
        );

        if ($cachedTranslation) {
            $responseTime = (microtime(true) - $startTime) * 1000;
            
            if (!auth()->check()) {
                $this->recordGuestUsage($request, $sourceLang, $targetLang);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'translated_text' => $cachedTranslation,
                    'source_lang' => $sourceLang,
                    'target_lang' => $targetLang,
                    'from_cache' => true,
                    'response_time_ms' => round($responseTime, 2),
                ],
                'usage' => $this->getUsageInfo($request),
            ])->withCookie($this->getTrackingCookie($request));
        }

        // Translate via API
        try {
            $translationResult = $this->translationService->translate(
                $sourceText,
                $sourceLang,
                $targetLang,
                ['content_type' => $contentType]
            );

            $translatedText = $translationResult['translated_text'] ?? $translationResult;
            
            // Save to cache
            TranslationCache::store(
                $sourceText,
                $translatedText,
                $sourceLang,
                $targetLang,
                $contentType
            );

            $responseTime = (microtime(true) - $startTime) * 1000;
            
            if (!auth()->check()) {
                $this->recordGuestUsage($request, $sourceLang, $targetLang);
            }
            
            // Log usage
            PromptLogger::log(
                'public_translation',
                "{$sourceLang}-{$targetLang}",
                null,
                null,
                $sourceLang,
                $targetLang,
                '',
                $sourceText,
                $translatedText,
                (int) $responseTime,
                false,
                ['content_type' => $contentType]
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'translated_text' => $translatedText,
                    'source_lang' => $sourceLang,
                    'target_lang' => $targetLang,
                    'from_cache' => false,
                    'response_time_ms' => round($responseTime, 2),
                ],
                'usage' => $this->getUsageInfo($request),
            ])->withCookie($this->getTrackingCookie($request));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation failed',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Check usage status
     */
    public function checkUsage(Request $request)
    {
        if (auth()->check()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_authenticated' => true,
                    'unlimited' => true,
                ],
            ]);
        }

        $ipAddress = $request->ip();
        $fingerprint = $request->header('X-Fingerprint');
        $cookieId = $request->cookie('guest_translation_id');
        
        $canTranslate = GuestUsageTracking::canTranslate($ipAddress, $fingerprint, $cookieId);

        return response()->json([
            'success' => true,
            'data' => $canTranslate,
        ]);
    }

    // Helpers
    
    private function recordGuestUsage(Request $request, string $sourceLang, string $targetLang): void
    {
        $ipAddress = $request->ip();
        $fingerprint = $request->header('X-Fingerprint');
        $cookieId = $request->cookie('guest_translation_id') ?? GuestUsageTracking::generateCookieId();
        
        GuestUsageTracking::recordUsage(
            $ipAddress,
            $fingerprint,
            $cookieId,
            $request->userAgent(),
            [
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'timestamp' => now(),
            ]
        );
    }

    private function getTrackingCookie(Request $request)
    {
        $cookieId = $request->cookie('guest_translation_id') ?? GuestUsageTracking::generateCookieId();
        return cookie('guest_translation_id', $cookieId, 60 * 24 * 30);
    }

    private function getUsageInfo(Request $request): array
    {
        if (auth()->check()) {
            return [
                'is_authenticated' => true,
                'unlimited' => true,
            ];
        }

        $ipAddress = $request->ip();
        $fingerprint = $request->header('X-Fingerprint');
        $cookieId = $request->cookie('guest_translation_id');
        
        $canTranslate = GuestUsageTracking::canTranslate($ipAddress, $fingerprint, $cookieId);
        
        return [
            'is_authenticated' => false,
            'used' => $canTranslate['used'] ?? 0,
            'remaining' => $canTranslate['remaining'] ?? 0,
            'limit' => $canTranslate['limit'] ?? 2,
        ];
    }

    private function getBlockMessage(array $canTranslate): string
    {
        if ($canTranslate['reason'] === 'blocked') {
            return "Daily limit exceeded. Please sign up.";
        }

        if ($canTranslate['reason'] === 'daily_limit_reached') {
            return "Free limit reached. Sign up for unlimited access.";
        }

        return "Please register to continue.";
    }
}
