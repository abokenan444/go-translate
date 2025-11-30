<?php

namespace App\Filament\Admin\Resources\PageSectionResource\Pages;

use App\Filament\Admin\Resources\PageSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPageSections extends ListRecords
{
    protected static string $resource = PageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
