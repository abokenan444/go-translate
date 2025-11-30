<?php

namespace App\Filament\Resources\IndustryBehaviorResource\Pages;

use App\Filament\Resources\IndustryBehaviorResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListIndustryBehaviors extends ListRecords
{
    protected static string $resource = IndustryBehaviorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
