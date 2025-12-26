<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UniversityNotification extends Notification implements ShouldQueue
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
            'student_limit_reached' => $this->studentLimitReached($notifiable),
            'subscription_expiring' => $this->subscriptionExpiring($notifiable),
            default => $this->defaultNotification($notifiable),
        };
    }

    protected function applicationPending($notifiable)
    {
        return (new MailMessage)
            ->subject('University Registration Received')
            ->greeting('Thank you for registering!')
            ->line('We have received your university registration application.')
            ->line('Your application is currently under review by our verification team.')
            ->line('You will receive a notification once your application has been reviewed.')
            ->line('This process typically takes 2-5 business days.')
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you for choosing CulturalTranslate!');
    }

    protected function applicationApproved($notifiable)
    {
        return (new MailMessage)
            ->subject('University Account Approved')
            ->greeting('Congratulations!')
            ->line('Your university account has been approved and activated.')
            ->line('You can now:')
            ->line('• Add student accounts')
            ->line('• Access university dashboard')
            ->line('• Manage translation services')
            ->line('• View usage reports and analytics')
            ->action('Access University Dashboard', url('/university/dashboard'))
            ->line('Welcome to CulturalTranslate!');
    }

    protected function applicationRejected($notifiable)
    {
        return (new MailMessage)
            ->subject('University Registration Update')
            ->greeting('Hello,')
            ->line('Thank you for your interest in CulturalTranslate.')
            ->line('Unfortunately, we are unable to approve your university registration at this time.')
            ->line('Reason: ' . ($this->data['reason'] ?? 'Not specified'))
            ->line('If you believe this was an error, please contact our support team.')
            ->action('Contact Support', url('/contact'))
            ->line('Thank you for your understanding.');
    }

    protected function studentLimitReached($notifiable)
    {
        return (new MailMessage)
            ->subject('Student Limit Reached')
            ->greeting('Attention')
            ->line('You have reached your maximum student limit of ' . ($this->data['limit'] ?? 0) . ' students.')
            ->line('Current students: ' . ($this->data['current'] ?? 0))
            ->line('To add more students, please upgrade your plan.')
            ->action('Upgrade Plan', url('/university/subscription'))
            ->line('Contact us if you need assistance.');
    }

    protected function subscriptionExpiring($notifiable)
    {
        return (new MailMessage)
            ->subject('University Subscription Expiring Soon')
            ->greeting('Reminder')
            ->line('Your university subscription will expire in ' . ($this->data['days'] ?? 0) . ' days.')
            ->line('Renew now to avoid service interruption for your students.')
            ->action('Renew Subscription', url('/university/subscription'))
            ->line('Thank you for your continued partnership!');
    }

    protected function defaultNotification($notifiable)
    {
        return (new MailMessage)
            ->subject($this->data['subject'] ?? 'University Notification')
            ->greeting($this->data['greeting'] ?? 'Hello!')
            ->line($this->data['message'] ?? 'You have a new notification.')
            ->action($this->data['action_text'] ?? 'View Dashboard', $this->data['action_url'] ?? url('/university/dashboard'));
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
            'application_pending' => 'Your university registration is under review',
            'application_approved' => 'Your university account has been approved',
            'application_rejected' => 'Your university registration was not approved',
            'student_limit_reached' => 'Student limit reached',
            'subscription_expiring' => 'Subscription expires in ' . ($this->data['days'] ?? 0) . ' days',
            default => $this->data['message'] ?? 'New notification',
        };
    }
}
