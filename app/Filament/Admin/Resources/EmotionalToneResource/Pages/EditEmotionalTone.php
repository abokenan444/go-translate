<?php

namespace App\Filament\Admin\Resources\EmotionalToneResource\Pages;

use App\Filament\Admin\Resources\EmotionalToneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmotionalTone extends EditRecord
{
    protected static string $resource = EmotionalToneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
