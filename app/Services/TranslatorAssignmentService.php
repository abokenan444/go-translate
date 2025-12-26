<?php

namespace App\Services;

use App\Models\Translation;
use App\Models\User;
use App\Models\CtsPartner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Translator Assignment Service
 * 
 * Handles intelligent assignment of translators to translation requests
 * based on language pairs, expertise, availability, and partner certification
 */
class TranslatorAssignmentService
{
    /**
     * Assign the best translator to a translation request
     *
     * @param Translation $translation
     * @return User|null
     */
    public function assignTranslator(Translation $translation): ?User
    {
        // Get qualified translators for this language pair
        $qualifiedTranslators = $this->getQualifiedTranslators(
            $translation->source_language,
            $translation->target_language,
            $translation->cts_level
        );

        if ($qualifiedTranslators->isEmpty()) {
            Log::warning("No qualified translators found for translation {$translation->id}");
            return null;
        }

        // Score and rank translators
        $bestTranslator = $this->selectBestTranslator($qualifiedTranslators, $translation);

        if ($bestTranslator) {
            $translation->update([
                'translator_id' => $bestTranslator->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            Log::info("Translator {$bestTranslator->id} assigned to translation {$translation->id}");
        }

        return $bestTranslator;
    }

    /**
     * Get translators qualified for specific language pair and CTS level
     *
     * @param string $sourceLang
     * @param string $targetLang
     * @param string|null $ctsLevel
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getQualifiedTranslators(string $sourceLang, string $targetLang, ?string $ctsLevel)
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'translator');
        })
        ->where('is_active', true)
        ->where(function ($query) use ($sourceLang, $targetLang) {
            $query->whereJsonContains('language_pairs', [
                'source' => $sourceLang,
                'target' => $targetLang
            ])
            ->orWhereJsonContains('languages', $sourceLang)
            ->whereJsonContains('languages', $targetLang);
        })
        ->when($ctsLevel, function ($query) use ($ctsLevel) {
            // Filter by CTS certification level
            $query->whereHas('ctsPartner', function ($q) use ($ctsLevel) {
                $q->where('status', 'active')
                  ->where('certification_level', '>=', $this->getCtsLevelValue($ctsLevel));
            });
        })
        ->get();
    }

    /**
     * Select the best translator based on multiple criteria
     *
     * @param \Illuminate\Database\Eloquent\Collection $translators
     * @param Translation $translation
     * @return User|null
     */
    protected function selectBestTranslator($translators, Translation $translation): ?User
    {
        $scoredTranslators = $translators->map(function ($translator) use ($translation) {
            $score = 0;

            // 1. Expertise score (30%)
            $score += $this->calculateExpertiseScore($translator, $translation) * 0.3;

            // 2. Availability score (25%)
            $score += $this->calculateAvailabilityScore($translator) * 0.25;

            // 3. Quality score (25%)
            $score += $this->calculateQualityScore($translator) * 0.25;

            // 4. Partner certification score (20%)
            $score += $this->calculateCertificationScore($translator, $translation) * 0.2;

            return [
                'translator' => $translator,
                'score' => $score
            ];
        });

        $best = $scoredTranslators->sortByDesc('score')->first();

        return $best ? $best['translator'] : null;
    }

    /**
     * Calculate expertise score based on specialization and experience
     *
     * @param User $translator
     * @param Translation $translation
     * @return float (0-100)
     */
    protected function calculateExpertiseScore(User $translator, Translation $translation): float
    {
        $score = 50; // Base score

        // Check specialization match
        $specializations = $translator->specializations ?? [];
        if (in_array($translation->category, $specializations)) {
            $score += 30;
        }

        // Experience bonus
        $completedTranslations = $translator->completedTranslations()->count();
        $score += min(20, $completedTranslations / 10); // Up to 20 points for experience

        return min(100, $score);
    }

    /**
     * Calculate availability score based on current workload
     *
     * @param User $translator
     * @return float (0-100)
     */
    protected function calculateAvailabilityScore(User $translator): float
    {
        $activeTranslations = $translator->activeTranslations()->count();
        
        // Maximum 5 concurrent translations for optimal quality
        if ($activeTranslations >= 5) {
            return 0;
        }

        return 100 - ($activeTranslations * 20);
    }

    /**
     * Calculate quality score based on ratings and completion rate
     *
     * @param User $translator
     * @return float (0-100)
     */
    protected function calculateQualityScore(User $translator): float
    {
        $score = 50; // Base score

        // Average rating (40 points)
        $avgRating = $translator->translations()
            ->whereNotNull('rating')
            ->avg('rating') ?? 3;
        $score += ($avgRating / 5) * 40;

        // Completion rate (10 points)
        $totalAssigned = $translator->translations()->count();
        $completed = $translator->translations()->where('status', 'completed')->count();
        
        if ($totalAssigned > 0) {
            $completionRate = $completed / $totalAssigned;
            $score += $completionRate * 10;
        }

        return min(100, $score);
    }

    /**
     * Calculate certification score based on CTS partner status
     *
     * @param User $translator
     * @param Translation $translation
     * @return float (0-100)
     */
    protected function calculateCertificationScore(User $translator, Translation $translation): float
    {
        $partner = $translator->ctsPartner;

        if (!$partner || $partner->status !== 'active') {
            return 0;
        }

        $score = 50; // Base score for being a certified partner

        // CTS level match bonus
        if ($translation->cts_level) {
            $requiredLevel = $this->getCtsLevelValue($translation->cts_level);
            $partnerLevel = $this->getCtsLevelValue($partner->certification_level ?? 'CTS-B');
            
            if ($partnerLevel >= $requiredLevel) {
                $score += 30;
            }
        }

        // Certificates issued bonus (up to 20 points)
        $score += min(20, ($partner->certificates_issued ?? 0) / 10);

        return min(100, $score);
    }

    /**
     * Get numeric value for CTS level for comparison
     *
     * @param string $level
     * @return int
     */
    protected function getCtsLevelValue(string $level): int
    {
        $levels = [
            'CTS-A' => 4,
            'CTS-B' => 3,
            'CTS-C' => 2,
            'CTS-R' => 1,
        ];

        return $levels[$level] ?? 0;
    }

    /**
     * Reassign translation to another translator
     *
     * @param Translation $translation
     * @param string $reason
     * @return User|null
     */
    public function reassignTranslation(Translation $translation, string $reason): ?User
    {
        $previousTranslator = $translation->translator_id;

        // Log reassignment
        Log::info("Reassigning translation {$translation->id} from translator {$previousTranslator}. Reason: {$reason}");

        // Reset translation status
        $translation->update([
            'translator_id' => null,
            'status' => 'pending',
            'assigned_at' => null,
        ]);

        // Assign new translator
        return $this->assignTranslator($translation);
    }

    /**
     * Get translator workload statistics
     *
     * @param User $translator
     * @return array
     */
    public function getTranslatorWorkload(User $translator): array
    {
        return [
            'active_translations' => $translator->activeTranslations()->count(),
            'pending_translations' => $translator->translations()->where('status', 'assigned')->count(),
            'in_progress_translations' => $translator->translations()->where('status', 'in_progress')->count(),
            'completed_today' => $translator->translations()
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'avg_completion_time' => $translator->translations()
                ->where('status', 'completed')
                ->whereNotNull('completed_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, assigned_at, completed_at)) as avg_hours')
                ->value('avg_hours'),
        ];
    }
}
