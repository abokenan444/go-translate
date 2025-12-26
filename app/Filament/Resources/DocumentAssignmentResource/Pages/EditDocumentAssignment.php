<?php

namespace App\Filament\Resources\DocumentAssignmentResource\Pages;

use App\Filament\Resources\DocumentAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentAssignment extends EditRecord
{
    protected static string $resource = DocumentAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
