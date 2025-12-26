<?php

namespace App\Filament\Admin\Resources\WebsiteContentResource\Pages;

use App\Filament\Admin\Resources\WebsiteContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteContent extends EditRecord
{
    protected static string $resource = WebsiteContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
