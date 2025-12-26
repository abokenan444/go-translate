<?php

namespace App\Notifications;

use App\Models\GovernmentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GovernmentRegistrationMoreInfoRequired extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public GovernmentRegistration $registration,
        public string $requestedInfo
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Additional Information Required - Government Account')
            ->greeting('Hello,')
            ->line('Thank you for your government account registration.')
            ->line('To proceed with the verification process, we need some additional information from you:')
            ->line($this->requestedInfo)
            ->line('Please log in to your account and provide the requested documents or information.')
            ->action('Submit Additional Info', route('government.additional-info'))
            ->line('If you have any questions, please don\'t hesitate to contact our support team.')
            ->line('Your registration ID: ' . $this->registration->id);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'registration_id' => $this->registration->id,
            'entity_name' => $this->registration->entity_name,
            'requested_info' => $this->requestedInfo,
            'message' => 'Additional information required for your government account.',
            'action_url' => route('government.additional-info'),
        ];
    }
}
