<?php

namespace App\Console\Commands;

use App\Models\RealTimeSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOldSessions extends Command
{
    protected $signature = 'realtime:cleanup
                            {--days=7 : Number of days to keep sessions}
                            {--dry-run : Preview what would be deleted without actually deleting}';

    protected $description = 'Clean up old real-time sessions and their audio files';

    public function handle()
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');

        $this->info("Cleaning up sessions older than {$days} days...");

        // Find old sessions
        $oldSessions = RealTimeSession::where('created_at', '<', now()->subDays($days))
            ->with(['turns', 'participants'])
            ->get();

        if ($oldSessions->isEmpty()) {
            $this->info('No old sessions found.');
            return 0;
        }

        $this->info("Found {$oldSessions->count()} old sessions.");

        $deletedFiles = 0;
        $deletedSessions = 0;

        foreach ($oldSessions as $session) {
            $this->line("Processing session: {$session->public_id}");

            // Delete audio files
            foreach ($session->turns as $turn) {
                if ($turn->source_audio_path && Storage::exists($turn->source_audio_path)) {
                    if (!$dryRun) {
                        Storage::delete($turn->source_audio_path);
                    }
                    $deletedFiles++;
                    $this->line("  - Deleted source audio: {$turn->source_audio_path}");
                }

                if ($turn->target_audio_path && Storage::exists($turn->target_audio_path)) {
                    if (!$dryRun) {
                        Storage::delete($turn->target_audio_path);
                    }
                    $deletedFiles++;
                    $this->line("  - Deleted target audio: {$turn->target_audio_path}");
                }
            }

            // Delete session
            if (!$dryRun) {
                $session->delete();
            }
            $deletedSessions++;
        }

        if ($dryRun) {
            $this->warn("DRY RUN: Would delete {$deletedSessions} sessions and {$deletedFiles} audio files.");
        } else {
            $this->info("Successfully deleted {$deletedSessions} sessions and {$deletedFiles} audio files.");
        }

        return 0;
    }
}
