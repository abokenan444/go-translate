<?php

namespace App\Services\LiveCall;

use App\Models\User;

class LiveCallPricingService
{
    /**
     * Get price per minute based on user account type
     */
    public function pricePerMinute(User $user): float
    {
        $type = $user->account_type ?? 'customer';

        return match ($type) {
            'government' => 0.08,
            'enterprise' => 0.12,
            'partner', 'translator' => 0.18,
            default => 0.35,
        };
    }
}
