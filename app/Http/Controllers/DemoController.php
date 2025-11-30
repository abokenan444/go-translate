<?php

namespace App\Http\Controllers;

use App\Services\AdvancedTranslationService;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    protected $translationService;

    public function __construct(AdvancedTranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Demo translation (no authentication required)
     */
    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500', // Limit demo to 500 chars
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
        ]);

        // Rate limiting for demo
        $ip = $request->ip();
        $cacheKey = 'demo_translate_' . $ip;
        
        if (cache()->has($cacheKey)) {
            $count = cache()->get($cacheKey);
            if ($count >= 10) {
                return response()->json([
                    'success' => false,
                    'error' => 'Rate limit exceeded',
                    'message' => 'لقد تجاوزت الحد المسموح. الرجاء إنشاء حساب للمتابعة.'
                ], 429);
            }
            cache()->put($cacheKey, $count + 1, now()->addHour());
        } else {
            cache()->put($cacheKey, 1, now()->addHour());
        }

        try {
            $result = $this->translationService->translate([
                "text" => $request->text,
                "source_language" => $request->source_language,
                "target_language" => $request->target_language,
                "tone" => "professional",
                "context" => "Demo translation",
                "task_type" => null,
                "industry" => null
            ]);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'translated_text' => $result['translated_text'],
                    'source_language' => $result['source_language'],
                    'target_language' => $result['target_language'],
                    'quality_score' => $result['quality_score'] ?? 0,
                    'word_count' => str_word_count($request->text),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Translation failed'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Translation service error: ' . $e->getMessage()
            ], 500);
        }
    }
}
