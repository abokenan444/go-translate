<?php

namespace App\Filament\Resources\DocumentAssignmentResource\Pages;

use App\Filament\Resources\DocumentAssignmentResource;
use Filament\Resources\Pages\ListRecords;

class ListDocumentAssignments extends ListRecords
{
    protected static string $resource = DocumentAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - assignments are auto-generated
        ];
    }
}
