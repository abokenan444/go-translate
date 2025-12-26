<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PartnerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $data;

    public function __construct(string $type, array $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return match($this->type) {
            'application_pending' => $this->applicationPending($notifiable),
            'application_approved' => $this->applicationApproved($notifiable),
            'application_rejected' => $this->applicationRejected($notifiable),
            'subscription_created' => $this->subscriptionCreated($notifiable),
            'subscription_expiring' => $this->subscriptionExpiring($notifiable),
            'subscription_expired' => $this->subscriptionExpired($notifiable),
            'api_key_created' => $this->apiKeyCreated($notifiable),
            'api_key_expiring' => $this->apiKeyExpiring($notifiable),
            'commission_approved' => $this->commissionApproved($notifiable),
            'commission_paid' => $this->commissionPaid($notifiable),
            'quota_warning' => $this->quotaWarning($notifiable),
            'quota_exceeded' => $this->quotaExceeded($notifiable),
            default => $this->defaultNotification($notifiable),
        };
    }

    protected function applicationPending($notifiable)
    {
        return (new MailMessage)
            ->subject('Partnership Application Received')
            ->greeting('Thank you for applying!')
            ->line('We have received your partnership application.')
            ->line('Your application is currently under review by our team.')
            ->line('You will receive a notification once your application has been reviewed.')
            ->line('This process typically takes 1-3 business days.')
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you for your patience!');
    }

    protected function applicationApproved($notifiable)
    {
        return (new MailMessage)
            ->subject('Partnership Application Approved')
            ->greeting('Congratulations!')
            ->line('Your partnership application has been approved.')
            ->line('You can now access your partner dashboard and start using our services.')
            ->action('Go to Dashboard', url('/partner/dashboard'))
            ->line('Thank you for partnering with us!');
    }

    protected function applicationRejected($notifiable)
    {
        return (new MailMessage)
            ->subject('Partnership Application Update')
            ->greeting('Hello,')
            ->line('Thank you for your interest in partnering with us.')
            ->line('Unfortunately, we are unable to approve your application at this time.')
            ->line('Reason: ' . ($this->data['reason'] ?? 'Not specified'))
            ->line('You may reapply after addressing the concerns mentioned above.');
    }

    protected function subscriptionCreated($notifiable)
    {
        return (new MailMessage)
            ->subject('Subscription Activated')
            ->greeting('Welcome!')
            ->line('Your ' . ($this->data['tier'] ?? 'subscription') . ' subscription has been activated.')
            ->line('Monthly Quota: ' . number_format($this->data['quota'] ?? 0) . ' translations')
            ->line('Next Billing Date: ' . ($this->data['next_billing'] ?? 'N/A'))
            ->action('View Subscription', url('/partner/subscription'))
            ->line('Thank you for your business!');
    }

    protected function subscriptionExpiring($notifiable)
    {
        return (new MailMessage)
            ->subject('Subscription Expiring Soon')
            ->greeting('Reminder')
            ->line('Your subscription will expire in ' . ($this->data['days'] ?? 0) . ' days.')
            ->line('Expiration Date: ' . ($this->data['expiration_date'] ?? 'N/A'))
            ->action('Renew Subscription', url('/partner/subscription'))
            ->line('Renew now to avoid service interruption.');
    }

    protected function subscriptionExpired($notifiable)
    {
        return (new MailMessage)
            ->subject('Subscription Expired')
            ->greeting('Attention Required')
            ->line('Your subscription has expired.')
            ->line('Your services have been suspended until renewal.')
            ->action('Renew Now', url('/partner/subscription'))
            ->line('Contact support if you need assistance.');
    }

    protected function apiKeyCreated($notifiable)
    {
        return (new MailMessage)
            ->subject('New API Key Created')
            ->greeting('Hello!')
            ->line('A new API key has been created for your account.')
            ->line('Key Name: ' . ($this->data['key_name'] ?? 'N/A'))
            ->line('Environment: ' . ($this->data['environment'] ?? 'N/A'))
            ->line('Please store your API key securely. It will not be shown again.')
            ->action('View API Keys', url('/partner/api-keys'));
    }

    protected function apiKeyExpiring($notifiable)
    {
        return (new MailMessage)
            ->subject('API Key Expiring Soon')
            ->greeting('Security Notice')
            ->line('Your API key will expire in ' . ($this->data['days'] ?? 0) . ' days.')
            ->line('Key Name: ' . ($this->data['key_name'] ?? 'N/A'))
            ->action('Manage API Keys', url('/partner/api-keys'))
            ->line('Generate a new key to avoid service interruption.');
    }

    protected function commissionApproved($notifiable)
    {
        return (new MailMessage)
            ->subject('Commission Approved')
            ->greeting('Good News!')
            ->line('Your commission has been approved.')
            ->line('Amount: $' . number_format($this->data['amount'] ?? 0, 2))
            ->line('Transaction ID: ' . ($this->data['transaction_id'] ?? 'N/A'))
            ->action('View Earnings', url('/partner/earnings'))
            ->line('Payment will be processed shortly.');
    }

    protected function commissionPaid($notifiable)
    {
        return (new MailMessage)
            ->subject('Commission Payment Processed')
            ->greeting('Payment Received!')
            ->line('Your commission payment has been processed.')
            ->line('Amount: $' . number_format($this->data['amount'] ?? 0, 2))
            ->line('Payment Method: ' . ($this->data['payment_method'] ?? 'N/A'))
            ->action('View Earnings', url('/partner/earnings'))
            ->line('Thank you for your partnership!');
    }

    protected function quotaWarning($notifiable)
    {
        return (new MailMessage)
            ->subject('Quota Usage Warning')
            ->greeting('Usage Alert')
            ->line('You have used ' . ($this->data['percentage'] ?? 0) . '% of your monthly quota.')
            ->line('Used: ' . number_format($this->data['used'] ?? 0))
            ->line('Remaining: ' . number_format($this->data['remaining'] ?? 0))
            ->action('Upgrade Plan', url('/partner/subscription'))
            ->line('Consider upgrading to avoid service interruption.');
    }

    protected function quotaExceeded($notifiable)
    {
        return (new MailMessage)
            ->subject('Monthly Quota Exceeded')
            ->greeting('Quota Limit Reached')
            ->line('You have exceeded your monthly translation quota.')
            ->line('Your services may be temporarily limited.')
            ->action('Upgrade Now', url('/partner/subscription'))
            ->line('Upgrade your plan to continue using our services.');
    }

    protected function defaultNotification($notifiable)
    {
        return (new MailMessage)
            ->subject($this->data['subject'] ?? 'Notification')
            ->greeting($this->data['greeting'] ?? 'Hello!')
            ->line($this->data['message'] ?? 'You have a new notification.')
            ->action($this->data['action_text'] ?? 'View Details', $this->data['action_url'] ?? url('/partner/dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
            'message' => $this->getNotificationMessage(),
        ];
    }

    protected function getNotificationMessage(): string
    {
        return match($this->type) {
            'application_pending' => 'Your partnership application is under review',
            'application_approved' => 'Your partnership application has been approved',
            'application_rejected' => 'Your partnership application was not approved',
            'subscription_created' => 'Your subscription has been activated',
            'subscription_expiring' => 'Your subscription expires in ' . ($this->data['days'] ?? 0) . ' days',
            'subscription_expired' => 'Your subscription has expired',
            'api_key_created' => 'New API key created',
            'api_key_expiring' => 'API key expiring soon',
            'commission_approved' => 'Commission approved: $' . number_format($this->data['amount'] ?? 0, 2),
            'commission_paid' => 'Commission paid: $' . number_format($this->data['amount'] ?? 0, 2),
            'quota_warning' => 'Quota usage at ' . ($this->data['percentage'] ?? 0) . '%',
            'quota_exceeded' => 'Monthly quota exceeded',
            default => $this->data['message'] ?? 'New notification',
        };
    }
}
