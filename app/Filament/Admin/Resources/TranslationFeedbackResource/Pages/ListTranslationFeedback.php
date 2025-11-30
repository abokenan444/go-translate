<?php

namespace App\Filament\Admin\Resources\TranslationFeedbackResource\Pages;

use App\Filament\Admin\Resources\TranslationFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranslationFeedback extends ListRecords
{
    protected static string $resource = TranslationFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
