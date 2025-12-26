<?php

namespace App\Filament\Admin\Resources\SystemHealthResource\Pages;

use App\Filament\Admin\Resources\SystemHealthResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemHealth extends EditRecord
{
    protected static string $resource = SystemHealthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
