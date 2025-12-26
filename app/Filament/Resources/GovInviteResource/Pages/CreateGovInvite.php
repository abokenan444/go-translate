<?php

namespace App\Filament\Resources\GovInviteResource\Pages;

use App\Filament\Resources\GovInviteResource;
use App\Models\GovInvite;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateGovInvite extends CreateRecord
{
    protected static string $resource = GovInviteResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate unique token
        $data['token'] = GovInvite::generateToken();
        
        // Set invited_by to current user
        $data['invited_by'] = auth()->id();
        
        // Parse metadata JSON if provided
        if (!empty($data['metadata'])) {
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
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Invitation created successfully';
    }
    
    protected function afterCreate(): void
    {
        // TODO: Send invitation email
        // Mail::to($this->record->email)->send(new GovInviteMail($this->record));
    }
}
