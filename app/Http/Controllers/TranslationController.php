<?php

namespace App\Http\Controllers;

use App\Services\AdvancedTranslationService;
use App\Services\CulturalAdaptationEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    protected $translationService;
    protected $culturalEngine;
    
    public function __construct(AdvancedTranslationService $translationService, CulturalAdaptationEngine $culturalEngine)
    {
        $this->translationService = $translationService;
        $this->culturalEngine = $culturalEngine;
    }
    
    /**
     * Show translation page
     */
    public function index()
    {
        $languages = $this->culturalEngine->getAllCultures();
        $tones = $this->culturalEngine->getAllTones();
        $industries = $this->culturalEngine->getAllIndustries();
        $taskTemplates = $this->culturalEngine->getAllTaskTemplates();
        
        return view('dashboard.translate', compact('languages', 'tones', 'industries', 'taskTemplates'));
    }
    
    /**
     * Translate text with advanced cultural adaptation
     */
    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:10000',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
            'tone' => 'nullable|string',
            'industry' => 'nullable|string',
            'task_type' => 'nullable|string',
            'context' => 'nullable|string|max:1000',
        ]);
        
        $user = Auth::user();
        
        // Check if user has available quota
        if (!$this->checkUserQuota($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Translation quota exceeded',
                'message' => 'You have reached your translation limit. Please upgrade your plan.'
            ], 429);
        }
        
        // Validate language selection
        if ($request->source_language === $request->target_language) {
            return response()->json([
                'success' => false,
                'error' => 'Same language selected',
                'message' => 'الرجاء اختيار لغات مختلفة'
            ], 422);
        }
        
        // Perform translation
        $result = $this->translationService->translate([
            'text' => $request->text,
            'source_language' => $request->source_language,
            'target_language' => $request->target_language,
            'tone' => $request->tone ?? 'professional',
            'industry' => $request->industry,
            'task_type' => $request->task_type,
            'context' => $request->context,
        ]);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Translation failed',
            ], 500);
        }
        
        // Save translation to database
        try {
            $translationId = DB::table('translations')->insertGetId([
                'user_id' => $user->id,
                'source_text' => $request->text,
                'translated_text' => $result['translated_text'],
                'source_language' => $request->source_language,
                'target_language' => $request->target_language,
                'tone' => $request->tone ?? 'professional',
                'context' => $request->context,
                'word_count' => $result['word_count'],
                'total_tokens' => $result['total_tokens'] ?? $result['tokens_used'] ?? 0,
                'tokens_in' => round($result['tokens_used'] * 0.6),
                'tokens_out' => round($result['tokens_used'] * 0.4),
                'quality_score' => $result['quality_score'] ?? null,
                'response_time_ms' => $result['response_time_ms'] ?? 0,
                'is_cached' => $result['cached'] ?? false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Update user stats (if not cached)
            if (!($result['cached'] ?? false)) {
                $tokensUsed = $result['total_tokens'] ?? $result['tokens_used'] ?? 0;
                $this->updateUserQuota($user, $tokensUsed);
            }
            
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to save translation: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'translated_text' => $result['translated_text'],
            'source_language' => $result['source_language'],
            'target_language' => $result['target_language'],
            'tone' => $result['tone'],
            'word_count' => $result['word_count'],
            'tokens_used' => $result['total_tokens'] ?? $result['tokens_used'] ?? 0,
            'quality_score' => $result['quality_score'] ?? null,
            'cached' => $result['cached'] ?? false,
            'response_time_ms' => $result['response_time_ms'] ?? 0,
        ]);
    }
    
    /**
     * Detect language
     */
    public function detectLanguage(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);
        
        $result = $this->translationService->detectLanguage($request->text);
        
        return response()->json($result);
    }
    
    /**
     * Get translation history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $translations = DB::table('translations')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        return response()->json([
            'success' => true,
            'translations' => $translations,
        ]);
    }
    
    /**
     * Check user translation quota
     */
    protected function checkUserQuota($user): bool
    {
        // For now, always return true
        // TODO: Implement proper quota checking based on user plan
        return true;
    }
    
    /**
     * Update user quota usage
     */
    protected function updateUserQuota($user, int $tokensUsed): void
    {
        // TODO: Implement quota tracking
        // This would update user_stats table or similar
    }
}
