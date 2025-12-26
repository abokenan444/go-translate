<?php

namespace App\Filament\Resources\GovernmentVerificationResource\Pages;

use App\Filament\Resources\GovernmentVerificationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGovernmentVerifications extends ListRecords
{
    protected static string $resource = GovernmentVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge($this->getModel()::count()),

            'pending' => Tab::make('Pending')
                ->badge($this->getModel()::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),

            'under_review' => Tab::make('Under Review')
                ->badge($this->getModel()::where('status', 'under_review')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'under_review')),

            'info_requested' => Tab::make('Info Requested')
                ->badge($this->getModel()::where('status', 'info_requested')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'info_requested')),

            'approved' => Tab::make('Approved')
                ->badge($this->getModel()::where('status', 'approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Rejected')
                ->badge($this->getModel()::where('status', 'rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),
        ];
    }
}
