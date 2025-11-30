<?php

namespace App\Filament\Admin\Resources\EmotionalToneResource\Pages;

use App\Filament\Admin\Resources\EmotionalToneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmotionalTones extends ListRecords
{
    protected static string $resource = EmotionalToneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
