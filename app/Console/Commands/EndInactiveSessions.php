<?php

namespace App\Console\Commands;

use App\Models\RealTimeSession;
use App\Models\RealTimeParticipant;
use Illuminate\Console\Command;

class EndInactiveSessions extends Command
{
    protected $signature = 'realtime:end-inactive
                            {--minutes=30 : Minutes of inactivity before ending session}
                            {--dry-run : Preview what would be ended without actually ending}';

    protected $description = 'End inactive real-time sessions';

    public function handle()
    {
        $minutes = $this->option('minutes');
        $dryRun = $this->option('dry-run');

        $this->info("Ending sessions inactive for more than {$minutes} minutes...");

        // Find active sessions with no recent activity
        $inactiveSessions = RealTimeSession::where('status', 'active')
            ->where('updated_at', '<', now()->subMinutes($minutes))
            ->get();

        if ($inactiveSessions->isEmpty()) {
            $this->info('No inactive sessions found.');
            return 0;
        }

        $this->info("Found {$inactiveSessions->count()} inactive sessions.");

        foreach ($inactiveSessions as $session) {
            $this->line("Processing session: {$session->public_id}");

            // Disconnect all participants
            $participants = RealTimeParticipant::where('session_id', $session->id)
                ->where('status', '!=', 'disconnected')
                ->get();

            foreach ($participants as $participant) {
                if (!$dryRun) {
                    $participant->disconnect();
                }
                $this->line("  - Disconnected participant: {$participant->display_name}");
            }

            // End session
            if (!$dryRun) {
                $session->update([
                    'status' => 'ended',
                    'ended_at' => now(),
                ]);
            }
            $this->line("  - Session ended");
        }

        if ($dryRun) {
            $this->warn("DRY RUN: Would end {$inactiveSessions->count()} sessions.");
        } else {
            $this->info("Successfully ended {$inactiveSessions->count()} sessions.");
        }

        return 0;
    }
}
