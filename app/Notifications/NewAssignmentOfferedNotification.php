<?php

namespace App\Notifications;

use App\Models\DocumentAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAssignmentOfferedNotification extends Notification implements ShouldQueue
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
        $expiresAt = $this->assignment->expires_at;
        $timeRemaining = $expiresAt ? $expiresAt->diffForHumans() : 'N/A';
        $documentType = $this->assignment->document->document_type ?? 'Document';

        return (new MailMessage)
            ->subject('New Document Review Request - Action Required')
            ->greeting('Hello ' . $notifiable->display_name . ',')
            ->line('A new document requires your professional review.')
            ->line('**Document Type:** ' . ucfirst($documentType))
            ->line('**Language Pair:** ' . strtoupper($this->assignment->document->source_lang ?? 'N/A') . ' → ' . strtoupper($this->assignment->document->target_lang ?? 'N/A'))
            ->line('**Jurisdiction:** ' . ($this->assignment->document->jurisdiction_country ?? 'N/A'))
            ->line('**Response Required:** ' . $timeRemaining)
            ->line('Please accept or reject this assignment within the allowed time window.')
            ->action('Review Assignment', url('/partner/assignments'))
            ->line('If you do not respond, the offer will expire automatically and be reassigned to another reviewer.')
            ->salutation('Best regards, Cultural Translate Platform');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'assignment_offered',
            'assignment_id' => $this->assignment->id,
            'document_id' => $this->assignment->document_id,
            'document_type' => $this->assignment->document->document_type ?? null,
            'offer_group_id' => $this->assignment->offer_group_id,
            'expires_at' => $this->assignment->expires_at?->toIso8601String(),
            'priority_rank' => $this->assignment->priority_rank,
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'assignment-offered';
    }
}
