<?php

namespace App\Filament\Admin\Resources\TranslationCacheResource\Pages;

use App\Filament\Admin\Resources\TranslationCacheResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranslationCaches extends ListRecords
{
    protected static string $resource = TranslationCacheResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
