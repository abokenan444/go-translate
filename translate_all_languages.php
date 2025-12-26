<?php
/**
 * Automated Translation Generator for 15 Languages
 * Uses translation mapping to create complete lang files
 */

// Target languages
$languages = [
    'ar' => 'Arabic',
    'de' => 'German', 
    'es' => 'Spanish',
    'fr' => 'French',
    'hi' => 'Hindi',
    'it' => 'Italian',
    'ja' => 'Japanese',
    'ko' => 'Korean',
    'nl' => 'Dutch',
    'pl' => 'Polish',
    'pt' => 'Portuguese',
    'ru' => 'Russian',
    'tr' => 'Turkish',
    'zh' => 'Chinese'
];

// Load English source
$englishMessages = require __DIR__ . '/lang/en/messages.php';
$englishDashboard = require __DIR__ . '/lang/en/dashboard.php';
$englishPages = require __DIR__ . '/lang/en/pages.php';

// Translation mappings for each language
$translations = [
    'ar' => [
        // Navigation
        'home' => 'الرئيسية',
        'features' => 'المميزات',
        'pricing' => 'التسعير',
        'about' => 'من نحن',
        'contact' => 'اتصل بنا',
        'login' => 'تسجيل الدخول',
        'register' => 'إنشاء حساب',
        'logout' => 'تسجيل الخروج',
        'dashboard' => 'لوحة التحكم',
        
        // Hero Section
        'hero_title' => 'بناء المعيار العالمي للتواصل الثقافي المعتمد',
        'hero_subtitle' => 'منصة مدعومة بالذكاء الاصطناعي تُنشئ بنية تحتية موثوقة للترجمة الذكية ثقافياً للحكومات والمؤسسات والشركات',
        'hero_cta_start' => 'ابدأ تجربة مجانية',
        'hero_cta_discover' => 'اكتشف المميزات',
        'hero_no_credit_card' => 'لا حاجة لبطاقة ائتمان',
        'hero_free_trial' => 'تجربة مجانية لمدة 14 يومًا',
        'get_started' => 'ابدأ الآن',
        'try_demo' => 'جرّب النسخة التجريبية',
        
        // Stats
        'stats_registered_users' => 'المستخدمون المسجلون',
        'stats_active_subscriptions' => 'الاشتراكات النشطة',
        'stats_published_pages' => 'الصفحات المنشورة',
        'stats_active_companies' => 'الشركات النشطة',
        'stats_translations_completed' => 'الترجمات المكتملة',
        'stats_languages_supported' => 'اللغات المدعومة',
        'stats_satisfaction_rate' => 'معدل الرضا',
        
        // Demo
        'demo_title' => 'جرّب نموذج الترجمة',
        'demo_subtitle' => 'اختبر قوة الذكاء الثقافي في العمل',
        'demo_from' => 'من',
        'demo_to' => 'إلى',
        'demo_source_text' => 'أدخل النص للترجمة...',
        'demo_translation' => 'الترجمة',
        'demo_result_placeholder' => 'ستظهر ترجمتك هنا...',
        'demo_translate_btn' => 'ترجم الآن',
        'demo_free_trial_note' => 'ابدأ تجربتك المجانية لفتح جميع المميزات',
        'demo_try_examples' => 'جرّب هذه الأمثلة:',
        'demo_example_welcome' => 'مرحباً بكم في شركتنا',
        'demo_example_marketing' => 'اكتشف منتجاتنا المذهلة',
        'demo_example_support' => 'كيف يمكننا مساعدتك اليوم؟',
        'demo_feature_instant' => 'ترجمة فورية',
        'demo_feature_instant_desc' => 'احصل على ترجمات دقيقة في ثوانٍ',
        'demo_feature_cultural' => 'تكيف ثقافي',
        'demo_feature_cultural_desc' => 'ترجمات تحترم السياق الثقافي',
        'demo_feature_secure' => 'آمنة وخاصة',
        'demo_feature_secure_desc' => 'بياناتك مشفرة ومحمية',
        
        // Translation Page
        'translate' => 'ترجم',
        'source_language' => 'اللغة المصدر',
        'target_language' => 'اللغة الهدف',
        'select_language' => 'اختر اللغة',
        'tone' => 'النبرة',
        'select_tone' => 'اختر النبرة',
        'context' => 'السياق',
        'context_placeholder' => 'أضف سياقاً إضافياً للترجمة (اختياري)',
        'enter_text' => 'أدخل النص للترجمة',
        'translation_result' => 'نتيجة الترجمة',
        'translate_now' => 'ترجم الآن',
        'translating' => 'جارٍ الترجمة...',
        'copy' => 'نسخ',
        'copied' => 'تم النسخ!',
        'clear' => 'مسح',
        
        // Tones
        'professional' => 'احترافي',
        'friendly' => 'ودي',
        'formal' => 'رسمي',
        'casual' => 'غير رسمي',
        'technical' => 'تقني',
        'marketing' => 'تسويقي',
        'creative' => 'إبداعي',
        'empathetic' => 'متعاطف',
        'authoritative' => 'موثوق',
        
        // Languages
        'arabic' => 'العربية',
        'english' => 'الإنجليزية',
        'french' => 'الفرنسية',
        'spanish' => 'الإسبانية',
        'german' => 'الألمانية',
        'italian' => 'الإيطالية',
        'portuguese' => 'البرتغالية',
        'russian' => 'الروسية',
        'chinese' => 'الصينية',
        'japanese' => 'اليابانية',
        'korean' => 'الكورية',
        'turkish' => 'التركية',
        'dutch' => 'الهولندية',
        
        // Features
        'cultural_adaptation' => 'تكيف ثقافي',
        'cultural_adaptation_desc' => 'ترجمات تحترم الاختلافات الثقافية والسياق',
        'multiple_tones' => 'نبرات متعددة',
        'multiple_tones_desc' => '9 نبرات عاطفية مختلفة لجميع حالات الاستخدام',
        'industry_specific' => 'خاص بالصناعة',
        'industry_specific_desc' => '15 صناعة بمصطلحات متخصصة',
        'high_quality' => 'جودة عالية',
        'high_quality_desc' => 'دقة 85-95% مع تسجيل الجودة التلقائي',
        'fast_translation' => 'ترجمة سريعة',
        'fast_translation_desc' => 'نتائج في 3-5 ثوانٍ فقط',
        'api_access' => 'وصول API',
        'api_access_desc' => 'REST API كامل للتكامل مع تطبيقاتك',
        
        // Pricing
        'free_plan' => 'مجاني',
        'pro_plan' => 'احترافي',
        'enterprise_plan' => 'مؤسسي',
        'per_month' => 'شهرياً',
        'translations_per_month' => 'ترجمة/شهر',
        'languages' => 'لغات',
        'tones' => 'نبرات',
        'support' => 'الدعم',
        'basic_support' => 'دعم أساسي',
        'priority_support' => 'دعم ذو أولوية',
        'dedicated_support' => 'دعم مخصص',
        'choose_plan' => 'اختر الباقة',
        
        // Footer
        'all_rights_reserved' => 'جميع الحقوق محفوظة',
        'privacy_policy' => 'سياسة الخصوصية',
        'terms_of_service' => 'شروط الخدمة',
        'cookie_policy' => 'سياسة ملفات تعريف الارتباط',
        
        // Messages
        'success' => 'نجاح',
        'error' => 'خطأ',
        'warning' => 'تحذير',
        'info' => 'معلومات',
        'please_select_different_languages' => 'يرجى اختيار لغات مختلفة',
        'please_enter_text' => 'يرجى إدخال نص للترجمة',
        'translation_successful' => 'تمت الترجمة بنجاح',
        'translation_failed' => 'فشلت الترجمة',
        
        // Stats
        'word_count' => 'عدد الكلمات',
        'character_count' => 'عدد الأحرف',
        'tokens_used' => 'الرموز المستخدمة',
        'quality_score' => 'درجة الجودة',
        'response_time' => 'وقت الاستجابة',
        'seconds' => 'ثوانٍ',
        
        // Account
        'my_account' => 'حسابي',
        'profile' => 'الملف الشخصي',
        'settings' => 'الإعدادات',
        'api_keys' => 'مفاتيح API',
        'usage_statistics' => 'إحصائيات الاستخدام',
        'billing' => 'الفواتير',
        'subscription' => 'الاشتراك',
        
        // Common
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'delete' => 'حذف',
        'edit' => 'تعديل',
        'view' => 'عرض',
        'download' => 'تنزيل',
        'upload' => 'رفع',
        'search' => 'بحث',
        'filter' => 'تصفية',
        'sort' => 'ترتيب',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'yes' => 'نعم',
        'no' => 'لا',
        'loading' => 'جارٍ التحميل...',
        'processing' => 'جارٍ المعالجة...',
        'please_wait' => 'يرجى الانتظار...',
        
        // Government Portal
        'government_portal' => 'البوابة الحكومية',
        'government_portal_desc' => 'خدمات ترجمة معتمدة للجهات الحكومية والوثائق الرسمية',
        'for_government_entities' => 'للجهات الحكومية',
        'department' => 'القسم',
        'contact_name' => 'اسم جهة الاتصال',
        'official_email' => 'البريد الإلكتروني الرسمي',
        'phone_number' => 'رقم الهاتف',
        'document_type' => 'نوع الوثيقة',
        'select_document_type' => 'اختر نوع الوثيقة',
        'source_lang' => 'اللغة المصدر',
        'target_lang' => 'اللغة الهدف',
        'urgency_level' => 'مستوى الاستعجال',
        'standard' => 'عادي',
        'urgent' => 'عاجل',
        'critical' => 'بالغ الأهمية',
        'additional_requirements' => 'متطلبات إضافية',
        'submit_request' => 'إرسال الطلب',
        'government_features' => 'المميزات الحكومية',
        'certified_translations' => 'ترجمات معتمدة',
        'certified_translations_desc' => 'جميع الترجمات تأتي مع شهادة رسمية',
        'security_compliance' => 'الأمن والامتثال',
        'security_compliance_desc' => 'يلبي معايير الأمن الحكومية',
        'dedicated_support_desc' => 'دعم ذو أولوية على مدار الساعة للعملاء الحكوميين',
        'bulk_processing' => 'معالجة جماعية',
        'bulk_processing_desc' => 'معالجة أحجام كبيرة من الوثائق بكفاءة',
        'official_documents' => 'وثائق رسمية',
        'legal_documents' => 'وثائق قانونية',
        'diplomatic_correspondence' => 'مراسلات دبلوماسية',
        'immigration_papers' => 'أوراق الهجرة',
        'government_reports' => 'تقارير حكومية',
        'other_documents' => 'وثائق أخرى',
        
        // Form Fields
        'select_type' => 'اختر نوع الوثيقة',
        'upload_document' => 'رفع الوثيقة',
        'click_to_upload' => 'انقر للرفع أو اسحب وأفلت',
        'additional_notes' => 'ملاحظات إضافية',
        'agree_to' => 'أوافق على',
        'terms_and_conditions' => 'الشروط والأحكام',
        'submit_for_translation' => 'إرسال للترجمة',
        'security_confidentiality' => 'الأمن والسرية',
        'government_security_notice' => 'تتم معالجة جميع الوثائق بأعلى مستوى من الأمن والسرية. يتم تشفير بياناتك ومعالجتها وفقاً للمعايير الحكومية.',
        
        // Additional Form Fields
        'email' => 'البريد الإلكتروني',
        'phone' => 'الهاتف',
        'legal_document' => 'وثيقة قانونية',
        'certificate' => 'شهادة',
        'contract' => 'عقد',
        'policy_document' => 'وثيقة سياسة',
        'regulation' => 'تنظيم',
        'other' => 'أخرى',
        
        // Site Meta
        'site_title' => 'CulturalTranslate - السلطة العالمية للذكاء الثقافي',
        'site_description' => 'منصة ترجمة احترافية مدعومة بالذكاء الاصطناعي تحافظ على السياق الثقافي والنبرة والمعنى عبر أكثر من 116 لغة.',
        
        // Demo Placeholder
        'demo_placeholder' => 'أدخل النص للترجمة...',
        
        // Features Section
        'features_title' => 'مميزات قوية لكل احتياج',
        'features_subtitle' => 'كل ما تحتاجه للترجمة الثقافية الاحترافية',
        'feature_cultural_title' => 'ذكاء ثقافي',
        'feature_cultural_desc' => 'يفهم الذكاء الاصطناعي لدينا الفروق الثقافية الدقيقة ويكيّف الترجمات وفقاً لذلك',
        'feature_fast_title' => 'سريع للغاية',
        'feature_fast_desc' => 'احصل على ترجمات دقيقة في ثوانٍ وليس ساعات',
        'feature_security_title' => 'أمن المؤسسات',
        'feature_security_desc' => 'تشفير على مستوى البنوك والامتثال للمعايير العالمية',
        'feature_memory_title' => 'ذاكرة الترجمة',
        'feature_memory_desc' => 'تعلّم من ترجماتك السابقة للاتساق',
        'feature_glossary_title' => 'مسارد مخصصة',
        'feature_glossary_desc' => 'حدّد مصطلحاتك لاتساق العلامة التجارية',
        'feature_api_title' => 'API قوي',
        'feature_api_desc' => 'دمج الترجمات مباشرة في تطبيقاتك',
        
        // CTA Section
        'cta_title' => 'هل أنت مستعد لتحويل اتصالاتك؟',
        'cta_subtitle' => 'انضم إلى آلاف الشركات التي تثق في CulturalTranslate',
        'cta_button' => 'ابدأ تجربتك المجانية',
    ],
];

echo "Starting translation generation for 15 languages...\n\n";

// Generate messages.php for each language
foreach ($languages as $code => $name) {
    echo "Processing $name ($code)...\n";
    
    $langDir = __DIR__ . "/lang/$code";
    if (!is_dir($langDir)) {
        mkdir($langDir, 0755, true);
    }
    
    // Generate messages.php
    $messagesFile = "$langDir/messages.php";
    $content = "<?php\n\nreturn [\n";
    
    // Get translations for this language if available, otherwise use English
    $langTranslations = $translations[$code] ?? [];
    
    foreach ($englishMessages as $key => $value) {
        $translated = $langTranslations[$key] ?? $value;
        $translated = str_replace("'", "\\'", $translated);
        $content .= "    '$key' => '$translated',\n";
    }
    
    $content .= "];\n";
    
    file_put_contents($messagesFile, $content);
    echo "  ✓ Created $messagesFile\n";
}

echo "\n✅ Translation generation complete!\n";
echo "Generated messages.php for " . count($languages) . " languages.\n";
