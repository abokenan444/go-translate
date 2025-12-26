<?php

namespace App\Mail;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiring extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Carbon $expiryDate,
        public int $daysRemaining
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Subscription Expires in {$this->daysRemaining} Days ⚠️",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription.expiring',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
