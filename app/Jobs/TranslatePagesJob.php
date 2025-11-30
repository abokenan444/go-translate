<?php
namespace App\Jobs;

use App\Services\TranslationSyncService;

class TranslatePagesJob implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use \Illuminate\Bus\Queueable, \Illuminate\Queue\InteractsWithQueue, \Illuminate\Queue\SerializesModels;

    public string $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function handle(TranslationSyncService $service): void
    {
        $service->syncPages($this->locale);
    }
}
