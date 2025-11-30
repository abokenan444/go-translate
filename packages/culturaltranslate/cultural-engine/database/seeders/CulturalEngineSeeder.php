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
        // 1) Cultures
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

        // 2) Tones
        $tones = [
            ['key' => 'friendly', 'label' => 'Friendly & Warm', 'description' => 'Warm, human, and accessible tone that builds trust.', 'intensity' => 7],
            ['key' => 'formal',   'label' => 'Formal & Professional', 'description' => 'Clear, respectful, and structured tone for business or official content.', 'intensity' => 6],
            ['key' => 'emotional','label' => 'Emotional & Inspiring', 'description' => 'Evokes feelings of hope, motivation, or empathy.', 'intensity' => 8],
            ['key' => 'direct',   'label' => 'Direct & Straightforward','description' => 'No fluff, straight to the point, ideal for Dutch/German audiences.', 'intensity' => 5],
        ];

        foreach ($tones as $data) {
            EmotionalTone::updateOrCreate(['key' => $data['key']], $data);
        }

        // 3) Industries
        $industries = [
            ['key' => 'generic',   'name' => 'Generic / Mixed', 'description' => 'Generic content not tied to a specific industry.'],
            ['key' => 'ecommerce', 'name' => 'E-Commerce',      'description' => 'Product pages, offers, landing pages, email campaigns.'],
            ['key' => 'saas',      'name' => 'SaaS & Tech',     'description' => 'Apps, platforms, onboarding, changelogs, feature pages.'],
            ['key' => 'tourism',   'name' => 'Tourism & Travel','description' => 'Travel packages, hotels, experiences.'],
            ['key' => 'finance',   'name' => 'Finance & Fintech','description'=> 'Fintech apps, payment platforms, bank marketing.'],
        ];

        foreach ($industries as $data) {
            Industry::updateOrCreate(['key' => $data['key']], $data);
        }

        // 4) Task Templates
        $tasks = [
            [
                'key'          => 'translation.general',
                'name'         => 'General Cultural Translation',
                'type'         => 'translation',
                'category'     => 'generic',
                'industry_key' => 'generic',
                'base_prompt'  => 'You are a cultural translator.

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

Additional context from the client:
{extra_context}

Source text:
"""{source_text}"""

Now produce only the final translated text, without explanations.',
                'meta' => ['example_use' => 'Any general marketing or communication text.'],
            ],

            [
                'key'          => 'translation.ad_copy_short',
                'name'         => 'Short Ad Copy (Performance Marketing)',
                'type'         => 'translation',
                'category'     => 'ads',
                'industry_key' => 'ecommerce',
                'base_prompt'  => 'You are a performance marketing copywriter and cultural translator.

Task:
- Rewrite the following short ad copy from {source_lang} to {target_lang}.
- Adapt it to the target culture: {culture_desc}
- Audience notes: {audience_notes}
- Tone: {tone_label} – {tone_desc}
- Industry: {industry_name} – {industry_desc}

Requirements:
- Keep it short, punchy, and emotionally engaging.
- Focus on one clear benefit for the reader.
- Avoid any cultural or legal issues for this market.
- Suggest 2 variations separated by "---".

Extra context:
{extra_context}

Original ad copy:
"""{source_text}"""',
                'meta' => ['variations' => 2],
            ],

            [
                'key'          => 'email.welcome_series',
                'name'         => 'Welcome Email – Warm & Trust-Building',
                'type'         => 'email',
                'category'     => 'lifecycle',
                'industry_key' => 'saas',
                'base_prompt'  => 'You are a lifecycle email copywriter and cultural translator.

Task:
- Transform the following draft into a culturally adapted welcome email.
- From {source_lang} to {target_lang}.
- Culture: {culture_desc}
- Audience: {audience_notes}
- Tone: {tone_label} – {tone_desc}
- Industry: {industry_name} – {industry_desc}

Requirements:
- Start with a warm, personal greeting (adapted to culture).
- Briefly explain the main value of the product/service.
- Add 2–3 clear next steps (CTAs).
- End with a simple, human closing line.

Context:
{extra_context}

Draft email:
"""{source_text}"""',
                'meta' => ['channel' => 'email', 'stage' => 'welcome'],
            ],

            [
                'key'          => 'product.description',
                'name'         => 'Product Page Description (E-Commerce)',
                'type'         => 'product',
                'category'     => 'product_page',
                'industry_key' => 'ecommerce',
                'base_prompt'  => 'You are an e-commerce copywriter and cultural translator.

Task:
- Rewrite the following product description from {source_lang} to {target_lang}.
- Adapt it to the target culture: {culture_desc}
- Tone: {tone_label} – {tone_desc}
- Audience: {audience_notes}

Requirements:
- Start with a 1–2 sentence hook highlighting the main benefit.
- Then write 3–5 bullet points with key features and benefits.
- Make sure all claims are realistic and acceptable in this market.
- Avoid offensive or culturally sensitive terms.

Context:
{extra_context}

Original product description:
"""{source_text}"""',
                'meta' => ['channel' => 'web', 'section' => 'product'],
            ],
        ];

        foreach ($tasks as $data) {
            TaskTemplate::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
