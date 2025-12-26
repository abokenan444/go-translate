<?php
/**
 * Fix Arabic Text Encoding in Cultural Profiles
 * إصلاح ترميز النصوص العربية في الملفات الثقافية
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          إصلاح وتحديث الملفات الثقافية                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Updated Arabic cultural profile with proper encoding
$arabicProfile = [
    'examples_json' => json_encode([
        'greetings' => [
            'السلام عليكم',
            'مرحباً',
            'أهلاً وسهلاً',
            'صباح الخير',
            'مساء النور'
        ],
        'polite_phrases' => [
            'من فضلك',
            'شكراً جزيلاً',
            'بارك الله فيك',
            'جزاك الله خيراً',
            'عفواً',
            'تفضل'
        ],
        'formal_closings' => [
            'مع خالص التحية والتقدير',
            'وتفضلوا بقبول فائق الاحترام',
            'دمتم بخير'
        ],
        'business_phrases' => [
            'نتطلع إلى التعاون معكم',
            'نأمل أن تحظى باهتمامكم الكريم',
            'نرجو التكرم بالاطلاع'
        ]
    ], JSON_UNESCAPED_UNICODE),
    'updated_at' => now()
];

// Update Saudi Arabic
DB::table('cultural_profiles')
    ->where('culture_code', 'ar-sa')
    ->update($arabicProfile);

echo "✅ تم تحديث الملف الثقافي السعودي\n";

// Add more Arabic cultural profiles if they don't exist
$arabicCultures = [
    [
        'code' => 'ar',
        'culture_code' => 'ar',
        'name' => 'العربية الفصحى',
        'locale' => 'ar',
        'region' => 'Arab World',
        'description' => 'اللغة العربية الفصحى المعيارية المستخدمة في الإعلام والتعليم والمراسلات الرسمية',
        'values_json' => json_encode([
            'formality' => 'high',
            'directness' => 'medium',
            'hierarchy' => 'high',
            'collectivism' => 'high',
            'time_orientation' => 'flexible'
        ], JSON_UNESCAPED_UNICODE),
        'examples_json' => json_encode([
            'greetings' => ['السلام عليكم ورحمة الله وبركاته', 'تحية طيبة وبعد'],
            'polite_phrases' => ['أرجو التفضل', 'نتقدم بجزيل الشكر', 'مع فائق الاحترام'],
            'idioms' => [
                'الصبر مفتاح الفرج',
                'في التأني السلامة',
                'العلم نور',
                'خير الكلام ما قل ودل'
            ]
        ], JSON_UNESCAPED_UNICODE),
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'code' => 'ar-eg',
        'culture_code' => 'ar-eg',
        'name' => 'العربية المصرية',
        'locale' => 'ar',
        'region' => 'North Africa',
        'description' => 'اللهجة المصرية المنتشرة في مصر، تتميز بالدفء والفكاهة في التواصل',
        'values_json' => json_encode([
            'formality' => 'medium',
            'directness' => 'medium',
            'hierarchy' => 'medium',
            'collectivism' => 'high',
            'humor' => 'high'
        ], JSON_UNESCAPED_UNICODE),
        'examples_json' => json_encode([
            'greetings' => ['إزيك', 'أهلاً وسهلاً', 'صباح الفل'],
            'polite_phrases' => ['لو سمحت', 'شكراً', 'متشكر جداً'],
        ], JSON_UNESCAPED_UNICODE),
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'code' => 'ar-ae',
        'culture_code' => 'ar-ae',
        'name' => 'العربية الإماراتية',
        'locale' => 'ar',
        'region' => 'Gulf',
        'description' => 'اللهجة الإماراتية المستخدمة في دولة الإمارات، تجمع بين الأصالة والحداثة',
        'values_json' => json_encode([
            'formality' => 'high',
            'directness' => 'low',
            'hierarchy' => 'high',
            'collectivism' => 'high',
            'hospitality' => 'very_high'
        ], JSON_UNESCAPED_UNICODE),
        'examples_json' => json_encode([
            'greetings' => ['هلا والله', 'السلام عليكم', 'مرحبا'],
            'polite_phrases' => ['تسلم', 'ما قصرت', 'الله يعطيك العافية'],
        ], JSON_UNESCAPED_UNICODE),
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($arabicCultures as $culture) {
    $exists = DB::table('cultural_profiles')
        ->where('culture_code', $culture['culture_code'])
        ->exists();
    
    if (!$exists) {
        DB::table('cultural_profiles')->insert($culture);
        echo "✅ تمت إضافة الملف الثقافي: {$culture['name']}\n";
    } else {
        DB::table('cultural_profiles')
            ->where('culture_code', $culture['culture_code'])
            ->update([
                'examples_json' => $culture['examples_json'],
                'values_json' => $culture['values_json'],
                'updated_at' => now()
            ]);
        echo "✅ تم تحديث الملف الثقافي: {$culture['name']}\n";
    }
}

// Verify the fix
echo "\n═══════════════════════════════════════\n";
echo "التحقق من الإصلاحات:\n";
echo "═══════════════════════════════════════\n";

$profiles = DB::table('cultural_profiles')
    ->whereIn('culture_code', ['ar', 'ar-sa', 'ar-eg', 'ar-ae'])
    ->get();

foreach ($profiles as $profile) {
    echo "\n【{$profile->name}】\n";
    $examples = json_decode($profile->examples_json, true);
    if (isset($examples['greetings'])) {
        echo "   التحيات: " . implode(' | ', array_slice($examples['greetings'], 0, 3)) . "\n";
    }
    if (isset($examples['polite_phrases'])) {
        echo "   العبارات المهذبة: " . implode(' | ', array_slice($examples['polite_phrases'], 0, 3)) . "\n";
    }
}

echo "\n✅ تم إصلاح جميع الملفات الثقافية بنجاح!\n";
