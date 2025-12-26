<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SandboxApiKey extends Model
{
    protected $fillable = [
        'sandbox_instance_id',
        'key',
        'name',
        'scopes',
        'rate_limit_profile',
        'last_used_at',
    ];

    protected $casts = [
        'scopes' => 'array',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'key',
    ];

    // Relationships
    public function sandboxInstance(): BelongsTo
    {
        return $this->belongsTo(SandboxInstance::class);
    }

    // Helpers
    public static function generateKey(): string
    {
        return 'sbx_test_' . Str::random(48);
    }

    public function hasScope(string $scope): bool
    {
        return in_array($scope, $this->scopes ?? []) || in_array('*', $this->scopes ?? []);
    }

    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    public function getMaskedKey(): string
    {
        return substr($this->key, 0, 12) . '...' . substr($this->key, -4);
    }
}
