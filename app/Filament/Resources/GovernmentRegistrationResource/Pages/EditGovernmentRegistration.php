<?php

namespace App\Filament\Resources\GovernmentRegistrationResource\Pages;

use App\Filament\Resources\GovernmentRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGovernmentRegistration extends EditRecord
{
    protected static string $resource = GovernmentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
