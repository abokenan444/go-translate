<?php

namespace App\Filament\Admin\Resources\VoiceTranslationResource\Pages;

use App\Filament\Admin\Resources\VoiceTranslationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListVoiceTranslations extends ListRecords
{
    protected static string $resource = VoiceTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
