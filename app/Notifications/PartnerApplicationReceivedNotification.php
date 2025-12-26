<?php

namespace App\Notifications;

use App\Models\PartnerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PartnerApplicationReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(PartnerApplication $application)
    {
        $this->application = $application;
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
            ->subject('تم استلام طلب الشراكة - Cultural Translate')
            ->greeting('مرحباً ' . $this->application->contact_name . '!')
            ->line('شكراً لك على اهتمامك بالشراكة مع Cultural Translate.')
            ->line('لقد استلمنا طلبك بنجاح وسيقوم فريقنا بمراجعته في أقرب وقت ممكن.')
            ->line('**تفاصيل طلبك:**')
            ->line('• **الشركة:** ' . $this->application->company_name)
            ->line('• **نوع الشراكة:** ' . $this->getPartnershipTypeLabel($this->application->partnership_type))
            ->line('• **رقم المرجع:** #' . $this->application->id)
            ->line('سنتواصل معك خلال 2-3 أيام عمل للخطوات التالية.')
            ->line('إذا كان لديك أي استفسار، يمكنك التواصل معنا على:')
            ->line('📧 info@culturaltranslate.com')
            ->salutation('مع أطيب التحيات، فريق Cultural Translate');
    }

    /**
     * Get partnership type label in Arabic
     */
    private function getPartnershipTypeLabel($type): string
    {
        $types = [
            'reseller' => 'موزع',
            'affiliate' => 'أفلييت',
            'technology' => 'شريك تقني',
            'white_label' => 'وايت ليبل',
            'other' => 'أخرى',
        ];

        return $types[$type] ?? $type;
    }
}
