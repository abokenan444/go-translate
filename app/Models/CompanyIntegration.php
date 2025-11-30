<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyIntegration extends Model
{
    protected $fillable = [
        'company_id',
        'provider',
        'api_key',
        'api_secret',
        'webhook_url',
        'domains',
        'events',
        'features_flags',
        'status',
        'last_success_at',
        'last_error_at',
    ];

    protected $casts = [
        'domains' => 'array',
        'events' => 'array',
        'features_flags' => 'array',
        'last_success_at' => 'datetime',
        'last_error_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
