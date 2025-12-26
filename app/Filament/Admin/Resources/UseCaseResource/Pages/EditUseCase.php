<?php

namespace App\Filament\Admin\Resources\UseCaseResource\Pages;

use App\Filament\Admin\Resources\UseCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUseCase extends EditRecord
{
    protected static string $resource = UseCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
