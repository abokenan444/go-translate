<?php

namespace App\Notifications;

use App\Models\GovernmentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GovernmentRegistrationApproved extends Notification implements ShouldQueue
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
            ->subject('Government Account Approved - CulturalTranslate')
            ->greeting('Congratulations!')
            ->line('Your government account registration has been approved.')
            ->line('**Entity**: ' . $this->registration->entity_name)
            ->line('**Country**: ' . $this->registration->country)
            ->line('You now have access to all government features including:')
            ->line('• Verified Government Badge')
            ->line('• Priority Translation Processing')
            ->line('• Official Document Management')
            ->line('• CTS™ Certification Access')
            ->action('Access Your Dashboard', route('government.dashboard'))
            ->line('If you need to set/reset your password, please use the link below:')
            ->action('Set Password', route('password.reset.token', ['token' => 'WILL_BE_GENERATED']))
            ->line('Thank you for choosing CulturalTranslate!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'registration_id' => $this->registration->id,
            'entity_name' => $this->registration->entity_name,
            'message' => 'Your government account has been approved.',
            'action_url' => route('government.dashboard'),
        ];
    }
}
