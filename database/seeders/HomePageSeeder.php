<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\MenuItem;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الصفحة الرئيسية
        $homePage = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'الصفحة الرئيسية',
                'content' => 'منصة الترجمة الثقافية بالذكاء الاصطناعي',
                'status' => 'published',
                'meta_title' => 'منصة الترجمة الثقافية - CulturalTranslate',
                'meta_description' => 'ترجمة المحتوى مع الحفاظ على السياق الثقافي وصوت العلامة التجارية والمعنى',
                'show_in_header' => false,
                'show_in_footer' => false,
            ]
        );

        // حذف الأقسام القديمة إذا كانت موجودة
        $homePage->sections()->delete();

        // قسم Hero الرئيسي
        PageSection::create([
            'page_id' => $homePage->id,
            'section_type' => 'hero',
            'title' => 'منصة الترجمة الثقافية بالذكاء الاصطناعي',
            'subtitle' => 'ترجمة المحتوى مع الحفاظ على السياق الثقافي وصوت العلامة التجارية والمعنى',
            'button_text' => 'ابدأ الآن',
            'button_link' => '/register',
            'button_text_secondary' => 'اكتشف المزيد',
            'button_link_secondary' => '#features',
            'order' => 1,
            'is_active' => true,
            'data' => [
                'badge_text' => 'بدون بطاقة ائتمان',
                'badge_secondary' => 'تجربة مجانية',
            ],
        ]);

        // قسم الإحصائيات
        PageSection::create([
            'page_id' => $homePage->id,
            'section_type' => 'stats',
            'order' => 2,
            'is_active' => true,
            'data' => [
                'stats' => [
                    [
                        'number' => '3',
                        'label' => 'المستخدمون المسجلون',
                    ],
                    [
                        'number' => '0',
                        'label' => 'الاشتراكات النشطة',
                    ],
                    [
                        'number' => '0',
                        'label' => 'الصفحات المنشورة',
                    ],
                    [
                        'number' => '0',
                        'label' => 'الشركات النشطة',
                    ],
                ],
            ],
        ]);

        // قسم التجربة (Demo)
        PageSection::create([
            'page_id' => $homePage->id,
            'section_type' => 'demo',
            'title' => 'جرّب الترجمة الثقافية الآن',
            'subtitle' => 'اختبر قوة الترجمة الذكية مع الحفاظ على السياق الثقافي',
            'button_text' => 'ترجمة الآن',
            'button_link' => '#',
            'order' => 3,
            'is_active' => true,
            'data' => [
                'note' => '💡 تجربة مجانية - لا حاجة لبطاقة ائتمان',
                'examples' => [
                    'مثال: رسالة ترحيب',
                    'مثال: تسويقي',
                    'مثال: خدمة عملاء',
                ],
            ],
        ]);

        // قسم المميزات
        PageSection::create([
            'page_id' => $homePage->id,
            'section_type' => 'features',
            'title' => 'مميزات قوية',
            'subtitle' => 'كل ما تحتاجه لترجمة المحتوى مع الحفاظ على السياق الثقافي وصوت العلامة التجارية',
            'order' => 4,
            'is_active' => true,
            'data' => [
                'features' => [
                    [
                        'icon' => '🌍',
                        'title' => 'التكيف الثقافي',
                        'description' => 'الحفاظ على السياق الثقافي المدعوم بالذكاء الاصطناعي يضمن أن رسالتك تلقى صدى لدى الجماهير المحلية',
                    ],
                    [
                        'icon' => '⚡',
                        'title' => 'سريع للغاية',
                        'description' => 'ترجمة آلاف الكلمات في ثوانٍ باستخدام نماذج الذكاء الاصطناعي المحسّنة',
                    ],
                    [
                        'icon' => '🔒',
                        'title' => 'أمان المؤسسات',
                        'description' => 'متوافق مع GDPR، معتمد SOC 2 Type II، مع تشفير من طرف إلى طرف',
                    ],
                    [
                        'icon' => '💾',
                        'title' => 'ذاكرة الترجمة',
                        'description' => 'وفر التكاليف من خلال إعادة استخدام الترجمات السابقة والحفاظ على الاتساق',
                    ],
                    [
                        'icon' => '📚',
                        'title' => 'مسارد مخصصة',
                        'description' => 'حدد المصطلحات الخاصة بالعلامة التجارية للحصول على ترجمات متسقة',
                    ],
                    [
                        'icon' => '🔌',
                        'title' => 'API صديق للمطورين',
                        'description' => 'RESTful API مع SDKs بلغات متعددة وتوثيق شامل',
                    ],
                ],
            ],
        ]);

        // قسم CTA النهائي
        PageSection::create([
            'page_id' => $homePage->id,
            'section_type' => 'cta',
            'title' => 'هل أنت مستعد للانطلاق عالمياً؟',
            'subtitle' => 'انضم إلى منصة الترجمة الثقافية للوصول إلى جماهير عالمية',
            'button_text' => 'ابدأ الآن',
            'button_link' => '/register',
            'order' => 5,
            'is_active' => true,
        ]);

        // إنشاء عناصر قائمة الهيدر
        MenuItem::firstOrCreate(
            ['location' => 'header', 'title' => 'Home', 'url' => '/'],
            ['order' => 1, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'header', 'title' => 'Features', 'url' => '/features'],
            ['order' => 2, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'header', 'title' => 'Pricing', 'url' => '/pricing'],
            ['order' => 3, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'header', 'title' => 'Use Cases', 'url' => '/use-cases'],
            ['order' => 4, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'header', 'title' => 'API Docs', 'url' => '/api-docs'],
            ['order' => 5, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'header', 'title' => 'About', 'url' => '/about'],
            ['order' => 6, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'header', 'title' => 'Contact', 'url' => '/contact'],
            ['order' => 7, 'is_active' => true]
        );

        // إنشاء عناصر قائمة الفوتر
        MenuItem::firstOrCreate(
            ['location' => 'footer', 'title' => 'Privacy Policy', 'url' => '/privacy'],
            ['order' => 1, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'footer', 'title' => 'Terms of Service', 'url' => '/terms'],
            ['order' => 2, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'footer', 'title' => 'Security', 'url' => '/security'],
            ['order' => 3, 'is_active' => true]
        );

        MenuItem::firstOrCreate(
            ['location' => 'footer', 'title' => 'GDPR', 'url' => '/gdpr'],
            ['order' => 4, 'is_active' => true]
        );

        $this->command->info('✅ تم إضافة البيانات الأولية للصفحة الرئيسية بنجاح!');
    }
}
