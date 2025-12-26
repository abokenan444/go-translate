<?php

namespace App\Filament\Admin\Resources\TranslatorPerformanceResource\Pages;

use App\Filament\Admin\Resources\TranslatorPerformanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTranslatorPerformance extends ViewRecord
{
    protected static string $resource = TranslatorPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
