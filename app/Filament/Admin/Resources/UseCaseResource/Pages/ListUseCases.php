<?php

namespace App\Filament\Admin\Resources\UseCaseResource\Pages;

use App\Filament\Admin\Resources\UseCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUseCases extends ListRecords
{
    protected static string $resource = UseCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
