<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CulturalMemory;

class CulturalMemorySeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'source_language' => 'en',
                'target_language' => 'ar',
                'target_culture' => 'ar_SA',
                'source_text' => 'Welcome to our financial planning portal. Your security matters.',
                'translated_text' => 'مرحبًا بك في بوابة التخطيط المالي لدينا. أمنك مهم.',
                'brand_voice' => 'professional',
                'emotion' => 'neutral',
                'tone' => 'formal',
                'metadata' => ['domain' => 'finance']
            ],
            [
                'source_language' => 'en',
                'target_language' => 'fr',
                'target_culture' => 'fr_FR',
                'source_text' => 'Real-time collaboration improves team efficiency.',
                'translated_text' => 'La collaboration en temps réel améliore l’efficacité de l’équipe.',
                'brand_voice' => 'innovative',
                'emotion' => 'positive',
                'tone' => 'professional',
                'metadata' => ['domain' => 'technology']
            ],
            [
                'source_language' => 'en',
                'target_language' => 'es',
                'target_culture' => 'es_ES',
                'source_text' => 'Healthcare data must remain confidential at all times.',
                'translated_text' => 'Los datos sanitarios deben permanecer confidenciales en todo momento.',
                'brand_voice' => 'trustworthy',
                'emotion' => 'serious',
                'tone' => 'formal',
                'metadata' => ['domain' => 'healthcare']
            ],
            [
                'source_language' => 'en',
                'target_language' => 'de',
                'target_culture' => 'de_DE',
                'source_text' => 'Manufacturing quality audits occur weekly for compliance.',
                'translated_text' => 'Qualitätsprüfungen in der Produktion finden wöchentlich zur Einhaltung statt.',
                'brand_voice' => 'precise',
                'emotion' => 'neutral',
                'tone' => 'technical',
                'metadata' => ['domain' => 'manufacturing']
            ],
        ];

        foreach ($samples as $s) {
            CulturalMemory::create($s);
        }
    }
}
