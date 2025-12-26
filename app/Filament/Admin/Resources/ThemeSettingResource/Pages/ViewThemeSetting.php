<?php

namespace App\Filament\Admin\Resources\ThemeSettingResource\Pages;

use App\Filament\Admin\Resources\ThemeSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewThemeSetting extends ViewRecord
{
    protected static string $resource = ThemeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
