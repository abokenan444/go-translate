<?php

namespace App\Filament\Admin\Resources\CulturalProfileResource\Pages;

use App\Filament\Admin\Resources\CulturalProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCulturalProfile extends EditRecord
{
    protected static string $resource = CulturalProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
