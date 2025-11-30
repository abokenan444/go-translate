<?php

namespace App\Filament\Resources\CulturalProfileResource\Pages;

use App\Filament\Resources\CulturalProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCulturalProfiles extends ListRecords
{
    protected static string $resource = CulturalProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
