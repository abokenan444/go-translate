<?php

namespace App\Filament\Admin\Resources\IndustryTemplateResource\Pages;

use App\Filament\Admin\Resources\IndustryTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndustryTemplate extends EditRecord
{
    protected static string $resource = IndustryTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
