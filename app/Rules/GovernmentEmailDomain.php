<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class GovernmentEmailDomain implements ValidationRule
{
    protected array $blockedDomains = [
        'gmail.com',
        'yahoo.com',
        'hotmail.com',
        'outlook.com',
        'live.com',
        'msn.com',
        'aol.com',
        'icloud.com',
        'mail.com',
        'protonmail.com',
        'zoho.com',
        'yandex.com',
        'gmx.com',
        'fastmail.com',
        'tutanota.com',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = strtolower($value);
        $domain = substr(strrchr($email, "@"), 1);

        // Check if it's a blocked public email domain
        if (in_array($domain, $this->blockedDomains)) {
            $fail('Government registration requires an official government email address. Public email providers (Gmail, Yahoo, etc.) are not accepted.');
            return;
        }

        // Check if domain matches known government patterns
        $isGovernmentDomain = $this->isGovernmentDomain($domain);

        // If not a known government domain, we'll allow it but flag for manual review
        // The verification process will handle validation
        if (!$isGovernmentDomain) {
            // Check if domain exists in our verified list
            $verified = DB::table('government_email_domains')
                ->where('is_verified', true)
                ->where(function ($query) use ($domain) {
                    $query->where('domain', $domain)
                        ->orWhereRaw("? LIKE CONCAT('%', domain)", [$domain]);
                })
                ->exists();

            if (!$verified) {
                // Log for manual review but don't fail
                \Log::info('Non-standard government email domain used', [
                    'email' => $email,
                    'domain' => $domain,
                ]);
            }
        }
    }

    protected function isGovernmentDomain(string $domain): bool
    {
        $governmentPatterns = [
            '/\.gov$/',
            '/\.gov\.[a-z]{2}$/',
            '/\.gouv\.[a-z]{2}$/',
            '/\.gob\.[a-z]{2}$/',
            '/\.go\.[a-z]{2}$/',
            '/\.govt\.[a-z]{2}$/',
            '/\.bund\.[a-z]{2}$/',
            '/\.admin\.[a-z]{2}$/',
            '/^gov\./',
            '/^government\./',
            '/^ministry\./',
            '/^embassy\./',
            '/^consulate\./',
            '/^municipality\./',
        ];

        foreach ($governmentPatterns as $pattern) {
            if (preg_match($pattern, $domain)) {
                return true;
            }
        }

        return false;
    }
}
