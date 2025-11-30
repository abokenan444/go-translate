<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractLocalizationKeys extends Command
{
    protected $signature = 'localization:extract {--path=resources/views} {--write}';
    protected $description = 'Scan blade/PHP files for translation keys and list or write missing stubs to locale files';

    protected array $patterns = [
        '/__\(\'([^\']+)\'\)/u',
        '/__\("([^\"]+)"\)/u',
        '/@lang\(\'([^\']+)\'\)/u',
        '/trans\(\'([^\']+)\'\)/u',
    ];

    public function handle(): int
    {
        $scanPath = base_path($this->option('path'));
        if (!File::exists($scanPath)) {
            $this->error('Path not found: '.$scanPath);
            return self::FAILURE;
        }

        $files = File::allFiles($scanPath);
        $keys = [];

        foreach ($files as $file) {
            $content = $file->getContents();
            foreach ($this->patterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $raw) {
                        $keys[$raw] = true;
                    }
                }
            }
        }

        ksort($keys);
        $foundKeys = array_keys($keys);
        $this->info('Found '.count($foundKeys).' unique translation keys.');

        $locales = collect(File::directories(lang_path()))->map(fn($d) => basename($d))->toArray();
        $this->line('Locales: '.implode(', ', $locales));

        if (!$this->option('write')) {
            foreach ($foundKeys as $k) {
                $this->line($k);
            }
            $this->comment('Run with --write to append missing keys to each locale messages.php');
            return self::SUCCESS;
        }

        foreach ($locales as $locale) {
            $messagesFile = lang_path($locale.'/auto.php');
            $existing = File::exists($messagesFile) ? include $messagesFile : [];
            $added = 0;
            foreach ($foundKeys as $k) {
                if (!array_key_exists($k, $existing)) {
                    $existing[$k] = $k; // stub value
                    $added++;
                }
            }
            ksort($existing);
            $export = "<?php\n\nreturn ".var_export($existing, true).";\n";
            File::put($messagesFile, $export);
            $this->info("[{$locale}] added {$added} new keys -> auto.php");
        }

        return self::SUCCESS;
    }
}
