<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationRegistry extends Model
{
    use HasFactory;

    protected $table = 'verification_registry';

    protected $fillable = [
        'cts_certificate_id',
        'verification_code',
        'verifier_ip',
        'verifier_country',
        'verifier_user_agent',
        'verified_at',
        'verification_count',
        'last_verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'last_verified_at' => 'datetime',
    ];

    /**
     * Get the certificate being verified
     */
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(CtsCertificate::class, 'cts_certificate_id');
    }

    /**
     * Generate unique verification code
     */
    public static function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));
        } while (self::where('verification_code', $code)->exists());

        return $code;
    }

    /**
     * Record a verification attempt
     */
    public function recordVerification(?string $ip = null, ?string $country = null, ?string $userAgent = null): void
    {
        $this->increment('verification_count');
        $this->update([
            'last_verified_at' => now(),
            'verifier_ip' => $ip ?? $this->verifier_ip,
            'verifier_country' => $country ?? $this->verifier_country,
            'verifier_user_agent' => $userAgent ?? $this->verifier_user_agent,
        ]);
    }
}
