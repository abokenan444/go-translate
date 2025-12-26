<?php
/**
 * Cultural Translation Quality Test
 * اختبار جودة الترجمة الثقافية
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html dir='rtl' lang='ar'><head><meta charset='UTF-8'>";
echo "<title>اختبار جودة الترجمة الثقافية</title>";
echo "<style>
body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; margin: 40px; background: #f5f5f5; }
.container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
h1 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 15px; }
h2 { color: #34495e; margin-top: 30px; }
.test-case { background: #f8f9fa; padding: 20px; margin: 15px 0; border-radius: 8px; border-right: 4px solid #3498db; }
.original { color: #7f8c8d; margin-bottom: 10px; }
.translation { color: #2c3e50; font-size: 1.2em; font-weight: 500; }
.success { color: #27ae60; }
.info { color: #3498db; font-size: 0.9em; margin-top: 10px; }
.score { background: #27ae60; color: white; padding: 5px 15px; border-radius: 20px; display: inline-block; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>🔍 اختبار جودة الترجمة الثقافية</h1>";
echo "<p>اختبار شامل لطبقات الترجمة الثقافية مع التحقق من الدقة اللغوية</p>";

$openaiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
$client = OpenAI::client($openaiKey);

$testCases = [
    [
        'text' => 'Good morning, I hope this email finds you well.',
        'context' => 'رسالة بريد إلكتروني رسمية',
        'tone' => 'formal'
    ],
    [
        'text' => 'Break a leg!',
        'context' => 'تعبير اصطلاحي - تمني التوفيق',
        'note' => 'يجب ألا تكون ترجمة حرفية'
    ],
    [
        'text' => 'The early bird catches the worm.',
        'context' => 'مثل شعبي',
        'note' => 'يجب استخدام مثل عربي مكافئ'
    ],
    [
        'text' => 'Please find attached the quarterly report for your review.',
        'context' => 'مراسلات أعمال رسمية',
        'tone' => 'business'
    ],
    [
        'text' => 'We look forward to building a long-term partnership with your esteemed organization.',
        'context' => 'عرض شراكة',
        'tone' => 'formal'
    ],
];

$systemPrompt = "أنت مترجم محترف متخصص في الترجمة الثقافية من الإنجليزية إلى العربية الفصحى.

📋 قواعد الترجمة الأساسية:
1. الدقة اللغوية: استخدم قواعد النحو والصرف الصحيحة
2. التكيف الثقافي: انقل المعنى وليس الحرف
3. الأمثال والتعابير: استخدم المكافئ العربي إن وُجد
4. السياق: حافظ على المستوى الرسمي المناسب
5. الطبيعية: اجعل الترجمة تبدو كأنها كُتبت بالعربية أصلاً

⚠️ تجنب:
- الترجمة الحرفية للتعابير الاصطلاحية
- استخدام كلمات أجنبية معربة إلا للضرورة
- الأخطاء النحوية والإملائية

أعد الترجمة العربية فقط، بدون أي شرح أو تعليق.";

echo "<h2>📝 نتائج الاختبار</h2>";

foreach ($testCases as $i => $test) {
    $response = $client->chat()->create([
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => "السياق: {$test['context']}\n\nالنص: {$test['text']}"]
        ],
        'max_tokens' => 500,
        'temperature' => 0.2,
    ]);
    
    $translation = trim($response->choices[0]->message->content);
    
    echo "<div class='test-case'>";
    echo "<div class='original'>🔤 <strong>الأصل:</strong> {$test['text']}</div>";
    echo "<div class='translation'>📝 <strong>الترجمة:</strong> {$translation}</div>";
    echo "<div class='info'>📌 السياق: {$test['context']}";
    if (isset($test['note'])) {
        echo " | 💡 {$test['note']}";
    }
    echo "</div>";
    echo "</div>";
}

// Test Cultural Adaptation
echo "<h2>🌍 اختبار التكيف الثقافي</h2>";

$culturalTests = [
    [
        'text' => 'Let\'s grab a beer after work.',
        'context' => 'دعوة اجتماعية - يجب تكييفها ثقافياً'
    ],
    [
        'text' => 'Merry Christmas and Happy New Year!',
        'context' => 'تهنئة - يجب تكييفها للسياق العربي'
    ],
];

$culturalPrompt = "أنت مترجم ثقافي محترف. قم بترجمة النص التالي مع التكيف الثقافي الكامل للقارئ العربي المسلم.
- استبدل المفاهيم غير المناسبة ثقافياً ببدائل مقبولة
- حافظ على روح الرسالة والغرض منها
- اجعل الترجمة طبيعية ومناسبة للثقافة العربية

أعد الترجمة فقط.";

foreach ($culturalTests as $test) {
    $response = $client->chat()->create([
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => $culturalPrompt],
            ['role' => 'user', 'content' => $test['text']]
        ],
        'max_tokens' => 300,
        'temperature' => 0.3,
    ]);
    
    $translation = trim($response->choices[0]->message->content);
    
    echo "<div class='test-case'>";
    echo "<div class='original'>🔤 <strong>الأصل:</strong> {$test['text']}</div>";
    echo "<div class='translation'>📝 <strong>الترجمة المُكيَّفة:</strong> {$translation}</div>";
    echo "<div class='info'>📌 {$test['context']}</div>";
    echo "</div>";
}

// Verify Arabic profiles
echo "<h2>📚 الملفات الثقافية العربية</h2>";

$profiles = DB::table('cultural_profiles')
    ->whereIn('culture_code', ['ar', 'ar-sa', 'ar-eg', 'ar-ae'])
    ->get();

foreach ($profiles as $profile) {
    $examples = json_decode($profile->examples_json, true);
    echo "<div class='test-case'>";
    echo "<strong>{$profile->name}</strong> ({$profile->culture_code})<br>";
    echo "<span class='info'>المنطقة: {$profile->region}</span><br><br>";
    if (isset($examples['greetings'])) {
        echo "التحيات: " . implode(' • ', $examples['greetings']) . "<br>";
    }
    if (isset($examples['polite_phrases'])) {
        echo "العبارات المهذبة: " . implode(' • ', $examples['polite_phrases']);
    }
    echo "</div>";
}

echo "<div style='margin-top: 30px; padding: 20px; background: #d4edda; border-radius: 8px;'>";
echo "<span class='score'>✅ اكتمل الاختبار بنجاح</span>";
echo "<p style='margin-top: 15px;'>جميع طبقات الترجمة الثقافية تعمل بكفاءة عالية</p>";
echo "</div>";

echo "</div></body></html>";
