<?php

namespace App\Filament\Resources\GovernmentRegistrationResource\Pages;

use App\Filament\Resources\GovernmentRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ListGovernmentRegistrations extends ListRecords
{
    protected static string $resource = GovernmentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export Report')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    // Export logic here
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GovernmentRegistrationResource\Widgets\RegistrationStatsWidget::class,
        ];
    }
}
