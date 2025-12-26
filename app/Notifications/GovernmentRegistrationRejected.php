<?php

namespace App\Notifications;

use App\Models\GovernmentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GovernmentRegistrationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public GovernmentRegistration $registration
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Government Account Registration - Decision')
            ->greeting('Hello,')
            ->line('Thank you for your interest in CulturalTranslate Government Services.')
            ->line('After careful review, we are unable to approve your government account registration at this time.')
            ->line('**Reason**: ' . $this->registration->rejection_reason)
            ->line('If you believe this decision was made in error, or if you have additional documentation that may help verify your government affiliation, please contact our support team.')
            ->action('Contact Support', url('/contact'))
            ->line('Thank you for your understanding.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'registration_id' => $this->registration->id,
            'entity_name' => $this->registration->entity_name,
            'rejection_reason' => $this->registration->rejection_reason,
            'message' => 'Your government account registration was not approved.',
        ];
    }
}
