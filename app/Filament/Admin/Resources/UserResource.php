<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Company;
use App\Notifications\GovernmentAccountVerified;
use App\Notifications\GovernmentAccountRevoked;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'User Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->required()
                    ->options([
                        'admin' => 'Admin',
                        'company_admin' => 'Company Admin',
                        'user' => 'User',
                    ])
                    ->default('user'),
                Forms\Components\Select::make('account_type')
                    ->label('Account Type')
                    ->options([
                        'customer' => 'Customer',
                        'affiliate' => 'Affiliate',
                        'government' => 'Government',
                        'partner' => 'Partner',
                        'translator' => 'Translator',
                    ])
                    ->default('customer'),
                Forms\Components\Toggle::make('is_government_verified')
                    ->label('Government Verified')
                    ->default(false)
                    ->visible(fn ($get) => $get('account_type') === 'government'),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Select::make('preferred_language')
                    ->label('Preferred Language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                    ])
                    ->default('en'),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'company_admin' => 'warning',
                        'user' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('account_type')
                    ->label('Account Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'government' => 'success',
                        'partner' => 'primary',
                        'translator' => 'warning',
                        'affiliate' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\IconColumn::make('is_government_verified')
                    ->label('Gov. Verified')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->email_verified_at !== null),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'company_admin' => 'Company Admin',
                        'user' => 'User',
                    ]),
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('verify_government')
                    ->label('Verify Government')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verify Government Account')
                    ->modalDescription('This will grant government access privileges. Ensure proper documentation has been reviewed.')
                    ->action(fn (User $record) => $record->update([
                        'is_government_verified' => true,
                    ]) && $record->notify(new GovernmentAccountVerified()))
                    ->successNotificationTitle('Government account verified')
                    ->visible(fn (User $record) => $record->account_type === 'government' && !$record->is_government_verified),
                
                Tables\Actions\Action::make('revoke_government')
                    ->label('Revoke Government')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Revoke Government Verification')
                    ->action(fn (User $record) => $record->update([
                        'is_government_verified' => false,
                    ]) && $record->notify(new GovernmentAccountRevoked()))
                    ->successNotificationTitle('Government verification revoked')
                    ->visible(fn (User $record) => $record->account_type === 'government' && $record->is_government_verified),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
