<?php

namespace App\Filament\Resources\GovernmentRegistrationResource\Pages;

use App\Filament\Resources\GovernmentRegistrationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGovernmentRegistration extends CreateRecord
{
    protected static string $resource = GovernmentRegistrationResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
