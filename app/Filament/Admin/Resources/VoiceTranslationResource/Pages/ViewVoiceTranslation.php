<?php

namespace App\Filament\Admin\Resources\VoiceTranslationResource\Pages;

use App\Filament\Admin\Resources\VoiceTranslationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewVoiceTranslation extends ViewRecord
{
    protected static string $resource = VoiceTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
