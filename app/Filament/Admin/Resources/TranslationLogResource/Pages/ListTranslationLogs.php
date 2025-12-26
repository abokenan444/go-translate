<?php
namespace App\Filament\Admin\Resources\TranslationLogResource\Pages;

use App\Filament\Admin\Resources\TranslationLogResource;
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
