<?php

namespace App\Filament\Admin\Resources\IndustryTemplateResource\Pages;

use App\Filament\Admin\Resources\IndustryTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndustryTemplates extends ListRecords
{
    protected static string $resource = IndustryTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
