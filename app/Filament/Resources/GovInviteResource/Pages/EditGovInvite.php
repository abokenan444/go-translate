<?php

namespace App\Filament\Resources\GovInviteResource\Pages;

use App\Filament\Resources\GovInviteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGovInvite extends EditRecord
{
    protected static string $resource = GovInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Parse metadata JSON if provided
        if (!empty($data['metadata']) && is_string($data['metadata'])) {
            try {
                $data['metadata'] = json_decode($data['metadata'], true);
            } catch (\Exception $e) {
                $data['metadata'] = null;
            }
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
