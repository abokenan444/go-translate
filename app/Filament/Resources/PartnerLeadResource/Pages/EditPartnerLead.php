<?php

namespace App\Filament\Resources\PartnerLeadResource\Pages;

use App\Filament\Resources\PartnerLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnerLead extends EditRecord
{
    protected static string $resource = PartnerLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
