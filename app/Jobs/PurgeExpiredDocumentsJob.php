<?php

namespace App\Jobs;

use App\Models\OfficialDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PurgeExpiredDocumentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $retentionPolicies = config('retention', [
            'public' => 3650,
            'internal' => 730,
            'confidential' => 365,
            'restricted' => 180,
        ]);

        foreach ($retentionPolicies as $classification => $days) {
            $threshold = now()->subDays($days);

            $documents = OfficialDocument::where('classification', $classification)
                ->where('created_at', '<', $threshold)
                ->whereNotIn('status', ['purged', 'archived'])
                ->get();

            foreach ($documents as $doc) {
                try {
                    // Delete file from storage
                    if ($doc->file_path && Storage::exists($doc->file_path)) {
                        Storage::delete($doc->file_path);
                        Log::info('Purged document file', [
                            'document_id' => $doc->id,
                            'file_path' => $doc->file_path,
                            'classification' => $classification,
                        ]);
                    }

                    // Update status
                    $doc->update([
                        'status' => 'purged',
                        'purged_at' => now(),
                    ]);

                } catch (\Exception $e) {
                    Log::error('Failed to purge document', [
                        'document_id' => $doc->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
