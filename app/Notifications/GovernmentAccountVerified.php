<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GovernmentAccountVerified extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Government Account Verified - CulturalTranslate')
            ->greeting('Congratulations!')
            ->line('Your government account has been verified and activated.')
            ->line('You now have access to all government features including:')
            ->line('• Verified Government Badge')
            ->line('• Priority Translation Processing')
            ->line('• Official Document Management')
            ->line('• Government Portal Access')
            ->action('Access Government Portal', url('/government/dashboard'))
            ->line('Thank you for choosing CulturalTranslate!');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Your government account has been verified.',
            'action_url' => url('/government/dashboard'),
        ];
    }
}
