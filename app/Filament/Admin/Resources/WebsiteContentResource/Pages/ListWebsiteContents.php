<?php

namespace App\Filament\Admin\Resources\WebsiteContentResource\Pages;

use App\Filament\Admin\Resources\WebsiteContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteContents extends ListRecords
{
    protected static string $resource = WebsiteContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
