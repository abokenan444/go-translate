<?php

namespace App\Jobs;

use App\Services\TranslatorPerformanceService;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateTranslatorPerformanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $translator;

    /**
     * Create a new job instance.
     */
    public function __construct(User $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Execute the job.
     */
    public function handle(TranslatorPerformanceService $service): void
    {
        try {
            $service->updatePerformanceCache($this->translator);
            
            Log::info('Translator performance updated', [
                'translator_id' => $this->translator->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update translator performance', [
                'translator_id' => $this->translator->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}
