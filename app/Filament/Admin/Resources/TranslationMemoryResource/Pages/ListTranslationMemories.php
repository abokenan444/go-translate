<?php
namespace App\Filament\Admin\Resources\TranslationMemoryResource\Pages;
use App\Filament\Admin\Resources\TranslationMemoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListTranslationMemories extends ListRecords
{
    protected static string $resource = TranslationMemoryResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
