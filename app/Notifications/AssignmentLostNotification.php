<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentLostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $documentId;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Review Assignment - Another Reviewer Selected')
            ->greeting('Hello ' . $notifiable->display_name . ',')
            ->line('Thank you for your response to the review request for Document #' . $this->documentId . '.')
            ->line('However, another reviewer was selected for this document.')
            ->line('This happens when multiple reviewers are offered the same document simultaneously, and another reviewer accepted first.')
            ->line('We appreciate your availability and will continue to send you relevant assignments.')
            ->action('View Available Assignments', url('/partner/assignments'))
            ->salutation('Best regards, Cultural Translate Platform');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'assignment_lost',
            'document_id' => $this->documentId,
            'reason' => 'Another reviewer was selected',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'assignment-lost';
    }
}
