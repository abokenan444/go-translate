<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CulturalPromptEngine;
use Illuminate\Support\Facades\Log;

class DemoTranslationController extends Controller
{
    protected $culturalEngine;

    public function __construct(CulturalPromptEngine $culturalEngine)
    {
        $this->culturalEngine = $culturalEngine;
    }

    public function translate(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string|max:5000',
                'source_language' => 'required|string|size:2',
                'target_language' => 'required|string|size:2',
            ]);

            $text = $request->input('text');
            $sourceLang = $request->input('source_language');
            $targetLang = $request->input('target_language');

            // استخدام CulturalPromptEngine للترجمة
            $translation = $this->culturalEngine->translateWithCulturalContext(
                $text,
                $sourceLang,
                $targetLang,
                [
                    'formality_level' => 'neutral',
                    'preserve_tone' => true,
                    'adapt_idioms' => true
                ]
            );

            return response()->json([
                'success' => true,
                'translation' => $translation,
                'source_language' => $sourceLang,
                'target_language' => $targetLang
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Demo translation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Translation service is temporarily unavailable'
            ], 500);
        }
    }
}
