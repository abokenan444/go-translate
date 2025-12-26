<?php
/**
 * Cultural Translation Quality Audit
 * فحص جودة الترجمة الثقافية
 */

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     تدقيق جودة نظام الترجمة الثقافية - CulturalTranslate      ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// 1. Check Database Encoding
echo "【1】 فحص ترميز قاعدة البيانات:\n";
echo "─────────────────────────────────────\n";
$encoding = DB::select("SHOW VARIABLES LIKE 'character_set%'");
foreach ($encoding as $row) {
    echo "   • {$row->Variable_name}: {$row->Value}\n";
}
echo "\n";

// 2. Check Cultural Profiles
echo "【2】 فحص الملفات الثقافية:\n";
echo "─────────────────────────────────────\n";
$profiles = DB::table('cultural_profiles')->limit(5)->get();
if ($profiles->isEmpty()) {
    echo "   ⚠️  لا توجد ملفات ثقافية - يجب إضافتها\n";
} else {
    foreach ($profiles as $p) {
        echo "   • {$p->culture_code}: {$p->culture_name}\n";
    }
}
echo "\n";

// 3. Test OpenAI Translation Quality
echo "【3】 اختبار جودة الترجمة (OpenAI GPT-4o):\n";
echo "─────────────────────────────────────\n";

$testCases = [
    [
        'text' => 'Good morning, I hope this email finds you well.',
        'source' => 'en',
        'target' => 'ar',
        'context' => 'business_email',
        'expected_tone' => 'formal'
    ],
    [
        'text' => 'Break a leg!',
        'source' => 'en', 
        'target' => 'ar',
        'context' => 'idiom',
        'note' => 'Should NOT be literal translation'
    ],
    [
        'text' => 'The early bird catches the worm.',
        'source' => 'en',
        'target' => 'ar', 
        'context' => 'proverb',
        'note' => 'Should use Arabic equivalent proverb'
    ],
];

$openaiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
if (empty($openaiKey)) {
    echo "   ❌ مفتاح OpenAI غير مُعد\n";
} else {
    $client = OpenAI::client($openaiKey);
    
    foreach ($testCases as $i => $test) {
        echo "\n   【Test " . ($i + 1) . "】\n";
        echo "   النص الأصلي: {$test['text']}\n";
        echo "   السياق: {$test['context']}\n";
        
        $systemPrompt = "أنت مترجم محترف متخصص في الترجمة الثقافية من الإنجليزية إلى العربية.
        
قواعد الترجمة:
1. لا تترجم حرفياً - احرص على نقل المعنى والسياق الثقافي
2. استخدم التعابير والأمثال العربية المكافئة عند وجودها
3. حافظ على المستوى الرسمي المناسب للسياق
4. تأكد من سلامة القواعد النحوية والإملائية
5. اجعل الترجمة طبيعية كأنها كُتبت بالعربية أصلاً

السياق: {$test['context']}

أعد الترجمة فقط، بدون أي شرح أو تعليق.";

        try {
            $response = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $test['text']]
                ],
                'max_tokens' => 500,
                'temperature' => 0.3,
            ]);
            
            $translation = trim($response->choices[0]->message->content);
            echo "   الترجمة: {$translation}\n";
            
            if (isset($test['note'])) {
                echo "   ملاحظة: {$test['note']}\n";
            }
            echo "   ✅ تمت الترجمة بنجاح\n";
            
        } catch (Exception $e) {
            echo "   ❌ خطأ: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n";

// 4. Check Translation Services
echo "【4】 فحص خدمات الترجمة المتاحة:\n";
echo "─────────────────────────────────────\n";

$services = [
    'App\Services\TranslationService' => 'خدمة الترجمة الأساسية',
    'App\Services\ContextAnalysisEngine' => 'محرك تحليل السياق',
    'App\Services\CulturalAdaptationEngine' => 'محرك التكيف الثقافي',
    'App\Services\TranslationQualityScoreService' => 'خدمة تقييم الجودة',
    'App\Services\LinguisticIntegrityService' => 'خدمة السلامة اللغوية',
    'App\Services\CulturalRiskEngine' => 'محرك المخاطر الثقافية',
    'App\Services\BrandVoiceEngine' => 'محرك صوت العلامة التجارية',
];

foreach ($services as $class => $name) {
    if (class_exists($class)) {
        echo "   ✅ {$name}\n";
    } else {
        echo "   ❌ {$name} (غير موجود)\n";
    }
}

echo "\n";

// 5. Arabic Text Quality Check
echo "【5】 فحص جودة النص العربي:\n";
echo "─────────────────────────────────────\n";

$arabicSamples = [
    'مرحباً بكم في منصة الترجمة الثقافية',
    'نقدم خدمات ترجمة احترافية مع الحفاظ على السياق الثقافي',
    'شركتنا رائدة في مجال الذكاء الثقافي والتواصل المعتمد',
];

foreach ($arabicSamples as $sample) {
    // Check for common Arabic text issues
    $issues = [];
    
    // Check for mixed direction
    if (preg_match('/[a-zA-Z]/', $sample)) {
        $issues[] = 'يحتوي على أحرف لاتينية';
    }
    
    // Check for proper Arabic
    if (!preg_match('/[\x{0600}-\x{06FF}]/u', $sample)) {
        $issues[] = 'لا يحتوي على نص عربي';
    }
    
    if (empty($issues)) {
        echo "   ✅ \"{$sample}\"\n";
    } else {
        echo "   ⚠️  \"{$sample}\" - " . implode(', ', $issues) . "\n";
    }
}

echo "\n";
echo "══════════════════════════════════════════════════════════════\n";
echo "                    انتهى التدقيق بنجاح ✓\n";
echo "══════════════════════════════════════════════════════════════\n";
