<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'site_url',
        'credentials',
        'status',
        'metadata',
        'last_sync_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_sync_at' => 'datetime',
    ];

    /**
     * Get the user that owns the integration
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Check if integration is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
    
    /**
     * Get decrypted credentials
     */
    public function getCredentials()
    {
        return decrypt($this->credentials);
    }
}
