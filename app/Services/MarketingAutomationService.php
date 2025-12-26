<?php

namespace App\Services;

use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Mail\SubscriptionExpiringEmail;
use Illuminate\Support\Facades\Mail;

class MarketingAutomationService
{
    public function sendWelcomeSequence(User $user)
    {
        // Send immediate welcome email
        Mail::to($user->email)->queue(new WelcomeEmail($user));

        // Schedule follow-up (Tip of the day) - In real app, use a Job with delay
        // ProcessMarketingEmail::dispatch($user, 'tips')->delay(now()->addDays(1));
    }

    public function checkExpiringSubscriptions()
    {
        // Mock logic: Find users with subscriptions expiring in 3 days
        // $users = User::whereHas('subscription', function($q) { ... })->get();
        
        // For demo, we'll just log it
        logger('Checking for expiring subscriptions...');
    }

    public function sendExpiringNotification(User $user)
    {
        Mail::to($user->email)->queue(new SubscriptionExpiringEmail($user));
    }
}
