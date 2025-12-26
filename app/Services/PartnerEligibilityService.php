<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Partner;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PartnerEligibilityService
{
    /**
     * Get eligible partners for a document assignment
     *
     * @param Document $document
     * @param int $limit
     * @return Collection
     */
    public function eligiblePartners(Document $document, int $limit = 10): Collection
    {
        $country = $document->jurisdiction_country;
        $source = $document->source_lang;
        $target = $document->target_lang;

        $query = Partner::query()
            ->where('status', 'verified')
            ->when($country, fn($q) => $q->where('country_code', $country))
            ->whereHas('credentials', function ($c) {
                $c->where('verification_status', 'approved')
                  ->where(function ($x) {
                      $x->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', Carbon::now());
                  });
            })
            ->whereHas('languages', function ($l) use ($source, $target) {
                $l->where('is_active', 1);
                if ($source) {
                    $l->where('source_lang', $source);
                }
                if ($target) {
                    $l->where('target_lang', $target);
                }
            });

        // Capacity check: active accepted assignments < max_concurrent_jobs
        $query->whereRaw("
            (SELECT COUNT(*) FROM document_assignments 
             WHERE partner_id = partners.id 
             AND status = 'accepted'
            ) < partners.max_concurrent_jobs
        ");

        // Order by: availability first, then rating
        $query->orderByRaw("
            (SELECT COUNT(*) FROM document_assignments 
             WHERE partner_id = partners.id 
             AND status = 'accepted'
            ) ASC
        ")->orderByDesc('rating');

        return $query->limit($limit)->get();
    }

    /**
     * Check if partner is eligible for specific document
     *
     * @param Partner $partner
     * @param Document $document
     * @return bool
     */
    public function isEligible(Partner $partner, Document $document): bool
    {
        // Status check
        if ($partner->status !== 'verified') {
            return false;
        }

        // Country match
        if ($document->jurisdiction_country && $partner->country_code !== $document->jurisdiction_country) {
            return false;
        }

        // Valid credentials
        if (!$partner->hasValidCredentials()) {
            return false;
        }

        // Language support
        if ($document->source_lang && $document->target_lang) {
            if (!$partner->canHandleLanguages($document->source_lang, $document->target_lang)) {
                return false;
            }
        }

        // Capacity
        $activeCount = $partner->activeAssignments()->count();
        if ($activeCount >= $partner->max_concurrent_jobs) {
            return false;
        }

        return true;
    }
}
