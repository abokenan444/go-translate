<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertifiedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cert_number',
        'document_type',
        'original_filename',
        'original_path',
        'translated_path',
        'source_language',
        'target_language',
        'original_text',
        'translated_text',
        'status',
        'verified_at',
        'payment_type',
        'amount_paid',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحصول على رابط التحقق
     */
    public function getVerificationUrlAttribute()
    {
        return route('certified.verify', $this->cert_number);
    }

    /**
     * الحصول على رابط التحميل
     */
    public function getDownloadUrlAttribute()
    {
        return route('certified.download', $this->cert_number);
    }
}
