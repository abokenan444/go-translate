<?php

namespace App\Console\Commands;

use App\Models\RealTimeSession;
use App\Models\SessionMetric;
use App\Models\RealTimeParticipant;
use Illuminate\Console\Command;

class CollectSessionMetrics extends Command
{
    protected $signature = 'realtime:collect-metrics';

    protected $description = 'Collect metrics for active real-time sessions';

    public function handle()
    {
        $activeSessions = RealTimeSession::where('status', 'active')->get();

        if ($activeSessions->isEmpty()) {
            $this->info('No active sessions to collect metrics from.');
            return 0;
        }

        $this->info("Collecting metrics for {$activeSessions->count()} active sessions...");

        foreach ($activeSessions as $session) {
            $this->collectMetrics($session);
            $this->line("Collected metrics for session: {$session->public_id}");
        }

        $this->info('Metrics collection completed.');
        return 0;
    }

    protected function collectMetrics(RealTimeSession $session)
    {
        // Get active participants
        $participants = RealTimeParticipant::where('session_id', $session->id)
            ->where('status', '!=', 'disconnected')
            ->get();

        // Calculate average connection quality
        $latencies = [];
        $packetLosses = [];
        $audioLevels = [];

        foreach ($participants as $participant) {
            if ($participant->connection_quality) {
                if (isset($participant->connection_quality['latency'])) {
                    $latencies[] = $participant->connection_quality['latency'];
                }
                if (isset($participant->connection_quality['packet_loss'])) {
                    $packetLosses[] = $participant->connection_quality['packet_loss'];
                }
                if (isset($participant->connection_quality['audio_level'])) {
                    $audioLevels[] = $participant->connection_quality['audio_level'];
                }
            }
        }

        // Get translation metrics
        $turns = $session->turns()->where('created_at', '>=', now()->subMinutes(5))->get();
        $translationTimes = $turns->pluck('response_time')->filter()->toArray();
        $successfulTranslations = $turns->where('status', 'completed')->count();
        $failedTranslations = $turns->where('status', 'failed')->count();

        // Determine audio quality
        $avgAudioLevel = !empty($audioLevels) ? array_sum($audioLevels) / count($audioLevels) : null;
        $audioQuality = null;
        if ($avgAudioLevel !== null) {
            if ($avgAudioLevel >= 70) $audioQuality = 'good';
            elseif ($avgAudioLevel >= 40) $audioQuality = 'fair';
            else $audioQuality = 'poor';
        }

        // Create metric record
        SessionMetric::create([
            'session_id' => $session->id,
            'recorded_at' => now(),
            'avg_latency' => !empty($latencies) ? array_sum($latencies) / count($latencies) : null,
            'max_latency' => !empty($latencies) ? max($latencies) : null,
            'min_latency' => !empty($latencies) ? min($latencies) : null,
            'packet_loss_rate' => !empty($packetLosses) ? array_sum($packetLosses) / count($packetLosses) : null,
            'avg_audio_level' => $avgAudioLevel,
            'audio_quality' => $audioQuality,
            'active_participants' => $participants->count(),
            'total_turns' => $session->turns()->count(),
            'total_audio_duration' => $turns->sum('audio_duration') ?? 0,
            'avg_translation_time' => !empty($translationTimes) ? array_sum($translationTimes) / count($translationTimes) : null,
            'successful_translations' => $successfulTranslations,
            'failed_translations' => $failedTranslations,
        ]);
    }
}
