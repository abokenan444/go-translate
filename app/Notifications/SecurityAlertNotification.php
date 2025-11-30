<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SecurityAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $attackType;
    public $attackDetails;
    public $timestamp;

    public function __construct($attackType, $attackDetails)
    {
        $this->attackType = $attackType;
        $this->attackDetails = $attackDetails;
        $this->timestamp = now();
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ تنبيه أمني: محاولة اختراق - ' . $this->attackType)
            ->line('**تم اكتشاف محاولة اختراق على المنصة!**')
            ->line('')
            ->line('**نوع الهجوم:** ' . $this->attackType)
            ->line('**الوقت:** ' . $this->timestamp->format('Y-m-d H:i:s'))
            ->line('')
            ->line('**التفاصيل:**')
            ->line('• عنوان IP: ' . ($this->attackDetails['ip'] ?? 'غير معروف'))
            ->line('• URL المستهدف: ' . ($this->attackDetails['url'] ?? 'غير معروف'))
            ->line('• الحقل: ' . ($this->attackDetails['input'] ?? 'غير معروف'))
            ->line('• القيمة المشبوهة: ' . substr($this->attackDetails['value'] ?? '', 0, 100))
            ->line('')
            ->line('• User Agent: ' . ($this->attackDetails['user_agent'] ?? 'غير معروف'))
            ->line('• Referer: ' . ($this->attackDetails['referer'] ?? 'غير معروف'))
            ->line('')
            ->action('عرض سجلات الأمان', url('/admin/security-logs'))
            ->line('يرجى مراجعة محاولة الاختراق واتخاذ الإجراءات المناسبة.')
            ->error();
    }

    public function toArray($notifiable)
    {
        return [
            'attack_type' => $this->attackType,
            'ip' => $this->attackDetails['ip'] ?? null,
            'url' => $this->attackDetails['url'] ?? null,
            'input' => $this->attackDetails['input'] ?? null,
            'value' => substr($this->attackDetails['value'] ?? '', 0, 200),
            'user_agent' => $this->attackDetails['user_agent'] ?? null,
            'referer' => $this->attackDetails['referer'] ?? null,
            'timestamp' => $this->timestamp,
        ];
    }
}
