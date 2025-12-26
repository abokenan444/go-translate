<?php

namespace App\Filament\Admin\Resources\TranslationTypeResource\Pages;

use App\Filament\Admin\Resources\TranslationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTranslationType extends ViewRecord
{
    protected static string $resource = TranslationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
