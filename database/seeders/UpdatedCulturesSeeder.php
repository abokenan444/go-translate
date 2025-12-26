<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatedCulturesSeeder extends Seeder
{
    public function run(): void
    {
        $cultures = [
            [
                'code' => 'ko-KR',
                'name' => 'Korean (South Korea)',
                'locale' => 'ko',
                'region' => 'East Asia',
                'description' => 'Respectful, hierarchical, detail-oriented. Use honorifics. Value harmony.',
                'values_json' => json_encode(['formal' => true, 'respectful' => true, 'hierarchical' => true]),
            ],
            [
                'code' => 'pt-BR',
                'name' => 'Portuguese (Brazil)',
                'locale' => 'pt',
                'region' => 'Latin America',
                'description' => 'Warm, friendly, informal. Emotional appeal. Vibrant and enthusiastic.',
                'values_json' => json_encode(['warm' => true, 'informal' => true, 'emotional' => true]),
            ],
            [
                'code' => 'id-ID',
                'name' => 'Indonesian (Indonesia)',
                'locale' => 'id',
                'region' => 'Southeast Asia',
                'description' => 'Polite, respectful, community-oriented. Value harmony and collectivism.',
                'values_json' => json_encode(['polite' => true, 'community' => true, 'respectful' => true]),
            ],
            [
                'code' => 'sv-SE',
                'name' => 'Swedish (Sweden)',
                'locale' => 'sv',
                'region' => 'Northern Europe',
                'description' => 'Direct, egalitarian, minimalist. Value honesty, simplicity, sustainability.',
                'values_json' => json_encode(['direct' => true, 'simple' => true, 'sustainable' => true]),
            ],
            [
                'code' => 'pl-PL',
                'name' => 'Polish (Poland)',
                'locale' => 'pl',
                'region' => 'Eastern Europe',
                'description' => 'Formal yet warm. Value tradition, family, hospitality.',
                'values_json' => json_encode(['formal' => true, 'traditional' => true, 'family' => true]),
            ],
            [
                'code' => 'el-GR',
                'name' => 'Greek (Greece)',
                'locale' => 'el',
                'region' => 'Southern Europe',
                'description' => 'Expressive, warm, personal. Value relationships and family.',
                'values_json' => json_encode(['expressive' => true, 'warm' => true, 'personal' => true]),
            ],
        ];

        foreach ($cultures as $culture) {
            DB::table('cultural_profiles')->updateOrInsert(
                ['code' => $culture['code']],
                array_merge($culture, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ Added/Updated ' . count($cultures) . ' cultural profiles!');
        
        // عرض الإحصائيات
        $total = DB::table('cultural_profiles')->count();
        $this->command->info("📊 Total cultural profiles: {$total}");
    }
}
