<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SandboxInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'api_key', 'api_secret', 'status',
        'expires_at', 'rate_limit', 'requests_count', 'last_request_at',
        'allowed_domains', 'webhook_url', 'features',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_request_at' => 'datetime',
        'allowed_domains' => 'array',
        'features' => 'array',
        'rate_limit' => 'integer',
        'requests_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->api_key)) {
                $model->api_key = 'ct_' . Str::random(40);
            }
            if (empty($model->api_secret)) {
                $model->api_secret = Str::random(64);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apiLogs(): HasMany
    {
        return $this->hasMany(ApiLog::class, 'sandbox_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function incrementRequests(): void
    {
        $this->increment('requests_count');
        $this->update(['last_request_at' => now()]);
    }
}
