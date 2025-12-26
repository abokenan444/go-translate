<?php

namespace App\Filament\Resources\PartnerEarningResource\Pages;

use App\Filament\Resources\PartnerEarningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerEarnings extends ListRecords
{
    protected static string $resource = PartnerEarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
