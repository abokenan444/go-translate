<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Cultural\CulturalProfile;
use App\Models\Cultural\EmotionalTone;
use App\Models\Cultural\IndustryTemplate;
use App\Models\Cultural\TaskTemplate;

class CulturalPromptsSeeder extends Seeder
{
    public function run(): void
    {
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
}
