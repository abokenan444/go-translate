<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CulturalMemory;
use App\Services\Security\MemoryEncryptionService;

class RotateMemoryKeys extends Command
{
    protected $signature = 'memory:rotate-keys {from} {to} {--limit=0} {--dry-run : Verify re-encryption without persisting}';
    protected $description = 'Re-encrypt cultural memories from one key id to another';

    public function handle(): int
    {
        $from = $this->argument('from');
        $to = $this->argument('to');
        $limit = (int)$this->option('limit');
        $dryRun = $this->option('dry-run');
        $svc = app(MemoryEncryptionService::class);
        
        if ($dryRun) {
            $this->warn('DRY-RUN MODE: No changes will be persisted');
        }
        
        $memories = CulturalMemory::query()->where('encryption_key_id', $from);
        if ($limit > 0) $memories->limit($limit);
        $list = $memories->get();
        $count = 0; $errors = 0;
        
        foreach ($list as $m) {
            try {
                // Decrypt with old key
                $plainSource = $svc->decrypt($m->getRawOriginal('source_text'), $m->encryption_key_id);
                $plainTranslated = $svc->decrypt($m->getRawOriginal('translated_text'), $m->encryption_key_id);
                
                if ($dryRun) {
                    // Re-encrypt in memory and verify integrity
                    $newSourceEnc = $svc->encrypt($plainSource, $to);
                    $newTranslatedEnc = $svc->encrypt($plainTranslated, $to);
                    
                    // Verify by decrypting again
                    $verifySource = $svc->decrypt($newSourceEnc, $to);
                    $verifyTranslated = $svc->decrypt($newTranslatedEnc, $to);
                    
                    if (hash('sha256', $plainSource) !== hash('sha256', $verifySource)) {
                        throw new \Exception('Source text hash mismatch after re-encryption');
                    }
                    if (hash('sha256', $plainTranslated) !== hash('sha256', $verifyTranslated)) {
                        throw new \Exception('Translated text hash mismatch after re-encryption');
                    }
                    
                    $this->line("✓ Verified record {$m->id} (source: ".strlen($plainSource)." bytes, translated: ".strlen($plainTranslated)." bytes)");
                } else {
                    // Actual rotation
                    $m->encryption_key_id = $to; // will cause new encrypt in mutators
                    $m->source_text = $plainSource;
                    $m->translated_text = $plainTranslated;
                    $m->save();
                }
                
                $count++;
            } catch (\Throwable $e) {
                $errors++;
                $this->error('Failed id '.$m->id.': '.$e->getMessage());
            }
        }
        
        if ($dryRun) {
            $this->info("DRY-RUN: Verified $count records (errors: $errors) - No changes persisted");
        } else {
            $this->info("Rotated $count records (errors: $errors) from $from to $to");
        }
        
        return self::SUCCESS;
    }
}
