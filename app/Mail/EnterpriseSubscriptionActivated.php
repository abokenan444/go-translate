<?php

namespace App\Mail;

use App\Models\EnterpriseSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnterpriseSubscriptionActivated extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;

    public function __construct(EnterpriseSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم تفعيل اشتراك المؤسسة - ' . $this->subscription->company_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enterprise-subscription-activated',
        );
    }
}
