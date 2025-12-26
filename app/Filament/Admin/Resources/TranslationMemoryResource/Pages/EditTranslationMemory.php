<?php
namespace App\Filament\Admin\Resources\TranslationMemoryResource\Pages;
use App\Filament\Admin\Resources\TranslationMemoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTranslationMemory extends EditRecord
{
    protected static string $resource = TranslationMemoryResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
