<?php

namespace App\Mail;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionActivated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public SubscriptionPlan $plan
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Subscription is Now Active! 🎊',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription.activated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
