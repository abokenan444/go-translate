<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Validation\ValidationException;

class JurisdictionService
{
    /**
     * Finalize and validate jurisdiction country from both user selection and portal
     *
     * @param Document $document
     * @return void
     * @throws ValidationException
     */
    public function finalizeJurisdictionCountry(Document $document): void
    {
        $user = $document->country_selected_by_user;
        $portal = $document->country_from_portal;

        // For certified/government: mismatch is blocked
        if (in_array($document->document_type, ['certified', 'government'], true)) {
            if ($user && $portal && strtoupper($user) !== strtoupper($portal)) {
                throw ValidationException::withMessages([
                    'country' => 'Selected country must match the portal jurisdiction.',
                ]);
            }
            $document->jurisdiction_country = strtoupper($portal ?: $user ?: '');
        } else {
            // general: prefer user selection if present
            $document->jurisdiction_country = strtoupper($user ?: $portal ?: '');
        }

        if ($document->jurisdiction_country === '') {
            $document->jurisdiction_country = null;
        }

        $document->save();
    }

    /**
     * Extract country code from government subdomain
     *
     * @param string $host
     * @return string|null
     */
    public function extractCountryFromSubdomain(string $host): ?string
    {
        // Patterns: gov-nl.culturaltranslate.com or nl-gov.culturaltranslate.com
        $patterns = [
            '/^gov-([a-z]{2})\./i',
            '/^([a-z]{2})-gov\./i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $host, $matches)) {
                return strtoupper($matches[1]);
            }
        }

        return null;
    }
}
