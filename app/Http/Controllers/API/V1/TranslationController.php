<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function translate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:10000',
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'cultural_adaptation' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $result = $this->translationService->translate(
                $request->text,
                $request->source_language,
                $request->target_language,
                $request->boolean('cultural_adaptation', false)
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'translated_text' => $result['translated_text'],
                    'word_count' => str_word_count($request->text),
                    'source_language' => $request->source_language,
                    'target_language' => $request->target_language,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Translation failed'], 500);
        }
    }

    public function languages()
    {
        $languages = [
            'en' => 'English', 'ar' => 'Arabic', 'es' => 'Spanish',
            'fr' => 'French', 'de' => 'German', 'zh' => 'Chinese',
            'ja' => 'Japanese', 'ko' => 'Korean', 'ru' => 'Russian',
        ];

        return response()->json(['success' => true, 'data' => $languages]);
    }

    public function status($id)
    {
        // TODO: Implement translation status check
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'status' => 'completed',
                'progress' => 100
            ]
        ]);
    }
}
