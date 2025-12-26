<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_filename',
        'file_type',
        'file_path',
        'file_size',
        'source_language',
        'target_language',
        'preserve_layout',
        'cultural_adaptation',
        'status',
        'pages_count',
        'translated_file_path',
        'error_message',
        'completed_at',
    ];

    protected $casts = [
        'preserve_layout' => 'boolean',
        'cultural_adaptation' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * علاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحصول على حجم الملف بصيغة قابلة للقراءة
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * الحصول على أيقونة نوع الملف
     */
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => '📄',
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'docx' => '📝',
        ];
        
        return $icons[$this->file_type] ?? '📁';
    }

    /**
     * الحصول على لون الحالة
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * الحصول على نص الحالة بالعربية
     */
    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'في الانتظار',
            'processing' => 'جاري المعالجة',
            'completed' => 'مكتمل',
            'failed' => 'فشل',
        ];
        
        return $texts[$this->status] ?? 'غير معروف';
    }
}
