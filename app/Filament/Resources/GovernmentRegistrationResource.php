<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GovernmentRegistrationResource\Pages;
use App\Models\GovernmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class GovernmentRegistrationResource extends Resource
{
    protected static ?string $model = GovernmentRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    
    protected static ?string $navigationLabel = 'Government Registrations';
    
    protected static ?string $navigationGroup = 'Security & Verification';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Registration Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending_verification' => 'قيد الانتظار / Pending',
                                'under_review' => 'قيد المراجعة / Under Review',
                                'approved' => 'موافق / Approved',
                                'rejected' => 'مرفوض / Rejected',
                                'more_info_required' => 'معلومات إضافية / More Info',
                                'suspended' => 'معلق / Suspended',
                            ])
                            ->required()
                            ->reactive(),
                    ])->columns(3),

                Forms\Components\Section::make('Government Entity Details')
                    ->schema([
                        Forms\Components\TextInput::make('entity_name')
                            ->label('Entity Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('entity_type')
                            ->label('Entity Type')
                            ->options(GovernmentRegistration::ENTITY_TYPES)
                            ->required(),
                        Forms\Components\TextInput::make('country')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('country_code')
                            ->label('Country Code')
                            ->maxLength(2)
                            ->required(),
                        Forms\Components\TextInput::make('job_title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('department')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('official_website_url')
                            ->label('Official Website')
                            ->url()
                            ->maxLength(500),
                    ])->columns(2),

                Forms\Components\Section::make('Verification & Review')
                    ->schema([
                        Forms\Components\Textarea::make('review_notes')
                            ->label('Review Notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->maxLength(65535)
                            ->visible(fn ($get) => $get('status') === 'rejected')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_verified')
                            ->label('Verified')
                            ->disabled(),
                        Forms\Components\DatePicker::make('verification_expiry_date')
                            ->label('Verification Expiry'),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('additional_info')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->collapsible(),

                Forms\Components\Section::make('Audit Trail')
                    ->schema([
                        Forms\Components\KeyValue::make('audit_log')
                            ->label('Audit Log')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('entity_name')
                    ->label('Entity')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('entity_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => GovernmentRegistration::ENTITY_TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_verification' => 'warning',
                        'under_review' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'more_info_required' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (GovernmentRegistration $record) => $record->getStatusLabel()),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending_verification' => 'Pending',
                        'under_review' => 'Under Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'more_info_required' => 'More Info Required',
                        'suspended' => 'Suspended',
                    ]),
                Tables\Filters\SelectFilter::make('entity_type')
                    ->options(GovernmentRegistration::ENTITY_TYPES),
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verified')
                    ->placeholder('All')
                    ->trueLabel('Verified Only')
                    ->falseLabel('Not Verified'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (GovernmentRegistration $record) => $record->isPending())
                    ->url(fn (GovernmentRegistration $record) => route('filament.admin.resources.government-registrations.review', $record)),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Approval Notes')
                            ->placeholder('Enter any notes for this approval...')
                            ->maxLength(500),
                    ])
                    ->action(function (GovernmentRegistration $record, array $data) {
                        $record->approve(auth()->user(), $data['notes'] ?? null);
                        
                        Notification::make()
                            ->success()
                            ->title('Registration Approved')
                            ->body('Government registration has been approved successfully.')
                            ->send();
                    })
                    ->visible(fn (GovernmentRegistration $record) => 
                        $record->isUnderReview() || $record->isPending()
                    ),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->placeholder('Explain why this registration is being rejected...')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->action(function (GovernmentRegistration $record, array $data) {
                        $record->reject(auth()->user(), $data['reason']);
                        
                        Notification::make()
                            ->danger()
                            ->title('Registration Rejected')
                            ->body('Government registration has been rejected.')
                            ->send();
                    })
                    ->visible(fn (GovernmentRegistration $record) => 
                        $record->isUnderReview() || $record->isPending()
                    ),
                Tables\Actions\Action::make('requestMoreInfo')
                    ->label('Request More Info')
                    ->icon('heroicon-o-information-circle')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('What additional information is needed?')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->action(function (GovernmentRegistration $record, array $data) {
                        $record->requestMoreInfo(auth()->user(), $data['notes']);
                        
                        Notification::make()
                            ->warning()
                            ->title('More Information Requested')
                            ->body('The applicant will be notified to provide additional information.')
                            ->send();
                    })
                    ->visible(fn (GovernmentRegistration $record) => 
                        $record->isUnderReview() || $record->isPending()
                    ),
                Tables\Actions\Action::make('viewDocuments')
                    ->label('View Documents')
                    ->icon('heroicon-o-document-text')
                    ->modalContent(fn (GovernmentRegistration $record) => view(
                        'filament.modals.government-documents',
                        ['registration' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListGovernmentRegistrations::route('/'),
            'view' => Pages\ViewGovernmentRegistration::route('/{record}'),
            'review' => Pages\ReviewGovernmentRegistration::route('/{record}/review'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending_verification')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::where('status', 'pending_verification')->count();
        return $count > 0 ? 'warning' : 'success';
    }
}
