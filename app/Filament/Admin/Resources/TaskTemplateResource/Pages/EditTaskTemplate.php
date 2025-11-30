<?php

namespace App\Filament\Admin\Resources\TaskTemplateResource\Pages;

use App\Filament\Admin\Resources\TaskTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskTemplate extends EditRecord
{
    protected static string $resource = TaskTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
