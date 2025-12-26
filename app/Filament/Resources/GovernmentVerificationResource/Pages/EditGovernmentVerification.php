<?php

namespace App\Filament\Resources\GovernmentVerificationResource\Pages;

use App\Filament\Resources\GovernmentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditGovernmentVerification extends EditRecord
{
    protected static string $resource = GovernmentVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Log the update
        DB::table('government_audit_logs')->insert([
            'verification_id' => $this->record->id,
            'action' => 'updated',
            'performed_by' => auth()->id(),
            'notes' => 'Record updated via admin panel',
            'created_at' => now(),
        ]);

        // If status changed to approved, update user
        if ($this->record->status === 'approved' && !$this->record->user->is_government_verified) {
            $this->record->user->update([
                'is_government_verified' => true,
                'government_verified_at' => now(),
                'government_badge' => 'verified',
            ]);
        }

        // If status changed to rejected/suspended, remove government badge
        if (in_array($this->record->status, ['rejected', 'suspended'])) {
            $this->record->user->update([
                'is_government_verified' => false,
                'government_badge' => null,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
