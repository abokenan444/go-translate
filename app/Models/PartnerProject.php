<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id', 'name', 'description', 'status',
        'total_translations', 'total_revenue',
    ];

    protected $casts = [
        'total_revenue' => 'decimal:2',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
