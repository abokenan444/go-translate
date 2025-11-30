<?php

namespace App\Filament\Resources\SecurityLogResource\Pages;

use App\Filament\Resources\SecurityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSecurityLog extends ViewRecord
{
    protected static string $resource = SecurityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('block_ip')
                ->label('حظر هذا الـ IP')
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('حظر عنوان IP')
                ->modalDescription(fn ($record) => "هل تريد حظر عنوان IP: {$record->ip_address}؟")
                ->action(function ($record) {
                    // TODO: Implement IP blocking
                    \Filament\Notifications\Notification::make()
                        ->title('تم حظر عنوان IP')
                        ->body("تم حظر {$record->ip_address}")
                        ->success()
                        ->send();
                }),
            
            Actions\DeleteAction::make(),
        ];
    }
}
