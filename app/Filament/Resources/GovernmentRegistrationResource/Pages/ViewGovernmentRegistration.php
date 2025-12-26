<?php

namespace App\Filament\Resources\GovernmentRegistrationResource\Pages;

use App\Filament\Resources\GovernmentRegistrationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Storage;

class ViewGovernmentRegistration extends ViewRecord
{
    protected static string $resource = GovernmentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve Registration')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->isPending() || $record->isUnderReview())
                ->form([
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('Approval Notes')
                        ->maxLength(500),
                ])
                ->action(function ($record, array $data) {
                    $record->approve(auth()->user(), $data['notes'] ?? null);
                    
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Registration Approved')
                        ->send();
                }),
            
            Actions\Action::make('reject')
                ->label('Reject Registration')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->isPending() || $record->isUnderReview())
                ->form([
                    \Filament\Forms\Components\Textarea::make('reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->maxLength(1000),
                ])
                ->action(function ($record, array $data) {
                    $record->reject(auth()->user(), $data['reason']);
                    
                    \Filament\Notifications\Notification::make()
                        ->danger()
                        ->title('Registration Rejected')
                        ->send();
                }),
            
            Actions\Action::make('requestInfo')
                ->label('Request More Info')
                ->icon('heroicon-o-information-circle')
                ->color('warning')
                ->visible(fn ($record) => $record->isPending() || $record->isUnderReview())
                ->form([
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('What information is needed?')
                        ->required()
                        ->maxLength(1000),
                ])
                ->action(function ($record, array $data) {
                    $record->requestMoreInfo(auth()->user(), $data['notes']);
                    
                    \Filament\Notifications\Notification::make()
                        ->warning()
                        ->title('More Information Requested')
                        ->send();
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Registration Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($record) => $record->getStatusBadgeColor())
                            ->formatStateUsing(fn ($record) => $record->getStatusLabel()),
                        Infolists\Components\TextEntry::make('is_verified')
                            ->label('Verified')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('verified_at')
                            ->dateTime()
                            ->visible(fn ($record) => $record->is_verified),
                    ])->columns(4),

                Infolists\Components\Section::make('Applicant Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone')
                            ->visible(fn ($state) => filled($state)),
                        Infolists\Components\TextEntry::make('ip_address')
                            ->label('Registration IP'),
                    ])->columns(2),

                Infolists\Components\Section::make('Government Entity Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('entity_name')
                            ->label('Entity Name'),
                        Infolists\Components\TextEntry::make('entity_type')
                            ->label('Entity Type')
                            ->formatStateUsing(fn ($state, $record) => 
                                $record->entity_type ? \App\Models\GovernmentRegistration::ENTITY_TYPES[$record->entity_type] : '-'
                            ),
                        Infolists\Components\TextEntry::make('country')
                            ->badge(),
                        Infolists\Components\TextEntry::make('job_title'),
                        Infolists\Components\TextEntry::make('department')
                            ->visible(fn ($state) => filled($state)),
                        Infolists\Components\TextEntry::make('official_website_url')
                            ->label('Official Website')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->visible(fn ($state) => filled($state)),
                    ])->columns(2),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('additional_info')
                            ->columnSpanFull()
                            ->visible(fn ($state) => filled($state)),
                    ])
                    ->visible(fn ($record) => filled($record->additional_info))
                    ->collapsible(),

                Infolists\Components\Section::make('Uploaded Documents')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('documents')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('path')
                                    ->label('Document')
                                    ->formatStateUsing(function ($state) {
                                        $filename = basename($state);
                                        $url = Storage::url($state);
                                        return "<a href='{$url}' target='_blank' class='text-primary-600 hover:underline'>{$filename}</a>";
                                    })
                                    ->html(),
                            ])
                            ->columns(1),
                    ])
                    ->visible(fn ($record) => !empty($record->documents)),

                Infolists\Components\Section::make('Review Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('reviewer.name')
                            ->label('Reviewed By')
                            ->visible(fn ($record) => $record->reviewed_by),
                        Infolists\Components\TextEntry::make('reviewed_at')
                            ->dateTime()
                            ->visible(fn ($record) => $record->reviewed_at),
                        Infolists\Components\TextEntry::make('review_notes')
                            ->columnSpanFull()
                            ->visible(fn ($state) => filled($state)),
                        Infolists\Components\TextEntry::make('rejection_reason')
                            ->columnSpanFull()
                            ->color('danger')
                            ->visible(fn ($state) => filled($state)),
                    ])
                    ->visible(fn ($record) => $record->reviewed_by)
                    ->collapsible(),

                Infolists\Components\Section::make('Audit Trail')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('audit_log')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('action')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('details'),
                                Infolists\Components\TextEntry::make('user_name')
                                    ->label('By'),
                                Infolists\Components\TextEntry::make('timestamp')
                                    ->dateTime(),
                            ])
                            ->columns(4),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
