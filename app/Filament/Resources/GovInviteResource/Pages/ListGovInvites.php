<?php

namespace App\Filament\Resources\GovInviteResource\Pages;

use App\Filament\Resources\GovInviteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGovInvites extends ListRecords
{
    protected static string $resource = GovInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
