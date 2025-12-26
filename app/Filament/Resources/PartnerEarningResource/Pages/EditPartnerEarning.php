<?php

namespace App\Filament\Resources\PartnerEarningResource\Pages;

use App\Filament\Resources\PartnerEarningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnerEarning extends EditRecord
{
    protected static string $resource = PartnerEarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
