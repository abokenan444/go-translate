<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Cultural\CulturalProfile;
use App\Models\Cultural\EmotionalTone;
use App\Models\Cultural\IndustryTemplate;
use App\Models\Cultural\TaskTemplate;

class CulturalPromptsSeeder extends Seeder
{
    public function run(): void
    {
        // Load advanced cultural prompts from SQL files
        $this->loadAdvancedPrompts();
        
        // Original bootstrap data
        $basePath = storage_path('cultural_prompts/bootstrap_data');

        // Cultures
        $cultures = json_decode(file_get_contents($basePath . '/cultures.json'), true) ?? [];
        foreach ($cultures as $c) {
            CulturalProfile::updateOrCreate(
                ['code' => $c['code']],
                $c
            );
        }

        // Tones
        $tones = json_decode(file_get_contents($basePath . '/emotional_tones.json'), true) ?? [];
        foreach ($tones as $t) {
            EmotionalTone::updateOrCreate(
                ['key' => $t['key']],
                $t
            );
        }

        // Industries
        $industries = json_decode(file_get_contents($basePath . '/industries.json'), true) ?? [];
        foreach ($industries as $i) {
            IndustryTemplate::updateOrCreate(
                ['key' => $i['key']],
                $i
            );
        }

        // Tasks
        $tasks = json_decode(file_get_contents($basePath . '/task_templates.json'), true) ?? [];
        foreach ($tasks as $t) {
            TaskTemplate::updateOrCreate(
                ['key' => $t['key']],
                $t
            );
        }
    }

    private function loadAdvancedPrompts(): void
    {
        $this->command->info('Loading advanced cultural translation prompts...');
        
        // Import SQL files
        $sqlFiles = [
            database_path('seeders/advanced_cultural_prompts.sql'),
            database_path('seeders/additional_languages_prompts.sql'),
        ];

        foreach ($sqlFiles as $file) {
            if (file_exists($file)) {
                $sql = file_get_contents($file);
                try {
                    DB::unprepared($sql);
                    $this->command->info("✓ Imported: " . basename($file));
                } catch (\Exception $e) {
                    $this->command->warn("⚠ Warning loading " . basename($file) . ": " . $e->getMessage());
                }
            } else {
                $this->command->warn("⚠ File not found: " . basename($file));
            }
        }

        $this->command->info('✓ Advanced prompts loaded successfully!');
    }
}
