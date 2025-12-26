<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GovernmentAccountRevoked extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Government Verification Status Update')
            ->greeting('Important Notice')
            ->line('Your government account verification has been revoked.')
            ->line('Access to government-specific features has been suspended.')
            ->line('If you believe this was done in error, please contact our support team immediately.')
            ->action('Contact Support', url('/contact'))
            ->line('Thank you for your understanding.');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Your government verification has been revoked.',
            'action_url' => url('/contact'),
        ];
    }
}
