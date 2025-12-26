<?php

namespace App\Mail;

use App\Models\OfficialDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;

class DocumentProcessed extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $user;
    public $downloadUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(OfficialDocument $document)
    {
        $this->document = $document;
        $this->user = $document->order->user;
        $this->downloadUrl = route('official.documents.download', $document->certificate->cert_id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [new Address('support@culturaltranslate.com', 'Cultural Translate Support')],
            subject: 'Your Document Translation is Ready!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.document-processed',
            text: 'emails.document-processed-text',
            with: [
                'documentName' => $this->document->original_filename,
                'documentType' => $this->document->document_type,
                'sourceLang' => $this->document->source_language,
                'targetLang' => $this->document->target_language,
                'userName' => $this->user->name,
                'downloadUrl' => $this->downloadUrl,
                'certificateId' => $this->document->certificate->cert_id ?? 'N/A',
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
