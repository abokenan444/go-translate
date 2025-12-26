<?php

namespace App\Support;

use App\Models\User;

class AccountDashboard
{
    /**
     * Returns a URL path (not a route name) for the user's primary dashboard.
     */
    public static function pathFor(User $user): string
    {
        $accountType = (string) ($user->account_type ?? 'customer');

        return match ($accountType) {
            'affiliate' => '/dashboard/affiliate',
            'government' => '/dashboard/government',
            'translator' => '/dashboard/translator',
            'partner' => '/partner/dashboard',
            'individual', 'customer' => '/dashboard/customer',
            default => '/dashboard/customer',
        };
    }
}
