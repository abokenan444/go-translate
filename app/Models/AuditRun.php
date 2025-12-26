<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditRun extends Model
{
    protected $fillable = [
        'scope','dir','status','total','passed','failed','warn',
        'modules','report_html_path','report_json_path',
        'is_release_candidate','marked_by','marked_at','release_notes',
    ];

    protected $casts = [
        'modules' => 'array',
        'is_release_candidate' => 'boolean',
        'marked_at' => 'datetime',
    ];
    
    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
