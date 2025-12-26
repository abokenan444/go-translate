<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageResponse extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public string $responseMessage
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Response to your message: ' . $this->contactMessage->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.admin-response',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
