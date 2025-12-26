<?php

namespace App\Notifications;

use App\Models\PartnerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPartnerApplicationNotification extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url('/admin/partner-applications/' . $this->application->id);
        
        return (new MailMessage)
            ->subject('طلب شراكة جديد - ' . $this->application->company_name)
            ->greeting('مرحباً!')
            ->line('تم استلام طلب شراكة جديد في منصة Cultural Translate.')
            ->line('**تفاصيل الطلب:**')
            ->line('• **الشركة:** ' . $this->application->company_name)
            ->line('• **الاسم:** ' . $this->application->contact_name)
            ->line('• **البريد:** ' . $this->application->email)
            ->line('• **الهاتف:** ' . $this->application->phone)
            ->line('• **نوع الشراكة:** ' . $this->getPartnershipTypeLabel($this->application->partnership_type))
            ->line('• **reCAPTCHA Score:** ' . number_format($this->application->recaptcha_score ?? 0, 2))
            ->line('• **IP Address:** ' . $this->application->ip_address)
            ->action('عرض الطلب', $url)
            ->line('يرجى مراجعة الطلب واتخاذ الإجراء المناسب.')
            ->salutation('مع تحياتنا، فريق Cultural Translate');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'partner_application',
            'application_id' => $this->application->id,
            'company_name' => $this->application->company_name,
            'contact_name' => $this->application->contact_name,
            'email' => $this->application->email,
            'partnership_type' => $this->application->partnership_type,
            'recaptcha_score' => $this->application->recaptcha_score,
            'message' => 'طلب شراكة جديد من ' . $this->application->company_name,
        ];
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
