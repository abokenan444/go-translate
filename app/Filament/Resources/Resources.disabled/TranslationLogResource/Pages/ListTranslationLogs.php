<?php
namespace App\Filament\Resources\TranslationLogResource\Pages;

use App\Filament\Resources\TranslationLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranslationLogs extends ListRecords
{
    protected static string $resource = TranslationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
