<?php

namespace App\Services;

use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranslatorPerformanceService
{
    /**
     * Calculate translator performance score
     */
    public function calculatePerformanceScore(User $translator): array
    {
        $metrics = $this->getTranslatorMetrics($translator);
        
        $scores = [
            'quality' => $this->calculateQualityScore($metrics),
            'speed' => $this->calculateSpeedScore($metrics),
            'reliability' => $this->calculateReliabilityScore($metrics),
            'communication' => $this->calculateCommunicationScore($metrics),
        ];

        $overallScore = ($scores['quality'] * 0.4) + 
                       ($scores['speed'] * 0.25) + 
                       ($scores['reliability'] * 0.25) + 
                       ($scores['communication'] * 0.1);

        return [
            'overall_score' => round($overallScore, 2),
            'breakdown' => $scores,
            'metrics' => $metrics,
            'level' => $this->determineLevel($overallScore),
            'calculated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Get translator metrics
     */
    private function getTranslatorMetrics(User $translator): array
    {
        $period = now()->subDays(30);
        
        $documents = Document::where('translator_id', $translator->id)
            ->where('created_at', '>=', $period)
            ->get();

        $completed = $documents->where('status', 'completed');
        $rejected = $documents->where('status', 'rejected');
        
        return [
            'total_documents' => $documents->count(),
            'completed_documents' => $completed->count(),
            'rejected_documents' => $rejected->count(),
            'average_rating' => $completed->avg('rating') ?? 0,
            'average_completion_time' => $this->calculateAverageCompletionTime($completed),
            'on_time_delivery_rate' => $this->calculateOnTimeRate($completed),
            'revision_rate' => $this->calculateRevisionRate($documents),
            'acceptance_rate' => $this->calculateAcceptanceRate($translator, $period),
            'response_time' => $this->calculateAverageResponseTime($translator, $period),
        ];
    }

    /**
     * Calculate quality score (0-100)
     */
    private function calculateQualityScore(array $metrics): float
    {
        $ratingScore = ($metrics['average_rating'] / 5) * 100;
        $revisionPenalty = $metrics['revision_rate'] * 20;
        $rejectionPenalty = ($metrics['rejected_documents'] / max($metrics['total_documents'], 1)) * 30;

        $score = $ratingScore - $revisionPenalty - $rejectionPenalty;
        
        return max(0, min(100, $score));
    }

    /**
     * Calculate speed score (0-100)
     */
    private function calculateSpeedScore(array $metrics): float
    {
        // Average expected time is 24 hours
        $expectedTime = 24;
        $actualTime = $metrics['average_completion_time'];

        if ($actualTime <= 0) {
            return 100;
        }

        $ratio = $expectedTime / $actualTime;
        $score = min($ratio * 100, 100);

        // Bonus for on-time delivery
        $bonus = $metrics['on_time_delivery_rate'] * 20;
        
        return min(100, $score + $bonus);
    }

    /**
     * Calculate reliability score (0-100)
     */
    private function calculateReliabilityScore(array $metrics): float
    {
        $acceptanceScore = $metrics['acceptance_rate'] * 100;
        $completionRate = ($metrics['completed_documents'] / max($metrics['total_documents'], 1)) * 100;
        
        return ($acceptanceScore * 0.4) + ($completionRate * 0.6);
    }

    /**
     * Calculate communication score (0-100)
     */
    private function calculateCommunicationScore(array $metrics): float
    {
        // Response time in hours - ideal is under 2 hours
        $responseTime = $metrics['response_time'];
        
        if ($responseTime <= 2) {
            return 100;
        } elseif ($responseTime <= 6) {
            return 80;
        } elseif ($responseTime <= 12) {
            return 60;
        } elseif ($responseTime <= 24) {
            return 40;
        } else {
            return 20;
        }
    }

    /**
     * Calculate average completion time in hours
     */
    private function calculateAverageCompletionTime($documents): float
    {
        if ($documents->isEmpty()) {
            return 0;
        }

        $times = $documents->map(function($doc) {
            if (!$doc->completed_at || !$doc->assigned_at) {
                return null;
            }
            return $doc->completed_at->diffInHours($doc->assigned_at);
        })->filter();

        return $times->avg() ?? 0;
    }

    /**
     * Calculate on-time delivery rate
     */
    private function calculateOnTimeRate($documents): float
    {
        if ($documents->isEmpty()) {
            return 0;
        }

        $onTime = $documents->filter(function($doc) {
            if (!$doc->completed_at || !$doc->deadline) {
                return false;
            }
            return $doc->completed_at <= $doc->deadline;
        })->count();

        return $onTime / $documents->count();
    }

    /**
     * Calculate revision rate
     */
    private function calculateRevisionRate($documents): float
    {
        if ($documents->isEmpty()) {
            return 0;
        }

        $revised = $documents->filter(function($doc) {
            return $doc->revision_count > 0;
        })->count();

        return $revised / $documents->count();
    }

    /**
     * Calculate acceptance rate
     */
    private function calculateAcceptanceRate(User $translator, $since): float
    {
        $offered = DB::table('document_assignments')
            ->where('translator_id', $translator->id)
            ->where('created_at', '>=', $since)
            ->count();

        $accepted = DB::table('document_assignments')
            ->where('translator_id', $translator->id)
            ->where('created_at', '>=', $since)
            ->where('status', 'accepted')
            ->count();

        if ($offered === 0) {
            return 1.0;
        }

        return $accepted / $offered;
    }

    /**
     * Calculate average response time in hours
     */
    private function calculateAverageResponseTime(User $translator, $since): float
    {
        $assignments = DB::table('document_assignments')
            ->where('translator_id', $translator->id)
            ->where('created_at', '>=', $since)
            ->whereNotNull('responded_at')
            ->get();

        if ($assignments->isEmpty()) {
            return 0;
        }

        $times = $assignments->map(function($assignment) {
            $created = \Carbon\Carbon::parse($assignment->created_at);
            $responded = \Carbon\Carbon::parse($assignment->responded_at);
            return $responded->diffInHours($created);
        });

        return $times->avg();
    }

    /**
     * Determine performance level
     */
    private function determineLevel(float $score): string
    {
        if ($score >= 90) {
            return 'Elite';
        } elseif ($score >= 80) {
            return 'Expert';
        } elseif ($score >= 70) {
            return 'Professional';
        } elseif ($score >= 60) {
            return 'Intermediate';
        } else {
            return 'Beginner';
        }
    }

    /**
     * Get top performers
     */
    public function getTopPerformers(int $limit = 10): array
    {
        $translators = User::where('role', 'translator')
            ->where('status', 'active')
            ->get();

        $performers = [];

        foreach ($translators as $translator) {
            $performance = Cache::remember(
                "translator_performance:{$translator->id}",
                3600,
                fn() => $this->calculatePerformanceScore($translator)
            );

            $performers[] = [
                'translator_id' => $translator->id,
                'name' => $translator->name,
                'score' => $performance['overall_score'],
                'level' => $performance['level'],
            ];
        }

        // Sort by score descending
        usort($performers, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($performers, 0, $limit);
    }

    /**
     * Update translator performance cache
     */
    public function updatePerformanceCache(User $translator): void
    {
        $performance = $this->calculatePerformanceScore($translator);
        
        Cache::put(
            "translator_performance:{$translator->id}",
            $performance,
            3600
        );

        // Update database
        DB::table('translator_performance')->updateOrInsert(
            ['translator_id' => $translator->id],
            [
                'overall_score' => $performance['overall_score'],
                'quality_score' => $performance['breakdown']['quality'],
                'speed_score' => $performance['breakdown']['speed'],
                'reliability_score' => $performance['breakdown']['reliability'],
                'communication_score' => $performance['breakdown']['communication'],
                'level' => $performance['level'],
                'updated_at' => now(),
            ]
        );

        Log::info('Translator performance updated', [
            'translator_id' => $translator->id,
            'score' => $performance['overall_score'],
        ]);
    }
}
