<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CulturalPromptSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Cultures
        $cultures = [
            [
                'slug' => 'gulf_arabic',
                'name' => 'الخليج العربي – محتوى محافظ بإلهام عاطفي',
                'region' => 'GCC',
                'primary_language' => 'ar',
                'traits' => json_encode([
                    'محافظ دينياً',
                    'حساسية اجتماعية عالية',
                    'تقدير للعائلة والمجتمع',
                    'تفضيل لغة محترمة مع لمسة إلهام',
                ]),
            ],
            [
                'slug' => 'levant_arabic',
                'name' => 'المشرق العربي – أسلوب دافئ وعاطفي',
                'region' => 'Levant',
                'primary_language' => 'ar',
                'traits' => json_encode([
                    'دفء عاطفي',
                    'تقدير للقصص والتعبير العاطفي',
                    'مرونة في الفكاهة الخفيفة',
                ]),
            ],
            [
                'slug' => 'nl_dutch_direct',
                'name' => 'هولندا – أسلوب مباشر وصريح',
                'region' => 'Netherlands',
                'primary_language' => 'nl',
                'traits' => json_encode([
                    'صراحة عالية',
                    'تركيز على الوضوح والشفافية',
                    'حساسية كبيرة لمواضيع التمييز',
                ]),
            ],
            [
                'slug' => 'uk_english_brand',
                'name' => 'المملكة المتحدة – أسلوب مهني مع لمسة ود',
                'region' => 'UK',
                'primary_language' => 'en',
                'traits' => json_encode([
                    'لباقة',
                    'مزج بين الرسمية والود',
                    'تجنّب المبالغة التسويقية',
                ]),
            ],
        ];

        foreach ($cultures as $culture) {
            DB::table('cultures')->updateOrInsert(
                ['slug' => $culture['slug']],
                array_merge($culture, ['updated_at' => $now, 'created_at' => $now])
            );
        }

        // Tones
        $tones = [
            ['slug' => 'professional', 'name' => 'احترافي', 'description' => 'رسمي، واضح، موجه للأعمال.'],
            ['slug' => 'emotional_inspiring', 'name' => 'عاطفي ملهم', 'description' => 'يركز على الإلهام والأمل والدعم.'],
            ['slug' => 'casual_friendly', 'name' => 'ودّي غير رسمي', 'description' => 'بسيط، قريب من الحديث اليومي.'],
            ['slug' => 'direct_concise', 'name' => 'مباشر ومختصر', 'description' => 'يذهب مباشرة للنقطة بدون زخرفة.'],
            ['slug' => 'marketing_conversion', 'name' => 'تسويقي مع تركيز على التحويل', 'description' => 'يحفّز القارئ على اتخاذ إجراء واضح.'],
        ];

        foreach ($tones as $tone) {
            DB::table('tones')->updateOrInsert(
                ['slug' => $tone['slug']],
                array_merge($tone, ['updated_at' => $now, 'created_at' => $now])
            );
        }

        // Industries
        $industries = [
            ['slug' => 'saas_marketing', 'name' => 'SaaS Marketing', 'description' => 'منصات وبرمجيات سحابية.'],
            ['slug' => 'ecommerce', 'name' => 'E‑commerce & Retail', 'description' => 'متاجر إلكترونية ومنتجات استهلاكية.'],
            ['slug' => 'healthcare', 'name' => 'Healthcare & Clinics', 'description' => 'عيادات، مراكز طبية، خدمات صحية.'],
            ['slug' => 'education', 'name' => 'Education & E‑learning', 'description' => 'أكاديميات، منصات تعليمية، دورات.'],
            ['slug' => 'hospitality', 'name' => 'Hospitality & Travel', 'description' => 'فنادق، سياحة، حجوزات، سفر.'],
        ];

        foreach ($industries as $industry) {
            DB::table('industries')->updateOrInsert(
                ['slug' => $industry['slug']],
                array_merge($industry, ['updated_at' => $now, 'created_at' => $now])
            );
        }

        // Task templates (a sample but structured)
        $tasks = [
            [
                'slug' => 'landing_page_hero_saas_ar_to_en',
                'type' => 'translation',
                'name' => 'Landing Page Hero – SaaS – AR→EN',
                'base_prompt' => 'Translate and culturally adapt the following hero section from {source_lang} to {target_lang}. Focus on clarity, trust, and conversion for a SaaS product. Keep it concise, powerful, and aligned with {culture_name} expectations. Use a {tone} tone.',
                'default_source_lang' => 'ar',
                'default_target_lang' => 'en',
                'default_tone_slug' => 'marketing_conversion',
                'default_industry_slug' => 'saas_marketing',
                'default_culture_slug' => 'uk_english_brand',
                'meta' => json_encode(['example_length' => '60-90 words']),
            ],
            [
                'slug' => 'email_welcome_series_ar_to_ar',
                'type' => 'transcreation',
                'name' => 'Email Welcome – عاطفي – AR→AR',
                'base_prompt' => 'Re-write the following Arabic welcome email in modern, emotionally warm Arabic for {culture_name}. Preserve the offer and key facts, but improve emotional flow, clarity, and empathy. Use a {tone} tone and avoid heavy jargon.',
                'default_source_lang' => 'ar',
                'default_target_lang' => 'ar',
                'default_tone_slug' => 'emotional_inspiring',
                'default_industry_slug' => 'ecommerce',
                'default_culture_slug' => 'levant_arabic',
                'meta' => json_encode(['use_transcreation' => true]),
            ],
            [
                'slug' => 'push_notification_offer',
                'type' => 'short_form',
                'name' => 'Push Notification – Limited Offer',
                'base_prompt' => 'Craft a culturally adapted push notification in {target_lang} for {culture_name}. Max 18–22 words. It should clearly state the offer, have one clear CTA, and feel {tone}. Avoid clickbait exaggeration.',
                'default_source_lang' => 'en',
                'default_target_lang' => 'ar',
                'default_tone_slug' => 'marketing_conversion',
                'default_industry_slug' => 'ecommerce',
                'default_culture_slug' => 'gulf_arabic',
                'meta' => json_encode(['max_words' => 22]),
            ],
            [
                'slug' => 'product_page_copy_ecommerce',
                'type' => 'translation',
                'name' => 'Product Page – E‑commerce',
                'base_prompt' => 'Translate and adapt this product description for {culture_name} in {target_lang}. Emphasize benefits, social proof, and local pain points. Keep SEO‑friendly but natural. Maintain a {tone} tone.',
                'default_source_lang' => 'en',
                'default_target_lang' => 'ar',
                'default_tone_slug' => 'professional',
                'default_industry_slug' => 'ecommerce',
                'default_culture_slug' => 'gulf_arabic',
                'meta' => json_encode(['seo' => true]),
            ],
            [
                'slug' => 'clinic_reminder_sms',
                'type' => 'short_form',
                'name' => 'Clinic Reminder SMS',
                'base_prompt' => 'Adapt this appointment reminder SMS for {culture_name} in {target_lang}. It must be polite, reassuring, and concise. Mention date/time clearly. Tone: {tone}.',
                'default_source_lang' => 'en',
                'default_target_lang' => 'ar',
                'default_tone_slug' => 'professional',
                'default_industry_slug' => 'healthcare',
                'default_culture_slug' => 'gulf_arabic',
                'meta' => json_encode(['channel' => 'sms']),
            ],
        ];

        foreach ($tasks as $task) {
            DB::table('task_templates')->updateOrInsert(
                ['slug' => $task['slug']],
                array_merge(
                    $task,
                    [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                )
            );
        }

        // Prompt presets
        $presets = [
            [
                'slug' => 'brand_guardian_soft',
                'name' => 'Brand Guardian – Soft',
                'category' => 'safety',
                'system_prompt' => 'Always ensure the output respects brand safety: no hate speech, no discrimination, no political or religious preaching. Avoid controversial topics unless explicitly requested and allowed.',
                'user_prompt_template' => 'If the source text contains sensitive or controversial content, neutralize it while preserving the core meaning and intent.',
                'meta' => json_encode(['safety_level' => 'soft']),
            ],
            [
                'slug' => 'brand_guardian_strict',
                'name' => 'Brand Guardian – Strict',
                'category' => 'safety',
                'system_prompt' => 'This brand requires strict safety. Do not reproduce any offensive, adult, or highly controversial content. When needed, replace with a neutral, brand‑safe alternative and mention that adaptation was required.',
                'user_prompt_template' => 'If something in the source text is not safe for a mainstream brand, replace it with a safe, neutral alternative while indicating the change gently.',
                'meta' => json_encode(['safety_level' => 'strict']),
            ],
        ];

        foreach ($presets as $preset) {
            DB::table('prompt_presets')->updateOrInsert(
                ['slug' => $preset['slug']],
                array_merge($preset, ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }
}
