<?php

namespace App\Services;

use App\Models\Partner;
use App\Notifications\PartnerNotification;
use Illuminate\Support\Facades\Log;

class PartnerNotificationService
{
    /**
     * Send application approved notification
     */
    public function notifyApplicationApproved(Partner $partner): void
    {
        $partner->user->notify(new PartnerNotification('application_approved'));
        
        Log::info('Partner application approved notification sent', [
            'partner_id' => $partner->id,
            'email' => $partner->email,
        ]);
    }

    /**
     * Send application rejected notification
     */
    public function notifyApplicationRejected(Partner $partner, string $reason = ''): void
    {
        $partner->user->notify(new PartnerNotification('application_rejected', [
            'reason' => $reason,
        ]));
        
        Log::info('Partner application rejected notification sent', [
            'partner_id' => $partner->id,
            'reason' => $reason,
        ]);
    }

    /**
     * Send subscription created notification
     */
    public function notifySubscriptionCreated(Partner $partner, $subscription): void
    {
        $partner->user->notify(new PartnerNotification('subscription_created', [
            'tier' => ucfirst($subscription->subscription_tier),
            'quota' => $subscription->monthly_quota,
            'next_billing' => $subscription->next_billing_date?->format('Y-m-d'),
        ]));
    }

    /**
     * Send subscription expiring notification
     */
    public function notifySubscriptionExpiring(Partner $partner, $subscription, int $days): void
    {
        $partner->user->notify(new PartnerNotification('subscription_expiring', [
            'days' => $days,
            'expiration_date' => $subscription->end_date?->format('Y-m-d'),
        ]));
    }

    /**
     * Send subscription expired notification
     */
    public function notifySubscriptionExpired(Partner $partner, $subscription): void
    {
        $partner->user->notify(new PartnerNotification('subscription_expired'));
    }

    /**
     * Send API key created notification
     */
    public function notifyApiKeyCreated(Partner $partner, $apiKey): void
    {
        $partner->user->notify(new PartnerNotification('api_key_created', [
            'key_name' => $apiKey->key_name,
            'environment' => ucfirst($apiKey->environment),
        ]));
    }

    /**
     * Send API key expiring notification
     */
    public function notifyApiKeyExpiring(Partner $partner, $apiKey, int $days): void
    {
        $partner->user->notify(new PartnerNotification('api_key_expiring', [
            'key_name' => $apiKey->key_name,
            'days' => $days,
        ]));
    }

    /**
     * Send commission approved notification
     */
    public function notifyCommissionApproved(Partner $partner, $commission): void
    {
        $partner->user->notify(new PartnerNotification('commission_approved', [
            'amount' => $commission->commission_amount,
            'transaction_id' => $commission->id,
        ]));
    }

    /**
     * Send commission paid notification
     */
    public function notifyCommissionPaid(Partner $partner, $commission): void
    {
        $partner->user->notify(new PartnerNotification('commission_paid', [
            'amount' => $commission->commission_amount,
            'payment_method' => $commission->payment_method ?? 'Bank Transfer',
        ]));
    }

    /**
     * Send quota warning notification
     */
    public function notifyQuotaWarning(Partner $partner, array $usage): void
    {
        $partner->user->notify(new PartnerNotification('quota_warning', [
            'percentage' => $usage['percentage'],
            'used' => $usage['used'],
            'remaining' => $usage['remaining'],
        ]));
    }

    /**
     * Send quota exceeded notification
     */
    public function notifyQuotaExceeded(Partner $partner): void
    {
        $partner->user->notify(new PartnerNotification('quota_exceeded'));
    }

    /**
     * Send custom notification
     */
    public function sendCustomNotification(Partner $partner, array $data): void
    {
        $partner->user->notify(new PartnerNotification('custom', $data));
    }

    /**
     * Check and send quota warnings
     */
    public function checkQuotaAndNotify(Partner $partner, array $usage): void
    {
        $percentage = $usage['percentage'] ?? 0;

        // Send warning at 80%
        if ($percentage >= 80 && $percentage < 100) {
            $this->notifyQuotaWarning($partner, $usage);
        }

        // Send exceeded notification at 100%
        if ($percentage >= 100) {
            $this->notifyQuotaExceeded($partner);
        }
    }

    /**
     * Check and notify expiring subscriptions
     */
    public function checkExpiringSubscriptions(): void
    {
        $partners = Partner::with(['activeSubscription', 'user'])->get();

        foreach ($partners as $partner) {
            $subscription = $partner->activeSubscription;
            
            if (!$subscription || !$subscription->end_date) {
                continue;
            }

            $daysUntilExpiry = now()->diffInDays($subscription->end_date, false);

            // Notify 7 days before expiry
            if ($daysUntilExpiry === 7) {
                $this->notifySubscriptionExpiring($partner, $subscription, 7);
            }

            // Notify 1 day before expiry
            if ($daysUntilExpiry === 1) {
                $this->notifySubscriptionExpiring($partner, $subscription, 1);
            }

            // Notify on expiry
            if ($daysUntilExpiry === 0) {
                $this->notifySubscriptionExpired($partner, $subscription);
            }
        }
    }

    /**
     * Check and notify expiring API keys
     */
    public function checkExpiringApiKeys(): void
    {
        $partners = Partner::with(['apiKeys', 'user'])->get();

        foreach ($partners as $partner) {
            foreach ($partner->apiKeys as $apiKey) {
                if (!$apiKey->expires_at) {
                    continue;
                }

                $daysUntilExpiry = now()->diffInDays($apiKey->expires_at, false);

                // Notify 7 days before expiry
                if ($daysUntilExpiry === 7) {
                    $this->notifyApiKeyExpiring($partner, $apiKey, 7);
                }

                // Notify 1 day before expiry
                if ($daysUntilExpiry === 1) {
                    $this->notifyApiKeyExpiring($partner, $apiKey, 1);
                }
            }
        }
    }
}
