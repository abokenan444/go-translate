<?php

namespace App\Filament\Admin\Resources\TaskTemplateResource\Pages;

use App\Filament\Admin\Resources\TaskTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskTemplates extends ListRecords
{
    protected static string $resource = TaskTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
