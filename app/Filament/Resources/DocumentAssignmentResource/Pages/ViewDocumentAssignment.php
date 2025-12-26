<?php

namespace App\Filament\Resources\DocumentAssignmentResource\Pages;

use App\Filament\Resources\DocumentAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDocumentAssignment extends ViewRecord
{
    protected static string $resource = DocumentAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
