<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UniversityResource\Pages;
use App\Models\University;
use App\Notifications\UniversityNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'University Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('University Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('official_name')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('country')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('license_number')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('contact_phone')
                            ->tel()
                            ->maxLength(50),
                        
                        Forms\Components\Textarea::make('address')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('max_students')
                            ->numeric()
                            ->default(100)
                            ->required()
                            ->minValue(1),
                        
                        Forms\Components\TextInput::make('discount_rate')
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100),
                        
                        Forms\Components\Toggle::make('api_enabled')
                            ->label('API Access')
                            ->default(true),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'suspended' => 'Suspended',
                                'inactive' => 'Inactive',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Account Owner')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('current_students_count')
                    ->label('Students')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->current_students_count . '/' . $record->max_students),
                
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('api_enabled')
                    ->label('API')
                    ->boolean(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger' => 'suspended',
                        'secondary' => 'inactive',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'inactive' => 'Inactive',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verified'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('verify')
                    ->label('Verify University')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verify University Account')
                    ->modalDescription('This will activate the university account and allow them to add students.')
                    ->action(function (University $record) {
                        $record->update([
                            'is_verified' => true,
                            'verified_at' => now(),
                            'verified_by' => auth()->id(),
                            'status' => 'active',
                        ]);
                        
                        $record->user->notify(new UniversityNotification('application_approved'));
                    })
                    ->successNotificationTitle('University verified successfully')
                    ->visible(fn (University $record) => !$record->is_verified),
                
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject University Application')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (University $record, array $data) {
                        $record->update([
                            'is_verified' => false,
                            'status' => 'inactive',
                            'notes' => ($record->notes ?? '') . "\n\nRejection Reason: " . $data['rejection_reason'],
                        ]);
                        
                        $record->user->notify(new UniversityNotification('application_rejected', [
                            'reason' => $data['rejection_reason']
                        ]));
                    })
                    ->successNotificationTitle('University application rejected')
                    ->visible(fn (University $record) => !$record->is_verified),
                
                Tables\Actions\Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-no-symbol')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Suspend University')
                    ->form([
                        Forms\Components\Textarea::make('suspension_reason')
                            ->label('Suspension Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (University $record, array $data) {
                        $record->update([
                            'status' => 'suspended',
                            'suspended_at' => now(),
                            'suspension_reason' => $data['suspension_reason'],
                        ]);
                    })
                    ->successNotificationTitle('University suspended')
                    ->visible(fn (University $record) => $record->is_verified && $record->status === 'active'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUniversities::route('/'),
            'create' => Pages\CreateUniversity::route('/create'),
            'view' => Pages\ViewUniversity::route('/{record}'),
            'edit' => Pages\EditUniversity::route('/{record}/edit'),
        ];
    }
}
