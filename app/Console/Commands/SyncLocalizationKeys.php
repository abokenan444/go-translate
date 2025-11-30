<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncLocalizationKeys extends Command
{
    protected $signature = 'localization:sync {--source=en}';
    protected $description = 'Copy missing keys from source locale auto.php to all other locale auto.php files';

    public function handle(): int
    {
        $source = $this->option('source');
        $sourceFile = lang_path($source.'/auto.php');
        if (!File::exists($sourceFile)) {
            $this->error('Source auto.php not found: '.$sourceFile);
            return self::FAILURE;
        }
        $sourceKeys = include $sourceFile;
        $locales = collect(File::directories(lang_path()))->map(fn($d)=>basename($d))->filter(fn($l)=>$l!==$source)->toArray();
        foreach ($locales as $locale) {
            $targetFile = lang_path($locale.'/auto.php');
            $target = File::exists($targetFile) ? include $targetFile : [];
            $added = 0;
            foreach ($sourceKeys as $k=>$v) {
                if (!array_key_exists($k,$target)) {
                    $target[$k] = $v; // stub copy
                    $added++;
                }
            }
            ksort($target);
            $export = "<?php\n\nreturn ".var_export($target,true).";\n";
            File::put($targetFile,$export);
            $this->info("Locale {$locale}: added {$added} keys");
        }
        return self::SUCCESS;
    }
}
