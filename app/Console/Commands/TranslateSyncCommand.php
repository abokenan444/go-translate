<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslationSyncService;

class TranslateSyncCommand extends Command
{
    protected $signature = 'translate:sync {locale}';
    protected $description = 'Sync page translations for a locale via external API';

    public function handle(TranslationSyncService $service): int
    {
        $locale = $this->argument('locale');
        $this->info("Syncing translations for locale: {$locale}");

        $result = $service->syncPages($locale);

        if (!empty($result['files'])) {
            foreach ($result['files'] as $file) {
                $this->line("Updated: {$file}");
            }
        }

        if (!empty($result['success'])) {
            $this->info('Translation sync completed successfully.');
            return self::SUCCESS;
        }

        $this->warn('Translation sync fell back to English.');
        if (!empty($result['error'])) {
            $this->error('Error: ' . $result['error']);
        }
        return self::SUCCESS; // not fatal
    }
}
