<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PartnerApplicationResource\Pages;
use App\Models\PartnerApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class PartnerApplicationResource extends Resource
{
    protected static ?string $model = PartnerApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Partner Applications';
    protected static ?string $pluralLabel = 'Partner Applications';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() > 0 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Company Information')->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(2),
                Forms\Components\Select::make('company_size')
                    ->options([
                        '1-10' => '1-10 employees',
                        '11-50' => '11-50 employees',
                        '51-200' => '51-200 employees',
                        '201-500' => '201-500 employees',
                        '500+' => '500+ employees',
                    ])
                    ->required(),
            ])->columns(2),

            Forms\Components\Section::make('Contact Information')->schema([
                Forms\Components\TextInput::make('contact_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('job_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(50),
            ])->columns(2),

            Forms\Components\Section::make('Partnership Details')->schema([
                Forms\Components\Select::make('partnership_type')
                    ->options([
                        'reseller' => 'Reseller',
                        'referral' => 'Referral',
                        'integration' => 'Integration',
                        'white_label' => 'White Label',
                    ])
                    ->required(),
                Forms\Components\Select::make('monthly_volume')
                    ->options([
                        '0-1000' => '0-1,000 words',
                        '1000-10000' => '1,000-10,000 words',
                        '10000-50000' => '10,000-50,000 words',
                        '50000+' => '50,000+ words',
                    ]),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Application Status')->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'contacted' => 'Contacted',
                    ])
                    ->required()
                    ->default('pending'),
            ]),

            Forms\Components\Section::make('Technical Information')->schema([
                Forms\Components\TextInput::make('ip_address')
                    ->disabled(),
                Forms\Components\TextInput::make('recaptcha_score')
                    ->numeric()
                    ->disabled(),
                Forms\Components\Textarea::make('user_agent')
                    ->disabled()
                    ->rows(2),
            ])->columns(2)->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Contact')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('partnership_type')
                    ->badge()
                    ->colors([
                        'primary' => 'reseller',
                        'success' => 'referral',
                        'info' => 'integration',
                        'warning' => 'white_label',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'info' => 'contacted',
                    ]),
                Tables\Columns\TextColumn::make('recaptcha_score')
                    ->label('Score')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'contacted' => 'Contacted',
                    ]),
                Tables\Filters\SelectFilter::make('partnership_type')
                    ->options([
                        'reseller' => 'Reseller',
                        'referral' => 'Referral',
                        'integration' => 'Integration',
                        'white_label' => 'White Label',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (PartnerApplication $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->success()
                            ->title('Application Approved')
                            ->body('The partner application has been approved.')
                            ->send();
                    })
                    ->visible(fn (PartnerApplication $record) => $record->status === 'pending'),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (PartnerApplication $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->danger()
                            ->title('Application Rejected')
                            ->body('The partner application has been rejected.')
                            ->send();
                    })
                    ->visible(fn (PartnerApplication $record) => $record->status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['status' => 'approved']);
                            Notification::make()
                                ->success()
                                ->title('Applications Approved')
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerApplications::route('/'),
            'create' => Pages\CreatePartnerApplication::route('/create'),
            'edit' => Pages\EditPartnerApplication::route('/{record}/edit'),
            'view' => Pages\ViewPartnerApplication::route('/{record}'),
        ];
    }
}
