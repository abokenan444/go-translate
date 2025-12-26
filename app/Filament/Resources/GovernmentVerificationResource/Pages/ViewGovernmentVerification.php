<?php

namespace App\Filament\Resources\GovernmentVerificationResource\Pages;

use App\Filament\Resources\GovernmentVerificationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class ViewGovernmentVerification extends ViewRecord
{
    protected static string $resource = GovernmentVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => in_array($this->record->status, ['pending', 'under_review', 'info_requested']))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'status' => 'approved',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ]);

                    $this->record->user->update([
                        'is_government_verified' => true,
                        'government_verified_at' => now(),
                        'government_badge' => 'verified',
                    ]);

                    DB::table('government_audit_logs')->insert([
                        'verification_id' => $this->record->id,
                        'action' => 'approved',
                        'performed_by' => auth()->id(),
                        'created_at' => now(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Verification Approved')
                        ->body('The government entity has been verified.')
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => in_array($this->record->status, ['pending', 'under_review', 'info_requested']))
                ->form([
                    \Filament\Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ]);

                    DB::table('government_audit_logs')->insert([
                        'verification_id' => $this->record->id,
                        'action' => 'rejected',
                        'performed_by' => auth()->id(),
                        'notes' => $data['rejection_reason'],
                        'created_at' => now(),
                    ]);

                    Notification::make()
                        ->warning()
                        ->title('Verification Rejected')
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Actions\Action::make('request_info')
                ->label('Request Info')
                ->icon('heroicon-o-question-mark-circle')
                ->color('warning')
                ->visible(fn () => in_array($this->record->status, ['pending', 'under_review']))
                ->form([
                    \Filament\Forms\Components\Textarea::make('info_request')
                        ->label('What information do you need?')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => 'info_requested',
                        'info_request_message' => $data['info_request'],
                        'info_requested_at' => now(),
                    ]);

                    DB::table('government_audit_logs')->insert([
                        'verification_id' => $this->record->id,
                        'action' => 'info_requested',
                        'performed_by' => auth()->id(),
                        'notes' => $data['info_request'],
                        'created_at' => now(),
                    ]);

                    Notification::make()
                        ->info()
                        ->title('Information Requested')
                        ->send();
                }),

            Actions\Action::make('contact')
                ->label('Contact Entity')
                ->icon('heroicon-o-envelope')
                ->color('gray')
                ->form([
                    \Filament\Forms\Components\Select::make('type')
                        ->label('Communication Type')
                        ->options([
                            'email' => 'Email',
                            'phone' => 'Phone Call',
                            'letter' => 'Official Letter',
                        ])
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('subject')
                        ->label('Subject')
                        ->required(),
                    \Filament\Forms\Components\RichEditor::make('message')
                        ->label('Message')
                        ->required(),
                ])
                ->action(function (array $data) {
                    DB::table('government_communications')->insert([
                        'verification_id' => $this->record->id,
                        'direction' => 'outbound',
                        'type' => $data['type'],
                        'subject' => $data['subject'],
                        'content' => $data['message'],
                        'sent_by' => auth()->id(),
                        'sent_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Communication Logged')
                        ->send();
                }),

            Actions\EditAction::make(),
        ];
    }
}
