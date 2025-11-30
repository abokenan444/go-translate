<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use CulturalTranslate\CulturalEngine\Models\CultureProfile;
use CulturalTranslate\CulturalEngine\Models\EmotionalTone;
use CulturalTranslate\CulturalEngine\Models\Industry;
use CulturalTranslate\CulturalEngine\Models\TaskTemplate;

class CulturalEngineSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCultures();
        // $this->seedTones(); // Skipped
        // $this->seedIndustries(); // Skipped
        $this->seedTaskTemplates();
    }

    protected function seedCultures(): void
    {
        $cultures = [
            [
                'key'           => 'sa_marketing',
                'name'          => 'Saudi Arabia – Marketing',
                'locale'        => 'ar-SA',
                'country_code'  => 'SA',
                'description'   => 'Conservative, family-oriented, religiously sensitive, prefers respectful and inspiring tone.',
                'audience_notes'=> 'Avoid direct confrontation, use respectful forms, mention trust and family values.',
                'constraints'   => ['avoid' => ['offensive_jokes', 'gambling', 'alcohol']],
                'is_default'    => true,
            ],
            [
                'key'           => 'nl_direct',
                'name'          => 'Netherlands – Direct Communication',
                'locale'        => 'nl-NL',
                'country_code'  => 'NL',
                'description'   => 'Very direct, values clarity, honesty, and practicality.',
                'audience_notes'=> 'Be clear and to the point, avoid overselling, focus on facts and benefits.',
                'constraints'   => ['avoid' => ['exaggeration', 'too_many_superlatives']],
                'is_default'    => false,
            ],
            [
                'key'           => 'uae_b2b',
                'name'          => 'UAE – B2B Professional',
                'locale'        => 'ar-AE',
                'country_code'  => 'AE',
                'description'   => 'Professional, bilingual culture with mix of Arabic and English business tones.',
                'audience_notes'=> 'Formal yet warm, highlight innovation and reliability.',
                'constraints'   => ['avoid' => ['political_topics']],
                'is_default'    => false,
            ],
        ];

        foreach ($cultures as $data) {
            CultureProfile::updateOrCreate(['key' => $data['key']], $data);
        }
    }

    protected function seedTones(): void
    {
        $tones = [
            [
                'key'         => 'friendly',
                'label'       => 'Friendly & Warm',
                'description' => 'Warm, human, and accessible tone that builds trust.',
                'intensity'   => 7,
            ],
            [
                'key'         => 'formal',
                'label'       => 'Formal & Professional',
                'description' => 'Clear, respectful, and structured tone for business or official content.',
                'intensity'   => 6,
            ],
            [
                'key'         => 'emotional',
                'label'       => 'Emotional & Inspiring',
                'description' => 'Evokes feelings of hope, motivation, or empathy.',
                'intensity'   => 8,
            ],
            [
                'key'         => 'direct',
                'label'       => 'Direct & Straightforward',
                'description' => 'No fluff, straight to the point, ideal for Dutch-style communication.',
                'intensity'   => 5,
            ],
        ];

        foreach ($tones as $data) {
            EmotionalTone::updateOrCreate(['key' => $data['key']], $data);
        }
    }

    protected function seedIndustries(): void
    {
        $industries = [
            [
                'key'         => 'generic',
                'name'        => 'Generic / Mixed',
                'description' => 'Generic content not tied to a specific industry.',
            ],
            [
                'key'         => 'ecommerce',
                'name'        => 'E-Commerce',
                'description' => 'Product pages, offers, landing pages, email campaigns.',
            ],
            [
                'key'         => 'saas',
                'name'        => 'SaaS & Tech',
                'description' => 'Apps, platforms, onboarding, changelogs, feature pages.',
            ],
            [
                'key'         => 'tourism',
                'name'        => 'Tourism & Travel',
                'description' => 'Travel packages, hotels, experiences.',
            ],
            [
                'key'         => 'finance',
                'name'        => 'Finance & Fintech',
                'description' => 'Fintech apps, payment platforms, bank marketing.',
            ],
        ];

        foreach ($industries as $data) {
            Industry::updateOrCreate(['key' => $data['key']], $data);
        }
    }

    protected function seedTaskTemplates(): void
    {
        /*
         * 1) ترجمة عامة – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "translation.general_$i"],
                [
                    'name'         => "General Cultural Translation #$i",
                    'type'         => 'translation',
                    'category'     => 'generic',
                    'industry_key' => 'generic',
                    'base_prompt'  => <<<PROMPT
You are a cultural translator.

Goal:
- Translate the following text from {source_lang} to {target_lang}.
- Adapt it to the target culture: {culture_desc}
- Respect the audience: {audience_notes}
- Use the tone: {tone_label} – {tone_desc}
- Consider the industry: {industry_name} – {industry_desc}

Instructions:
- Do NOT translate word-by-word.
- Rephrase to sound natural and human in the target language.
- If the text contains idioms, adapt them to culturally equivalent expressions.
- If something is inappropriate for this culture, replace it with a culturally safe alternative.

Client context:
{extra_context}

Source text:
\"\"\"{source_text}\"\"\"

Return only the final translated text, with no explanations.
PROMPT,
                    'meta'         => ['family' => 'general_translation'],
                ]
            );
        }

        /*
         * 2) إعلانات قصيرة (Facebook / Instagram / Google) – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "ads.performance_short_$i"],
                [
                    'name'         => "Short Performance Ad Copy #$i",
                    'type'         => 'translation',
                    'category'     => 'ads',
                    'industry_key' => 'ecommerce',
                    'base_prompt'  => <<<PROMPT
You are a performance marketing copywriter and cultural translator.

Task:
- Rewrite the following ad copy from {source_lang} to {target_lang}.
- Adapt it to the target culture: {culture_desc}
- Audience notes: {audience_notes}
- Tone: {tone_label} – {tone_desc}
- Industry: {industry_name} – {industry_desc}

Requirements:
- Keep it short, punchy, and emotionally engaging.
- Focus on one clear benefit for the reader.
- Avoid any cultural or legal issues for this market.
- Produce 2 variations separated by a line with "---".

Extra campaign context:
{extra_context}

Original ad copy:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['channel' => 'paid_ads', 'variations' => 2],
                ]
            );
        }

        /*
         * 3) إيميلات (Welcome, Reactivation, Promo…) – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "email.lifecycle_$i"],
                [
                    'name'         => "Lifecycle Email Template #$i",
                    'type'         => 'email',
                    'category'     => 'lifecycle',
                    'industry_key' => 'saas',
                    'base_prompt'  => <<<PROMPT
You are a lifecycle email copywriter and cultural translator.

Task:
- Transform the following draft into a culturally adapted email.
- From {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}
- Industry: {industry_name} – {industry_desc}

Requirements:
- Start with a greeting that fits the target culture.
- Introduce the main value in 1–2 concise sentences.
- Add 2–3 clear calls to action (CTAs).
- Close with a human, warm sign-off that feels natural for this culture.

Business context:
{extra_context}

Draft email:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['channel' => 'email', 'use_case' => 'lifecycle'],
                ]
            );
        }

        /*
         * 4) وصف منتجات لمتاجر إلكترونية – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "product.description_$i"],
                [
                    'name'         => "E-Commerce Product Description #$i",
                    'type'         => 'product',
                    'category'     => 'product_page',
                    'industry_key' => 'ecommerce',
                    'base_prompt'  => <<<PROMPT
You are an e-commerce copywriter and cultural translator.

Task:
- Rewrite the following product description from {source_lang} to {target_lang}.
- Adapt the message to the target culture: {culture_desc}
- Tone: {tone_label} – {tone_desc}
- Audience: {audience_notes}

Output structure:
1) A short 1–2 sentence hook that highlights the main benefit.
2) 3–5 bullet points with concrete features and benefits.
3) Optional one-line closing that reinforces trust or urgency (if culturally appropriate).

Client context:
{extra_context}

Original product description:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['channel' => 'web', 'section' => 'product'],
                ]
            );
        }

        /*
         * 5) صفحات هبوط (Landing Sections / Hero / Feature blocks) – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "landing.section_$i"],
                [
                    'name'         => "Landing Page Section #$i",
                    'type'         => 'web',
                    'category'     => 'landing',
                    'industry_key' => 'saas',
                    'base_prompt'  => <<<PROMPT
You are a SaaS landing page copywriter and cultural translator.

Task:
- Rewrite and culturally adapt the following landing page section.
- From {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}

Output:
- A clear section title (H2 style).
- 2–3 short sentences describing the value.
- Optional bullet list with up to 4 key points (if helpful for clarity).

Use the client context below to adapt references, examples, and terminology:
{extra_context}

Original section draft:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['page_type' => 'landing'],
                ]
            );
        }

        /*
         * 6) مقالات / مدوّنة (Intro, Body, Outro) – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "blog.article_$i"],
                [
                    'name'         => "Blog Article Cultural Adaptation #$i",
                    'type'         => 'longform',
                    'category'     => 'blog',
                    'industry_key' => 'generic',
                    'base_prompt'  => <<<PROMPT
You are a content writer and cultural translator.

Task:
- Adapt the following blog article or section from {source_lang} to {target_lang}.
- Preserve the core ideas while making examples, phrases, and tone fit the target culture: {culture_desc}.
- Respect: {audience_notes}
- Tone: {tone_label} – {tone_desc}

Requirements:
- Keep the structure logical and easy to read.
- If there are examples that do not make sense in the target culture, replace them with culturally relevant ones.
- Preserve any factual information and data points.

Additional editorial context:
{extra_context}

Original article text:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['format' => 'article'],
                ]
            );
        }

        /*
         * 7) دعم فني / ردود خدمة عملاء – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "support.reply_$i"],
                [
                    'name'         => "Customer Support Reply #$i",
                    'type'         => 'support',
                    'category'     => 'customer_service',
                    'industry_key' => 'saas',
                    'base_prompt'  => <<<PROMPT
You are a customer support specialist and cultural translator.

Task:
- Turn the following internal support notes into a polite, culturally adapted reply.
- From {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}

Requirements:
- Start with a short, empathetic acknowledgment.
- Explain the situation in simple, clear language.
- Offer a solution or next step.
- Close with a friendly, reassuring line that fits the culture.

Internal notes and context:
{extra_context}

Internal draft / bullet points:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['channel' => 'email_or_chat'],
                ]
            );
        }

        /*
         * 8) رسائل SMS / Push Notifications – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "notification.short_$i"],
                [
                    'name'         => "Short Notification (SMS / Push) #$i",
                    'type'         => 'notification',
                    'category'     => 'short_message',
                    'industry_key' => 'generic',
                    'base_prompt'  => <<<PROMPT
You are a short-form copywriter and cultural translator.

Task:
- Rewrite and culturally adapt the following short notification text.
- From {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}

Requirements:
- Max 1–2 short sentences.
- Very clear, action-oriented, and mobile-friendly.
- Avoid any words that might be sensitive or confusing in this culture.

Campaign / product context:
{extra_context}

Original short text:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['channels' => ['sms', 'push']],
                ]
            );
        }

        /*
         * 9) منشورات سوشال (Organic Social Posts) – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "social.post_$i"],
                [
                    'name'         => "Organic Social Post #$i",
                    'type'         => 'social',
                    'category'     => 'organic',
                    'industry_key' => 'generic',
                    'base_prompt'  => <<<PROMPT
You are a social media copywriter and cultural translator.

Task:
- Adapt the following social media post from {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}

Requirements:
- Keep it natural for the social platforms used in the target culture.
- You may adjust emojis, hashtags, and references to fit local habits.
- Keep the call to action clear but not aggressive (unless local culture expects strong CTAs).

Campaign context:
{extra_context}

Original social post:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['platforms' => ['facebook', 'instagram', 'linkedin', 'x']],
                ]
            );
        }

        /*
         * 10) نصوص داخل التطبيق / UX Copy (Buttons, Tooltips, Onboarding) – 10 قوالب
         */
        for ($i = 1; $i <= 10; $i++) {
            TaskTemplate::updateOrCreate(
                ['key' => "ux.microcopy_$i"],
                [
                    'name'         => "UX Microcopy & In-App Text #$i",
                    'type'         => 'ux',
                    'category'     => 'microcopy',
                    'industry_key' => 'saas',
                    'base_prompt'  => <<<PROMPT
You are a UX writer and cultural translator.

Task:
- Adapt the following in-app text and microcopy from {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}

Requirements:
- Keep each label, button text, and tooltip short and easy to understand.
- Use terminology that matches digital products in the target culture.
- If a term is typically kept in English in this market (e.g. "Dashboard"), keep or adapt accordingly.

Product context:
{extra_context}

Original UX copy:
\"\"\"{source_text}\"\"\"
PROMPT,
                    'meta'         => ['area' => 'product_ux'],
                ]
            );
        }
    }
}
