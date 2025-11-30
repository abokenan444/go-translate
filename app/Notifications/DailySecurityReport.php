<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailySecurityReport extends Notification
{
    use Queueable;

    public function __construct(
        public array $stats
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('📊 تقرير الأمان اليومي - Daily Security Report')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line("**التاريخ / Date:** {$this->stats['date']}")
            ->line('')
            ->line('### 📈 ملخص الهجمات / Attack Summary')
            ->line("**إجمالي الهجمات / Total Attacks:** {$this->stats['total_attacks']}")
            ->line("**المحظور / Blocked:** {$this->stats['blocked']}")
            ->line("**خطورة عالية / High Severity:** {$this->stats['high_severity']}")
            ->line("**حرج / Critical:** {$this->stats['critical']}")
            ->line('');

        // Attack types breakdown
        if (!empty($this->stats['by_type'])) {
            $message->line('### 🎯 الهجمات حسب النوع / Attacks by Type');
            foreach ($this->stats['by_type'] as $type => $count) {
                $message->line("- **{$type}:** {$count}");
            }
            $message->line('');
        }

        // Top attacking IPs
        if (!empty($this->stats['top_ips'])) {
            $message->line('### 🌐 أكثر 10 عناوين IP نشاطاً / Top 10 Active IPs');
            $i = 1;
            foreach ($this->stats['top_ips'] as $ip => $count) {
                $message->line("{$i}. **{$ip}** - {$count} محاولات / attempts");
                $i++;
            }
        }

        $message->action('عرض السجلات الكاملة / View Full Logs', url('/admin/security-logs'))
            ->line('شكراً لحفاظك على أمان المنصة! / Thank you for keeping the platform secure!');

        return $message;
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'daily_security_report',
            'date' => $this->stats['date'],
            'total_attacks' => $this->stats['total_attacks'],
            'blocked' => $this->stats['blocked'],
            'high_severity' => $this->stats['high_severity'],
            'critical' => $this->stats['critical'],
            'by_type' => $this->stats['by_type'],
            'top_ips' => $this->stats['top_ips'],
        ];
    }
}
