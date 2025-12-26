<?php

namespace App\Filament\Admin\Resources\TranslatorPerformanceResource\Pages;

use App\Filament\Admin\Resources\TranslatorPerformanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranslatorPerformance extends EditRecord
{
    protected static string $resource = TranslatorPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
