<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddNewLanguageCulturesSeeder extends Seeder
{
    public function run(): void
    {
        $newCultures = [
            // Korean
            [
                'code' => 'ko-KR-b2c',
                'name' => 'Korean B2C (South Korea)',
                'region' => 'East Asia',
                'language' => 'ko',
                'audience' => 'B2C',
                'communication_style' => 'Respectful, hierarchical, and detail-oriented. Use appropriate honorifics (존댓말). Value harmony and indirect communication.',
                'do_list' => '• Use formal language with proper honorifics
• Show respect for hierarchy and age
• Be humble and modest in tone
• Include cultural references to K-pop, K-drama when relevant
• Use polite expressions (감사합니다, 죄송합니다)',
                'dont_list' => '• Avoid being too direct or aggressive
• Don\'t use casual language inappropriately
• Avoid individual boasting
• Don\'t ignore age/status differences',
                'is_active' => true,
            ],
            
            // Portuguese (Brazil)
            [
                'code' => 'pt-BR-b2c',
                'name' => 'Portuguese B2C (Brazil)',
                'region' => 'Latin America',
                'language' => 'pt',
                'audience' => 'B2C',
                'communication_style' => 'Warm, friendly, and informal. Brazilians value personal connections and emotional appeal. Use vibrant and enthusiastic language.',
                'do_list' => '• Use informal "você" instead of formal "o senhor"
• Include emotional appeals and enthusiasm
• Reference Brazilian culture (futebol, carnaval, música)
• Use diminutives to show affection (-inho, -inha)
• Be warm and personal',
                'dont_list' => '• Avoid being overly formal or cold
• Don\'t confuse Brazilian Portuguese with European Portuguese
• Avoid negative or pessimistic tone
• Don\'t be too direct without warmth',
                'is_active' => true,
            ],
            
            // Indonesian
            [
                'code' => 'id-ID-b2c',
                'name' => 'Indonesian B2C (Indonesia)',
                'region' => 'Southeast Asia',
                'language' => 'id',
                'audience' => 'B2C',
                'communication_style' => 'Polite, respectful, and community-oriented. Use simple language that connects with diverse audiences. Value harmony and collectivism.',
                'do_list' => '• Use polite forms (Anda instead of kamu)
• Include community and family values
• Be respectful and humble
• Reference Indonesian culture and values
• Use simple, clear language',
                'dont_list' => '• Avoid overly casual slang
• Don\'t be confrontational
• Avoid complex vocabulary
• Don\'t ignore religious sensitivities (majority Muslim)',
                'is_active' => true,
            ],
            
            // Swedish
            [
                'code' => 'sv-SE-b2c',
                'name' => 'Swedish B2C (Sweden)',
                'region' => 'Northern Europe',
                'language' => 'sv',
                'audience' => 'B2C',
                'communication_style' => 'Direct, egalitarian, and minimalist. Swedes value honesty, simplicity, and sustainability. Avoid exaggeration.',
                'do_list' => '• Be direct and honest
• Use simple, clear language (lagom principle)
• Emphasize sustainability and ethics
• Value equality and inclusiveness
• Be understated, not flashy',
                'dont_list' => '• Avoid excessive marketing hype
• Don\'t use hierarchical language
• Avoid bragging or showing off
• Don\'t ignore environmental concerns',
                'is_active' => true,
            ],
            
            // Polish
            [
                'code' => 'pl-PL-b2c',
                'name' => 'Polish B2C (Poland)',
                'region' => 'Eastern Europe',
                'language' => 'pl',
                'audience' => 'B2C',
                'communication_style' => 'Formal yet warm. Polish culture values tradition, family, and hospitality. Use respectful language with emotional connection.',
                'do_list' => '• Use formal "Pan/Pani" in customer communication
• Emphasize family values and tradition
• Show warmth and hospitality
• Reference Polish pride and history appropriately
• Be respectful and courteous',
                'dont_list' => '• Avoid being too casual initially
• Don\'t ignore cultural and religious traditions
• Avoid overly aggressive sales tactics
• Don\'t use informal "ty" with strangers',
                'is_active' => true,
            ],
            
            // Greek
            [
                'code' => 'el-GR-b2c',
                'name' => 'Greek B2C (Greece)',
                'region' => 'Southern Europe',
                'language' => 'el',
                'audience' => 'B2C',
                'communication_style' => 'Expressive, warm, and personal. Greeks value relationships, family, and emotional connection. Use vivid and passionate language.',
                'do_list' => '• Be warm and personal
• Use expressive and emotional language
• Emphasize family and relationships
• Reference Greek culture and traditions
• Show hospitality (filoxenia)',
                'dont_list' => '• Avoid being cold or impersonal
• Don\'t be overly formal or bureaucratic
• Avoid rushing or being too direct
• Don\'t ignore social and family connections',
                'is_active' => true,
            ],
            
            // Portuguese (Portugal)
            [
                'code' => 'pt-PT-luxury',
                'name' => 'Portuguese Luxury (Portugal)',
                'region' => 'Western Europe',
                'language' => 'pt',
                'audience' => 'Premium',
                'communication_style' => 'Sophisticated, traditional, and elegant. European Portuguese is more formal than Brazilian. Value heritage and quality.',
                'do_list' => '• Use formal language consistently
• Emphasize tradition and heritage
• Highlight quality and craftsmanship
• Reference Portuguese culture (fado, azulejos)
• Be elegant and refined',
                'dont_list' => '• Avoid Brazilian Portuguese slang
• Don\'t be overly casual
• Avoid flashy or gaudy language
• Don\'t ignore historical and cultural depth',
                'is_active' => true,
            ],
            
            // Italian
            [
                'code' => 'it-IT-luxury',
                'name' => 'Italian Luxury',
                'region' => 'Southern Europe',
                'language' => 'it',
                'audience' => 'Premium',
                'communication_style' => 'Elegant, passionate, and sophisticated. Italians value style, quality, and beauty. Use expressive and refined language.',
                'do_list' => '• Emphasize beauty and aesthetics (la bella figura)
• Use elegant and refined language
• Reference Italian heritage and craftsmanship
• Show passion and emotion appropriately
• Highlight quality and exclusivity',
                'dont_list' => '• Avoid being bland or utilitarian
• Don\'t ignore style and presentation
• Avoid being too direct or blunt
• Don\'t downplay quality or craftsmanship',
                'is_active' => true,
            ],
        ];

        foreach ($newCultures as $culture) {
            DB::table('cultural_profiles')->updateOrInsert(
                ['code' => $culture['code']],
                array_merge($culture, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('Added ' . count($newCultures) . ' new cultural profiles!');
    }
}
