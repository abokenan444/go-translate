<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_id',
        'enabled_features',
        'allowed_models',
        'rate_limit_per_minute',
        'max_tokens_monthly',
    ];

    protected $casts = [
        'enabled_features' => 'array',
        'allowed_models' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
