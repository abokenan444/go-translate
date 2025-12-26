<?php

namespace App\Filament\Resources\GovInviteResource\Pages;

use App\Filament\Resources\GovInviteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGovInvite extends ViewRecord
{
    protected static string $resource = GovInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('copy_registration_link')
                ->label('Copy Registration Link')
                ->icon('heroicon-o-clipboard-document')
                ->color('success')
                ->visible(fn (): bool => $this->record->isValid())
                ->url(fn (): string => route('government.register', ['token' => $this->record->token]))
                ->openUrlInNewTab(),
        ];
    }
}
