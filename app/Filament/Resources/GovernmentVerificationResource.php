<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GovernmentVerificationResource\Pages;
use App\Models\GovernmentVerification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class GovernmentVerificationResource extends Resource
{
    protected static ?string $model = GovernmentVerification::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Government';

    protected static ?string $navigationLabel = 'Verification Requests';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'entity_name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['pending', 'under_review'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? 'danger' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Entity Information')
                    ->schema([
                        Forms\Components\TextInput::make('entity_name')
                            ->label('Entity Name')
                            ->required()
                            ->disabled(),

                        Forms\Components\TextInput::make('entity_name_local')
                            ->label('Local Name')
                            ->disabled(),

                        Forms\Components\Select::make('entity_type')
                            ->label('Entity Type')
                            ->options([
                                'ministry' => '🏛️ Ministry / وزارة',
                                'embassy' => '🏢 Embassy / سفارة',
                                'consulate' => '🏣 Consulate / قنصلية',
                                'municipality' => '🏙️ Municipality / بلدية',
                                'agency' => '🏗️ Agency / هيئة',
                                'department' => '📋 Department / إدارة',
                                'court' => '⚖️ Court / محكمة',
                                'parliament' => '🏛️ Parliament / برلمان',
                                'other' => '📌 Other / أخرى',
                            ])
                            ->disabled(),

                        Forms\Components\TextInput::make('country_code')
                            ->label('Country')
                            ->disabled(),

                        Forms\Components\TextInput::make('official_website')
                            ->label('Official Website')
                            ->url()
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Contact Person')
                    ->schema([
                        Forms\Components\TextInput::make('contact_name')
                            ->label('Name')
                            ->disabled(),

                        Forms\Components\TextInput::make('contact_position')
                            ->label('Position')
                            ->disabled(),

                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email')
                            ->disabled(),

                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Phone')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Review Decision')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => '⏳ Pending',
                                'under_review' => '🔍 Under Review',
                                'info_requested' => '❓ More Info Needed',
                                'approved' => '✅ Approved',
                                'rejected' => '❌ Rejected',
                                'suspended' => '⚠️ Suspended',
                            ])
                            ->required(),

                        Forms\Components\Select::make('priority')
                            ->label('Priority')
                            ->options([
                                'normal' => '🔵 Normal',
                                'high' => '🟠 High',
                                'urgent' => '🔴 Urgent',
                            ])
                            ->default('normal'),

                        Forms\Components\Textarea::make('review_notes')
                            ->label('Internal Notes')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason (Will be sent to user)')
                            ->rows(3)
                            ->visible(fn ($get) => $get('status') === 'rejected')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('info_request_message')
                            ->label('Information Request Message')
                            ->rows(3)
                            ->visible(fn ($get) => $get('status') === 'info_requested')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('entity_name')
                    ->label('Entity')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->entity_type),

                Tables\Columns\TextColumn::make('country_code')
                    ->label('Country')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->copyable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'under_review' => 'info',
                        'info_requested' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'under_review' => 'heroicon-o-magnifying-glass',
                        'info_requested' => 'heroicon-o-question-mark-circle',
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'suspended' => 'heroicon-o-exclamation-triangle',
                        default => 'heroicon-o-minus-circle',
                    }),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        default => 'gray',
                    })
                    ->visible(fn ($record) => $record?->priority !== 'normal'),

                Tables\Columns\TextColumn::make('documents_count')
                    ->label('Docs')
                    ->counts('documents')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at->diffForHumans()),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'under_review' => 'Under Review',
                        'info_requested' => 'Info Requested',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'suspended' => 'Suspended',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'urgent' => 'Urgent',
                        'high' => 'High',
                        'normal' => 'Normal',
                    ]),

                Tables\Filters\SelectFilter::make('entity_type')
                    ->label('Entity Type')
                    ->options([
                        'ministry' => 'Ministry',
                        'embassy' => 'Embassy',
                        'consulate' => 'Consulate',
                        'municipality' => 'Municipality',
                        'agency' => 'Agency',
                        'department' => 'Department',
                        'court' => 'Court',
                        'parliament' => 'Parliament',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => in_array($record->status, ['pending', 'under_review', 'info_requested']))
                        ->requiresConfirmation()
                        ->modalHeading('Approve Government Verification')
                        ->modalDescription('This will grant the user government entity status and badge.')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'approved',
                                'reviewed_by' => auth()->id(),
                                'reviewed_at' => now(),
                            ]);

                            // Update user
                            $record->user->update([
                                'is_government_verified' => true,
                                'government_verified_at' => now(),
                                'government_badge' => 'verified',
                            ]);

                            // Log
                            DB::table('government_audit_logs')->insert([
                                'verification_id' => $record->id,
                                'action' => 'approved',
                                'performed_by' => auth()->id(),
                                'notes' => 'Verification approved',
                                'created_at' => now(),
                            ]);

                            Notification::make()
                                ->success()
                                ->title('Verification Approved')
                                ->send();
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => in_array($record->status, ['pending', 'under_review', 'info_requested']))
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->rows(4),
                        ])
                        ->action(function ($record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'rejection_reason' => $data['rejection_reason'],
                                'reviewed_by' => auth()->id(),
                                'reviewed_at' => now(),
                            ]);

                            DB::table('government_audit_logs')->insert([
                                'verification_id' => $record->id,
                                'action' => 'rejected',
                                'performed_by' => auth()->id(),
                                'notes' => $data['rejection_reason'],
                                'created_at' => now(),
                            ]);

                            Notification::make()
                                ->warning()
                                ->title('Verification Rejected')
                                ->send();
                        }),

                    Tables\Actions\Action::make('request_info')
                        ->label('Request More Info')
                        ->icon('heroicon-o-question-mark-circle')
                        ->color('warning')
                        ->visible(fn ($record) => in_array($record->status, ['pending', 'under_review']))
                        ->form([
                            Forms\Components\Textarea::make('info_request')
                                ->label('What information do you need?')
                                ->required()
                                ->rows(4),
                        ])
                        ->action(function ($record, array $data) {
                            $record->update([
                                'status' => 'info_requested',
                                'info_request_message' => $data['info_request'],
                                'info_requested_at' => now(),
                            ]);

                            DB::table('government_audit_logs')->insert([
                                'verification_id' => $record->id,
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

                    Tables\Actions\Action::make('start_review')
                        ->label('Start Review')
                        ->icon('heroicon-o-magnifying-glass')
                        ->color('info')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'under_review',
                                'reviewed_by' => auth()->id(),
                            ]);

                            DB::table('government_audit_logs')->insert([
                                'verification_id' => $record->id,
                                'action' => 'review_started',
                                'performed_by' => auth()->id(),
                                'created_at' => now(),
                            ]);
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_under_review')
                        ->label('Mark Under Review')
                        ->icon('heroicon-o-magnifying-glass')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'under_review']);
                                }
                            });
                        }),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Entity Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('entity_name')
                            ->label('Entity Name'),
                        Infolists\Components\TextEntry::make('entity_name_local')
                            ->label('Local Name'),
                        Infolists\Components\TextEntry::make('entity_type')
                            ->label('Type')
                            ->badge(),
                        Infolists\Components\TextEntry::make('country_code')
                            ->label('Country'),
                        Infolists\Components\TextEntry::make('official_website')
                            ->label('Website')
                            ->url(fn ($record) => $record->official_website),
                    ])->columns(2),

                Infolists\Components\Section::make('Contact Person')
                    ->schema([
                        Infolists\Components\TextEntry::make('contact_name'),
                        Infolists\Components\TextEntry::make('contact_position'),
                        Infolists\Components\TextEntry::make('contact_email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('contact_phone'),
                    ])->columns(2),

                Infolists\Components\Section::make('Documents')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('documents')
                            ->schema([
                                Infolists\Components\TextEntry::make('document_type')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('original_filename'),
                                Infolists\Components\IconEntry::make('is_verified')
                                    ->boolean(),
                            ])->columns(3),
                    ]),

                Infolists\Components\Section::make('Audit Trail')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('auditLogs')
                            ->schema([
                                Infolists\Components\TextEntry::make('action')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('performer.name')
                                    ->label('By'),
                                Infolists\Components\TextEntry::make('notes'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->dateTime(),
                            ])->columns(4),
                    ]),

                Infolists\Components\Section::make('Legal Disclaimer')
                    ->schema([
                        Infolists\Components\IconEntry::make('legal_accepted')
                            ->label('Accepted')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('legal_accepted_at')
                            ->label('Accepted At')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('legal_ip')
                            ->label('IP Address'),
                    ])->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGovernmentVerifications::route('/'),
            'view' => Pages\ViewGovernmentVerification::route('/{record}'),
            'edit' => Pages\EditGovernmentVerification::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'documents']);
    }
}
