<?php

namespace App\Filament\Resources\AffiliateResource\Pages;

use App\Filament\Resources\AffiliateResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateAffiliate extends CreateRecord
{
    protected static string $resource = AffiliateResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate unique affiliate code
        do {
            $code = 'AFF' . Str::random(7);
        } while (\App\Models\Affiliate::where('affiliate_code', $code)->exists());
        
        $data['affiliate_code'] = $code;
        $data['total_earnings'] = 0;
        $data['pending_balance'] = 0;
        $data['paid_balance'] = 0;
        
        return $data;
    }
}
