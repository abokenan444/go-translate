<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovEntity extends Model
{
    use SoftDeletes;

    protected $table = 'gov_entities';

    protected $fillable = [
        'entity_code',
        'entity_name',
        'entity_type',
        'country_code',
        'jurisdiction',
        'official_email',
        'official_phone',
        'official_address',
        'subdomain',
        'allowed_domains',
        'ip_whitelist',
        'status',
        'is_verified',
        'verified_at',
        'verified_by',
        'pilot_config',
        'sla_config',
        'compliance_requirements'
    ];

    protected $casts = [
        'allowed_domains' => 'array',
        'ip_whitelist' => 'array',
        'pilot_config' => 'array',
        'sla_config' => 'array',
        'compliance_requirements' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime'
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(GovContact::class, 'gov_entity_id');
    }

    public function primaryContact(): HasMany
    {
        return $this->hasMany(GovContact::class, 'gov_entity_id')->where('is_primary', true);
    }

    public function pilots(): HasMany
    {
        return $this->hasMany(GovPilot::class, 'gov_entity_id');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(GovInvite::class, 'gov_entity_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->is_verified;
    }

    public function canUpload(): bool
    {
        $activePilot = $this->pilots()->where('status', 'active')->first();
        return $this->isActive() && $activePilot !== null;
    }

    public function isDomainAllowed(string $email): bool
    {
        if (empty($this->allowed_domains)) {
            return true;
        }

        $domain = '@' . substr(strrchr($email, '@'), 1);
        return in_array($domain, $this->allowed_domains);
    }

    public function isIpAllowed(string $ip): bool
    {
        if (empty($this->ip_whitelist)) {
            return true;
        }

        return in_array($ip, $this->ip_whitelist);
    }
}
