<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contact;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
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
        $url = url('/admin/contact-messages/' . $this->contact->id);
        
        return (new MailMessage)
            ->subject('رسالة اتصال جديدة - ' . $this->contact->name)
            ->greeting('مرحباً!')
            ->line('تم استلام رسالة اتصال جديدة في منصة Cultural Translate.')
            ->line('**تفاصيل الرسالة:**')
            ->line('• **الاسم:** ' . $this->contact->name)
            ->line('• **البريد:** ' . $this->contact->email)
            ->line('• **الموضوع:** ' . ($this->contact->subject ?? 'غير محدد'))
            ->line('• **الرسالة:**')
            ->line($this->contact->message)
            ->line('• **reCAPTCHA Score:** ' . number_format($this->contact->recaptcha_score ?? 0, 2))
            ->action('عرض الرسالة', $url)
            ->line('يرجى الرد على الرسالة في أقرب وقت ممكن.')
            ->salutation('مع تحياتنا، فريق Cultural Translate');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'contact_message',
            'contact_id' => $this->contact->id,
            'name' => $this->contact->name,
            'email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'recaptcha_score' => $this->contact->recaptcha_score,
            'message' => 'رسالة اتصال جديدة من ' . $this->contact->name,
        ];
    }
}
