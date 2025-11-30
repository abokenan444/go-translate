<?php

namespace App\Filament\Resources\IndustryBehaviorResource\Pages;

use App\Filament\Resources\IndustryBehaviorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndustryBehavior extends EditRecord
{
    protected static string $resource = IndustryBehaviorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
