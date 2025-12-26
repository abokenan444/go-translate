<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoadAllLiteraryPrompts extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌍 Loading Universal Literary Excellence System for ALL Languages...');
        
        // Clear old literary prompts
        DB::table('cultural_prompts')->where('tone', 'literary')->delete();
        DB::table('cultural_prompts')->where('industry', 'literary')->delete();
        DB::table('cultural_prompts')->where('industry', 'analysis')->delete();
        DB::table('cultural_prompts')->where('industry', 'refinement')->delete();
        DB::table('cultural_prompts')->where('industry', 'cultural')->delete();
        DB::table('cultural_prompts')->where('industry', 'comparison')->delete();
        DB::table('cultural_prompts')->where('industry', 'philosophical')->delete();
        
        $this->loadFile('advanced_literary_prompts.sql', '📚 Arabic Literary Excellence');
        $this->loadFile('ultra_advanced_filters.sql', '🔬 Ultra-Advanced Quality Filters');
        $this->loadFile('universal_literary_excellence.sql', '🌍 Universal Literary Excellence (All Languages)');
        
        $count = DB::table('cultural_prompts')->count();
        $this->command->info("✅ Literary excellence system loaded! Total prompts: {$count}");
        
        // Show breakdown by language
        $this->command->info("\n📊 Prompts by Language:");
        $languages = DB::table('cultural_prompts')
            ->select('language_pair', DB::raw('count(*) as count'))
            ->groupBy('language_pair')
            ->orderBy('count', 'desc')
            ->get();
        
        foreach ($languages as $lang) {
            $this->command->line("  {$lang->language_pair}: {$lang->count}");
        }
    }

    private function loadFile($filename, $description)
    {
        $this->command->info("\n{$description}...");
        
        try {
            $file = database_path("seeders/{$filename}");
            if (file_exists($file)) {
                $sql = file_get_contents($file);
                DB::unprepared($sql);
                $this->command->info("✓ {$filename} loaded");
            } else {
                $this->command->warn("⚠ {$filename} not found");
            }
        } catch (\Exception $e) {
            $this->command->error("❌ Error loading {$filename}: " . $e->getMessage());
        }
    }
}
