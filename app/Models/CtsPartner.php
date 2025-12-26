<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CtsPartner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partner_name',
        'partner_type',
        'partner_code',
        'country_code',
        'email',
        'phone',
        'address',
        'license_number',
        'seal_image_path',
        'status',
        'certification_date',
        'expiry_date',
        'permissions',
        'certificates_issued',
        'notes',
    ];

    protected $casts = [
        'permissions' => 'array',
        'certification_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the certificates issued by this partner
     */
    public function certificates()
    {
        return $this->belongsToMany(CtsCertificate::class, 'partner_certificates')
                    ->withPivot('partner_seal_applied', 'sealed_at')
                    ->withTimestamps();
    }

    /**
     * Check if partner is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               (!$this->expiry_date || $this->expiry_date->isFuture());
    }

    /**
     * Check if partner can certify a specific type
     */
    public function canCertify(string $type): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        $permissions = $this->permissions ?? [];
        return in_array($type, $permissions) || in_array('all', $permissions);
    }

    /**
     * Increment certificates issued counter
     */
    public function incrementCertificates(): void
    {
        $this->increment('certificates_issued');
    }

    /**
     * Get partner type label
     */
    public function getTypeLabel(): string
    {
        return match($this->partner_type) {
            'certified_translator' => 'Certified Translator',
            'law_firm' => 'Law Firm',
            'translation_agency' => 'Translation Agency',
            'corporate' => 'Corporate Partner',
            'university' => 'University/Academic',
            default => ucfirst($this->partner_type),
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'active' => 'green',
            'pending' => 'yellow',
            'suspended' => 'orange',
            'revoked' => 'red',
            default => 'gray',
        };
    }
}
