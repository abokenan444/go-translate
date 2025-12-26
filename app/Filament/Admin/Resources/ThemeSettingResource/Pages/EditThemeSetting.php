<?php

namespace App\Filament\Admin\Resources\ThemeSettingResource\Pages;

use App\Filament\Admin\Resources\ThemeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThemeSetting extends EditRecord
{
    protected static string $resource = ThemeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
