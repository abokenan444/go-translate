<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerUsageStat extends Model
{
    protected $fillable = [
        'partner_id', 'date', 'api_calls', 'translations_count',
        'characters_translated', 'revenue_generated', 'commission_earned',
    ];

    protected $casts = [
        'date' => 'date',
        'revenue_generated' => 'decimal:2',
        'commission_earned' => 'decimal:2',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public static function recordUsage(int $partnerId, array $data)
    {
        $today = now()->toDateString();
        
        $stat = self::firstOrNew([
            'partner_id' => $partnerId,
            'date' => $today,
        ]);

        $stat->api_calls += $data['api_calls'] ?? 0;
        $stat->translations_count += $data['translations'] ?? 0;
        $stat->characters_translated += $data['characters'] ?? 0;
        $stat->revenue_generated += $data['revenue'] ?? 0;
        $stat->commission_earned += $data['commission'] ?? 0;
        
        $stat->save();
    }
}
