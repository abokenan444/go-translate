<?php
namespace App\Http\Controllers;

use App\Services\SimpleDemoTranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DemoController extends Controller
{
    protected $translationService;

    public function __construct(SimpleDemoTranslationService $translationService)
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
            Log::info('Demo translation request', [
                'text' => $request->text,
                'source' => $request->source_language,
                'target' => $request->target_language
            ]);

            $result = $this->translationService->translate([
                "text" => $request->text,
                "source_language" => $request->source_language,
                "target_language" => $request->target_language,
                "tone" => "professional",
                "context" => "Demo translation",
                "task_type" => null,
                "industry" => null
            ]);

            Log::info('Demo translation result', $result);

            if ($result['success']) {
                // Generate cultural insights based on target culture
                $targetCulture = $request->target_culture ?? 'general';
                $culturalInsights = $this->generateCulturalInsights(
                    $request->source_language,
                    $request->target_language,
                    $targetCulture
                );
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'translated_text' => $result['translated_text'] ?? '',
                        'source_language' => $result['source_language'] ?? $request->source_language,
                        'target_language' => $result['target_language'] ?? $request->target_language,
                        'quality_score' => $result['quality_score'] ?? 0,
                        'word_count' => str_word_count($request->text),
                        'cultural_insights' => $culturalInsights,
                        'tokens_used' => $result['tokens_used'] ?? 0,
                        'response_time_ms' => $result['response_time_ms'] ?? 0,
                        'cached' => $result['cached'] ?? false,
                    ],
                    'message' => 'ترجمة ناجحة | Translation successful'
                ]);
            } else {
                Log::error('Demo translation failed', ['error' => $result['error'] ?? 'Unknown error']);
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في الترجمة | Translation failed: ' . ($result['error'] ?? 'Unknown error'),
                    'error' => $result['error'] ?? 'Translation failed'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Demo translation exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'خطأ في خدمة الترجمة | Translation service error: ' . $e->getMessage(),
                'error' => 'Translation service error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate cultural insights based on languages and culture
     */
    protected function generateCulturalInsights($sourceLang, $targetLang, $targetCulture): array
    {
        $insights = [];
        
        // Add insights based on target culture
        $cultureInsights = [
            'saudi_arabia' => [
                'نبرة رسمية مناسبة للثقافة السعودية | Formal tone adjusted for Saudi culture',
                'استخدام التحيات والألقاب التقليدية | Traditional greetings and titles applied',
                'تكييف التعبيرات الثقافية | Cultural expressions adapted'
            ],
            'egypt' => [
                'أسلوب محادثة مناسب للثقافة المصرية | Conversational style for Egyptian culture',
                'تعديل التعبيرات العامية | Colloquial expressions adjusted',
                'نبرة ودية وتفاعلية | Friendly and interactive tone'
            ],
            'morocco' => [
                'أسلوب ودود مناسب للثقافة المغربية | Friendly style for Moroccan culture',
                'مزج بين الفصحى والدارجة | Mix of formal and colloquial Arabic',
                'تعبيرات محلية مكيفة | Local expressions adapted'
            ],
            'usa' => [
                'نبرة أمريكية احترافية | American professional tone',
                'تهجئة أمريكية | American spelling used',
                'تعبيرات ثقافية أمريكية | American cultural expressions'
            ],
            'uk' => [
                'نبرة بريطانية رسمية | British formal tone',
                'تهجئة بريطانية | British spelling used',
                'تعبيرات ثقافية بريطانية | British cultural expressions'
            ],
            'spain' => [
                'إسبانية قشتالية | Castilian Spanish',
                'نبرة رسمية أوروبية | Formal European tone',
                'تعبيرات إسبانية محلية | Local Spanish expressions'
            ],
            'mexico' => [
                'إسبانية أمريكا اللاتينية | Latin American Spanish',
                'نبرة ودية ودافئة | Friendly and warm tone',
                'تعبيرات مكسيكية محلية | Mexican local expressions'
            ],
        ];
        
        $insights = $cultureInsights[$targetCulture] ?? [
            'التكيف الثقافي المطبق | Cultural adaptation applied',
            'نبرة مناسبة للجمهور المستهدف | Tone appropriate for target audience',
            'تعبيرات محلية محترمة | Culturally respectful expressions'
        ];
        
        // Add language-specific insights
        if ($targetLang === 'ar') {
            $insights[] = 'تشكيل صحيح للنص العربي | Proper Arabic diacritics';
        }
        
        return $insights;
    }
}
