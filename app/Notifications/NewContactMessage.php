<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Contact Message from ' . $this->contact->name)
            ->greeting('New Contact Message Received!')
            ->line('You have received a new contact message from your website.')
            ->line('**Name:** ' . $this->contact->name)
            ->line('**Email:** ' . $this->contact->email)
            ->line('**Subject:** ' . $this->contact->subject)
            ->line('**Message:**')
            ->line($this->contact->message)
            ->action('View in Admin Panel', url('/admin/contacts/' . $this->contact->id))
            ->line('Please respond to this inquiry as soon as possible.');
    }
}
