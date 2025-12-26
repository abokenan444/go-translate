<?php
namespace App\Filament\Admin\Resources\TranslationMemoryResource\Pages;
use App\Filament\Admin\Resources\TranslationMemoryResource;
use Filament\Resources\Pages\CreateRecord;
class CreateTranslationMemory extends CreateRecord
{
    protected static string $resource = TranslationMemoryResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
