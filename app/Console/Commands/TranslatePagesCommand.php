<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TranslatePagesCommand extends Command
{
    protected $signature = 'translate:pages';
    protected $description = 'Translate pages.php to all supported languages';

    protected $languages = [
        'ar' => 'Arabic',
        'es' => 'Spanish', 
        'fr' => 'French',
        'de' => 'German',
        'it' => 'Italian',
        'pt' => 'Portuguese',
        'ru' => 'Russian',
        'zh' => 'Chinese',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'tr' => 'Turkish',
        'nl' => 'Dutch',
        'hi' => 'Hindi',
    ];

    public function handle()
    {
        $this->info('🌍 Creating page translation files for all languages...');
        
        $sourcePath = base_path('lang/en/pages.php');
        
        if (!File::exists($sourcePath)) {
            $this->error('Source file not found: ' . $sourcePath);
            return 1;
        }

        foreach ($this->languages as $locale => $language) {
            $this->info("Processing {$language} ({$locale})...");
            
            $targetDir = base_path('lang/' . $locale);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            $targetPath = $targetDir . '/pages.php';
            
            // Copy English template - will be translated manually or via AI
            File::copy($sourcePath, $targetPath);
            $this->line("  ✅ Created: {$targetPath}");
        }
        
        $this->info('');
        $this->info('✅ Translation files created!');
        $this->info('📝 Please translate each pages.php file in lang/<locale>/ directories');
        
        return 0;
    }
}
