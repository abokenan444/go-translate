<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'admin_reply',
        'replied_at',
        'replied_by',
        'ip_address',
        'user_agent',
        'recaptcha_score',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'recaptcha_score' => 'float',
    ];

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeSuspicious($query, $threshold = 0.5)
    {
        return $query->where('recaptcha_score', '<', $threshold);
    }
}
