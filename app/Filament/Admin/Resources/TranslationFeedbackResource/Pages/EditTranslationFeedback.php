<?php

namespace App\Filament\Admin\Resources\TranslationFeedbackResource\Pages;

use App\Filament\Admin\Resources\TranslationFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranslationFeedback extends EditRecord
{
    protected static string $resource = TranslationFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
