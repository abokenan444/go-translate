<?php

namespace App\Filament\Resources\PartnerLeadResource\Pages;

use App\Filament\Resources\PartnerLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerLeads extends ListRecords
{
    protected static string $resource = PartnerLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
