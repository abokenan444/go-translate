<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ContactAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $addedBy;
    protected bool $isRegistered;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $addedBy, bool $isRegistered = false)
    {
        $this->addedBy = $addedBy;
        $this->isRegistered = $isRegistered;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // If user is registered, store in database too
        if ($this->isRegistered) {
            return ['mail', 'database'];
        }
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'CulturalTranslate');
        $downloadUrl = config('app.url', 'https://culturaltranslate.com') . '/download';
        
        if ($this->isRegistered) {
            return (new MailMessage)
                ->subject("{$this->addedBy->name} added you as a contact on {$appName}")
                ->greeting("Hello {$notifiable->name}!")
                ->line("{$this->addedBy->name} has added you as a contact on {$appName}.")
                ->line("You can now receive voice calls with real-time translation in over 100 languages!")
                ->action('Open App', $downloadUrl)
                ->line("Start communicating without language barriers today.");
        }
        
        // For non-registered users
        return (new MailMessage)
            ->subject("You've been invited to {$appName}!")
            ->greeting("Hello!")
            ->line("{$this->addedBy->name} wants to connect with you on {$appName}.")
            ->line("{$appName} is a revolutionary voice calling app with real-time translation in over 100 languages.")
            ->line("Features:")
            ->line("• Voice calls with instant translation")
            ->line("• Support for 100+ languages")
            ->line("• Crystal clear audio quality")
            ->line("• Easy to use interface")
            ->action('Download Now', $downloadUrl)
            ->line("Join now and start communicating without language barriers!");
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'contact_added',
            'title' => 'New Contact Added You',
            'body' => "{$this->addedBy->name} has added you as a contact. You can now receive calls from them.",
            'added_by_id' => $this->addedBy->id,
            'added_by_name' => $this->addedBy->name,
            'added_by_email' => $this->addedBy->email,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
