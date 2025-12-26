<?php

namespace App\Notifications;

use App\Models\DocumentAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentTimedOutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public DocumentAssignment $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(DocumentAssignment $assignment)
    {
        $this->assignment = $assignment;
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
            ->subject('Review Request Expired')
            ->greeting('Hello ' . $notifiable->display_name . ',')
            ->line('The review request for Document #' . $this->assignment->document_id . ' has expired.')
            ->line('No response was received within the allowed time window.')
            ->line('The document has been automatically reassigned to another reviewer.')
            ->action('View Assignments', url('/partner/assignments'))
            ->salutation('Best regards, Cultural Translate Platform');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'assignment_timed_out',
            'assignment_id' => $this->assignment->id,
            'document_id' => $this->assignment->document_id,
            'offer_group_id' => $this->assignment->offer_group_id,
            'expired_at' => $this->assignment->expires_at?->toIso8601String(),
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'assignment-timed-out';
    }
}
