<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'sandbox_id', 'endpoint', 'method', 'request_payload',
        'response_payload', 'status_code', 'response_time', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'status_code' => 'integer',
        'response_time' => 'integer',
    ];

    public function sandboxInstance(): BelongsTo
    {
        return $this->belongsTo(SandboxInstance::class, 'sandbox_id');
    }
}
