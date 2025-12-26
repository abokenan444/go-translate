<?php
/**
 * Cultural Translation Pipeline Test
 * Tests all 5 layers of translation refinement
 */

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\ContextAnalysisEngine;
use App\Services\CulturalAdaptationEngine;
use App\Services\CulturalRiskEngine;
use App\Services\TranslationQualityScoreService;
use App\Services\LinguisticIntegrityService;
use App\Services\AdvancedCulturalTranslationService;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║      CULTURAL TRANSLATION PIPELINE - FULL TEST               ║\n";
echo "║      Testing all 5 layers of refinement                      ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Test text
$testText = "Our innovative solution will revolutionize the market. Let's break a leg and hit it out of the park!";
$sourceLang = 'en';
$targetLang = 'ar';

echo "📝 Source Text: {$testText}\n";
echo "🌍 Language Pair: {$sourceLang} → {$targetLang}\n\n";

// Layer 1: Context Analysis
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔍 LAYER 1: CONTEXT ANALYSIS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
try {
    $contextEngine = app(ContextAnalysisEngine::class);
    echo "✅ ContextAnalysisEngine loaded\n";
    echo "   Available methods: " . implode(', ', get_class_methods($contextEngine)) . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Layer 2: Cultural Adaptation
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎭 LAYER 2: CULTURAL ADAPTATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
try {
    $culturalEngine = app(CulturalAdaptationEngine::class);
    echo "✅ CulturalAdaptationEngine loaded\n";
    
    // Test getting Arabic profile
    $arabicProfile = $culturalEngine->getProfile('ar');
    if ($arabicProfile) {
        echo "   Arabic Culture Profile:\n";
        echo "   - Formality: " . ($arabicProfile['formality_level'] ?? 'N/A') . "\n";
        echo "   - Text Direction: " . ($arabicProfile['text_direction'] ?? 'N/A') . "\n";
        echo "   - Uses Honorifics: " . (($arabicProfile['uses_honorifics'] ?? false) ? 'Yes' : 'No') . "\n";
    } else {
        echo "   ⚠️ No Arabic profile found in database\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Layer 3: Cultural Risk Assessment
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "⚠️ LAYER 3: CULTURAL RISK ASSESSMENT\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
try {
    $riskEngine = app(CulturalRiskEngine::class);
    echo "✅ CulturalRiskEngine loaded\n";
    
    $riskAnalysis = $riskEngine->analyze([
        'text' => $testText,
        'source_language' => $sourceLang,
        'target_language' => $targetLang,
        'use_case' => 'marketing'
    ]);
    
    echo "   CTS Level: " . ($riskAnalysis['cts_level'] ?? 'N/A') . "\n";
    echo "   Impact Score: " . ($riskAnalysis['cultural_impact_score'] ?? 'N/A') . "\n";
    echo "   Requires Human Review: " . (($riskAnalysis['requires_human_review'] ?? false) ? 'Yes' : 'No') . "\n";
    echo "   Risk Flags: " . count($riskAnalysis['risk_flags'] ?? []) . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Layer 4: Translation Quality Scoring
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 LAYER 4: QUALITY SCORING\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
try {
    $qualityService = app(TranslationQualityScoreService::class);
    echo "✅ TranslationQualityScoreService loaded\n";
    
    // Sample translation for testing
    $sampleTranslation = "حلنا المبتكر سيحدث ثورة في السوق. دعونا نبذل قصارى جهدنا ونحقق نجاحًا باهرًا!";
    
    $quality = $qualityService->calculateScore($testText, $sampleTranslation, $targetLang, [
        'tone' => 'marketing'
    ]);
    
    echo "   Overall Score: " . ($quality['overall_score'] ?? 'N/A') . "\n";
    echo "   Rating: " . ($quality['rating'] ?? 'N/A') . "\n";
    echo "   Scores:\n";
    foreach (($quality['scores'] ?? []) as $metric => $score) {
        echo "     - {$metric}: {$score}\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Layer 5: Linguistic Integrity
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📝 LAYER 5: LINGUISTIC INTEGRITY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
try {
    $linguisticService = app(LinguisticIntegrityService::class);
    echo "✅ LinguisticIntegrityService loaded\n";
    
    $sampleTranslation = "حلنا المبتكر سيحدث ثورة في السوق.";
    
    $linguistic = $linguisticService->analyze($testText, $sampleTranslation, $sourceLang, $targetLang);
    
    echo "   Layer: " . ($linguistic['layer'] ?? 'N/A') . "\n";
    echo "   Score: " . ($linguistic['score'] ?? 'N/A') . "\n";
    echo "   Passed: " . (($linguistic['passed'] ?? false) ? 'Yes' : 'No') . "\n";
    echo "   Issues: " . count($linguistic['issues'] ?? []) . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Full Pipeline Test
echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🚀 FULL PIPELINE: ADVANCED TRANSLATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
try {
    $advancedService = app(AdvancedCulturalTranslationService::class);
    echo "✅ AdvancedCulturalTranslationService loaded\n";
    echo "   Available methods: " . implode(', ', get_class_methods($advancedService)) . "\n";
    
    // Check if OpenAI is configured
    $openaiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
    if (!empty($openaiKey)) {
        echo "\n   🌐 OpenAI API: Configured ✅\n";
        echo "   🎯 Attempting full cultural translation...\n\n";
        
        $result = $advancedService->translateWithCulturalContext(
            "Good morning! We're excited to announce our new partnership.",
            'en',
            'ar',
            [
                'domain' => 'business',
                'formality' => 'formal'
            ]
        );
        
        echo "   📥 Input: Good morning! We're excited to announce our new partnership.\n";
        echo "   📤 Output: " . ($result['translated_text'] ?? 'N/A') . "\n";
        echo "   📊 Quality Score: " . ($result['quality_score'] ?? 'N/A') . "\n";
        echo "   🎭 Domain: " . ($result['domain'] ?? 'N/A') . "\n";
        echo "   📋 Formality: " . ($result['formality'] ?? 'N/A') . "\n";
        echo "   ✅ Cultural Adaptation: " . (($result['metadata']['cultural_adaptation_applied'] ?? false) ? 'Applied' : 'Not Applied') . "\n";
        echo "   📝 Correction Layers: " . ($result['metadata']['correction_layers_applied'] ?? 'N/A') . "\n";
    } else {
        echo "   ⚠️ OpenAI API not configured - skipping live translation test\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST COMPLETE                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
