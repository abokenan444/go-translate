<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TranslationMemory;
use App\Models\Glossary;
use App\Models\BrandVoice;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TranslationApiController extends Controller
{
    /**
     * Translate text with cultural adaptation
     */
    public function translate(Request $request)
    {
        $startTime = microtime(true);
        
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:10000',
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'context' => 'nullable|in:formal,casual,marketing,legal,technical,medical,general',
            'apply_glossary' => 'nullable|boolean',
            'use_translation_memory' => 'nullable|boolean',
            'brand_voice_id' => 'nullable|exists:brand_voices,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $text = $request->input('text');
        $sourceLanguage = $request->input('source_language');
        $targetLanguage = $request->input('target_language');
        $context = $request->input('context', 'general');
        $applyGlossary = $request->input('apply_glossary', true);
        $useTranslationMemory = $request->input('use_translation_memory', true);
        $brandVoiceId = $request->input('brand_voice_id');

        $translatedText = $text;
        $culturalAdaptations = [];
        $glossaryMatches = 0;
        $translationMemoryUsed = false;

        // Check Translation Memory first
        if ($useTranslationMemory) {
            $hash = md5($sourceLanguage . '|' . $targetLanguage . '|' . strtolower($text));
            $tmEntry = TranslationMemory::where('hash', $hash)->first();
            
            if ($tmEntry) {
                $translatedText = $tmEntry->target_text;
                $translationMemoryUsed = true;
                $tmEntry->increment('usage_count');
                $tmEntry->update(['last_used_at' => now()]);
            }
        }

        // Apply Glossary if not found in TM
        if (!$translationMemoryUsed && $applyGlossary) {
            $glossaries = Glossary::where('source_language', $sourceLanguage)
                ->where('target_language', $targetLanguage)
                ->where('is_active', true)
                ->get();

            foreach ($glossaries as $glossary) {
                $pattern = $glossary->case_sensitive 
                    ? '/\b' . preg_quote($glossary->source_term, '/') . '\b/'
                    : '/\b' . preg_quote($glossary->source_term, '/') . '\b/i';
                
                if (preg_match($pattern, $translatedText)) {
                    $translatedText = preg_replace($pattern, $glossary->target_term, $translatedText);
                    $glossaryMatches++;
                }
            }
        }

        // Apply Brand Voice
        if ($brandVoiceId) {
            $brandVoice = BrandVoice::find($brandVoiceId);
            if ($brandVoice && $brandVoice->is_active) {
                $culturalAdaptations[] = 'brand_voice_applied';
                // Here you would apply brand voice transformations
            }
        }

        // Apply cultural adaptations based on context
        if ($context === 'formal') {
            $culturalAdaptations[] = 'formal_greeting';
        }

        // Save to Translation Memory if new
        if (!$translationMemoryUsed) {
            TranslationMemory::create([
                'source_language' => $sourceLanguage,
                'target_language' => $targetLanguage,
                'source_text' => $text,
                'target_text' => $translatedText,
                'context' => $context,
                'hash' => md5($sourceLanguage . '|' . $targetLanguage . '|' . strtolower($text)),
                'usage_count' => 1,
                'last_used_at' => now(),
            ]);
        }

        $processingTime = round((microtime(true) - $startTime) * 1000, 2);
        $requestId = 'req_' . Str::random(10);

        // Log API request
        ApiLog::create([
            'sandbox_instance_id' => $request->user()?->sandboxInstance?->id,
            'endpoint' => '/v1/translate',
            'method' => 'POST',
            'request_payload' => $request->all(),
            'response_payload' => [
                'translated_text' => $translatedText,
                'cultural_adaptations' => $culturalAdaptations,
            ],
            'status_code' => 200,
            'response_time' => $processingTime,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'translated_text' => $translatedText,
                'source_language' => $sourceLanguage,
                'target_language' => $targetLanguage,
                'cultural_adaptations' => $culturalAdaptations,
                'glossary_matches' => $glossaryMatches,
                'translation_memory_used' => $translationMemoryUsed,
            ],
            'meta' => [
                'request_id' => $requestId,
                'processing_time_ms' => $processingTime,
            ]
        ]);
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'texts' => 'required|array|max:100',
            'texts.*' => 'required|string|max:10000',
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'context' => 'nullable|in:formal,casual,marketing,legal,technical,medical,general',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $texts = $request->input('texts');
        $results = [];

        foreach ($texts as $text) {
            $request->merge(['text' => $text]);
            $response = $this->translate($request);
            $results[] = json_decode($response->getContent(), true);
        }

        return response()->json([
            'success' => true,
            'data' => $results,
            'meta' => [
                'total_texts' => count($texts),
            ]
        ]);
    }

    /**
     * Get supported languages
     */
    public function languages()
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English'],
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية'],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español'],
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français'],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch'],
            ['code' => 'it', 'name' => 'Italian', 'native_name' => 'Italiano'],
            ['code' => 'pt', 'name' => 'Portuguese', 'native_name' => 'Português'],
            ['code' => 'ru', 'name' => 'Russian', 'native_name' => 'Русский'],
            ['code' => 'zh', 'name' => 'Chinese', 'native_name' => '中文'],
            ['code' => 'ja', 'name' => 'Japanese', 'native_name' => '日本語'],
        ];

        return response()->json([
            'success' => true,
            'data' => $languages,
        ]);
    }
}
