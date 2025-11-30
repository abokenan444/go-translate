<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CulturalProfilesSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            // 1. Arabic Culture
            [
                'culture_code' => 'ar',
                'culture_name' => 'العربية',
                'native_name' => 'العربية',
                'description' => 'الثقافة العربية الإسلامية مع التركيز على القيم التقليدية والاحترام',
                'characteristics' => json_encode([
                    'hospitality' => 'كرم الضيافة من أهم القيم',
                    'family_oriented' => 'التركيز القوي على العائلة والروابط الأسرية',
                    'respect_for_elders' => 'احترام كبير للكبار والسلطة',
                    'religious_values' => 'القيم الإسلامية مهمة في التواصل',
                    'indirect_communication' => 'التواصل غير المباشر والمهذب',
                ]),
                'preferred_tones' => json_encode(['formal', 'respectful', 'warm', 'professional']),
                'taboos' => json_encode([
                    'avoid_direct_criticism',
                    'no_alcohol_references',
                    'modest_imagery',
                    'respect_religious_values',
                    'avoid_controversial_topics',
                ]),
                'special_styles' => json_encode([
                    'use_honorifics' => 'استخدام الألقاب والمسميات الاحترامية',
                    'formal_greetings' => 'التحيات الرسمية مهمة',
                    'elaborate_language' => 'اللغة المزخرفة والبليغة محببة',
                ]),
                'symbols_references' => json_encode([
                    'palm_trees' => 'النخيل رمز للخير والبركة',
                    'coffee' => 'القهوة رمز للضيافة',
                    'calligraphy' => 'الخط العربي فن وثقافة',
                ]),
                'formality_level' => 'formal',
                'directness' => 'indirect',
                'uses_honorifics' => true,
                'emotional_expressiveness' => 7,
                'common_expressions' => json_encode([
                    'إن شاء الله',
                    'ما شاء الله',
                    'بارك الله فيك',
                    'جزاك الله خيراً',
                    'أهلاً وسهلاً',
                ]),
                'marketing_preferences' => json_encode([
                    'family_values' => 'التركيز على القيم العائلية',
                    'trust_building' => 'بناء الثقة مهم جداً',
                    'testimonials' => 'الشهادات والتوصيات فعالة',
                    'luxury_appeal' => 'الفخامة والجودة محببة',
                ]),
                'business_etiquette' => json_encode([
                    'relationship_first' => 'العلاقات الشخصية قبل الأعمال',
                    'patience' => 'الصبر مهم في المفاوضات',
                    'respect_hierarchy' => 'احترام التسلسل الهرمي',
                ]),
                'text_direction' => 'rtl',
                'date_formats' => json_encode(['d/m/Y', 'Y-m-d']),
                'number_formats' => json_encode(['decimal' => '٫', 'thousands' => '٬']),
                'currency_symbol' => 'ر.س',
                'system_prompt' => 'أنت مترجم محترف متخصص في الثقافة العربية. احرص على استخدام لغة مهذبة ومحترمة، مع مراعاة القيم الإسلامية والعربية. استخدم الألقاب المناسبة وتجنب المواضيع الحساسة.',
                'translation_guidelines' => 'استخدم الفصحى المبسطة، تجنب العامية إلا عند الضرورة، احترم القيم الدينية والاجتماعية، استخدم التعابير المحلية المناسبة.',
                'is_active' => true,
                'priority' => 1,
            ],
            
            // 2. English (US) Culture
            [
                'culture_code' => 'en',
                'culture_name' => 'English (US)',
                'native_name' => 'English',
                'description' => 'American English culture with emphasis on directness and individualism',
                'characteristics' => json_encode([
                    'direct_communication' => 'Clear and straightforward communication',
                    'individualism' => 'Focus on personal achievement',
                    'innovation' => 'Value creativity and new ideas',
                    'efficiency' => 'Time is money mentality',
                    'casual_friendly' => 'Generally informal and friendly',
                ]),
                'preferred_tones' => json_encode(['professional', 'friendly', 'casual', 'direct']),
                'taboos' => json_encode([
                    'avoid_excessive_formality',
                    'respect_personal_space',
                    'avoid_age_gender_discrimination',
                    'be_inclusive',
                ]),
                'special_styles' => json_encode([
                    'action_oriented' => 'Focus on results and action',
                    'positive_language' => 'Optimistic and can-do attitude',
                    'conversational' => 'Conversational and engaging tone',
                ]),
                'symbols_references' => json_encode([
                    'american_dream' => 'Success through hard work',
                    'freedom' => 'Individual freedom and choice',
                    'innovation' => 'Technology and progress',
                ]),
                'formality_level' => 'casual',
                'directness' => 'very_direct',
                'uses_honorifics' => false,
                'emotional_expressiveness' => 6,
                'common_expressions' => json_encode([
                    'How are you?',
                    'Have a great day!',
                    'Let\'s touch base',
                    'Sounds good!',
                    'No worries',
                ]),
                'marketing_preferences' => json_encode([
                    'value_proposition' => 'Clear benefits and value',
                    'social_proof' => 'Reviews and ratings matter',
                    'convenience' => 'Easy and fast solutions',
                    'personalization' => 'Tailored experiences',
                ]),
                'business_etiquette' => json_encode([
                    'punctuality' => 'Being on time is important',
                    'direct_negotiation' => 'Straightforward business deals',
                    'informal_meetings' => 'Casual business atmosphere',
                ]),
                'text_direction' => 'ltr',
                'date_formats' => json_encode(['m/d/Y', 'Y-m-d']),
                'number_formats' => json_encode(['decimal' => '.', 'thousands' => ',']),
                'currency_symbol' => '$',
                'system_prompt' => 'You are a professional translator specialized in American English culture. Use clear, direct, and friendly language. Be conversational but professional. Focus on benefits and value.',
                'translation_guidelines' => 'Use American English spelling, be direct and clear, avoid excessive formality, use active voice, keep it conversational yet professional.',
                'is_active' => true,
                'priority' => 2,
            ],
        ];

        foreach ($profiles as $profile) {
            DB::table('cultural_profiles')->updateOrInsert(
                ['culture_code' => $profile['culture_code']],
                array_merge($profile, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
