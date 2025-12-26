<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PartnerUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'partner_id', 'name', 'email', 'password', 'role', 'is_active', 'last_login_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
