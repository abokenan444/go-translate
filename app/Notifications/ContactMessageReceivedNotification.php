<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageReceivedNotification extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم استلام رسالتك - Cultural Translate')
            ->greeting('مرحباً ' . $this->contact->name . '!')
            ->line('شكراً لك على تواصلك مع Cultural Translate.')
            ->line('لقد استلمنا رسالتك بنجاح وسيقوم فريق الدعم بالرد عليك في أقرب وقت ممكن.')
            ->line('**تفاصيل رسالتك:**')
            ->line('• **الموضوع:** ' . ($this->contact->subject ?? 'غير محدد'))
            ->line('• **رقم المرجع:** #' . $this->contact->id)
            ->line('عادةً ما نرد على الرسائل خلال 24 ساعة عمل.')
            ->line('إذا كان الأمر عاجلاً، يمكنك التواصل معنا مباشرة على:')
            ->line('📧 info@culturaltranslate.com')
            ->line('📞 يمكنك أيضاً الاتصال بنا للحصول على رد فوري')
            ->salutation('مع أطيب التحيات، فريق Cultural Translate');
    }
}
