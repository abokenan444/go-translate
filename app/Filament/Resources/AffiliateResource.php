<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateResource\Pages;
use App\Models\Affiliate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Partners & Companies';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $navigationLabel = 'Affiliates';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Affiliate Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('User'),
                        
                        Forms\Components\TextInput::make('affiliate_code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->label('Affiliate Code')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('commission_rate')
                            ->numeric()
                            ->default(10)
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->label('Commission Rate'),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'bank_transfer' => 'Bank Transfer',
                                'paypal' => 'PayPal',
                                'wise' => 'Wise',
                            ])
                            ->label('Preferred Payment Method'),
                        
                        Forms\Components\Textarea::make('payment_details')
                            ->rows(3)
                            ->maxLength(1000)
                            ->label('Payment Details'),
                    ]),
                
                Forms\Components\Section::make('Earnings')
                    ->schema([
                        Forms\Components\TextInput::make('total_earnings')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->label('Total Earnings'),
                        
                        Forms\Components\TextInput::make('pending_balance')
                            ->numeric()
                            ->prefix('$')
                            ->label('Pending Balance'),
                        
                        Forms\Components\TextInput::make('paid_balance')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->label('Paid Balance'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('User'),
                
                Tables\Columns\TextColumn::make('affiliate_code')
                    ->searchable()
                    ->copyable()
                    ->label('Code')
                    ->fontFamily('mono'),
                
                Tables\Columns\TextColumn::make('commission_rate')
                    ->suffix('%')
                    ->sortable()
                    ->label('Commission'),
                
                Tables\Columns\TextColumn::make('referrals_count')
                    ->counts('referrals')
                    ->label('Referrals')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_earnings')
                    ->money('USD')
                    ->sortable()
                    ->label('Total Earned'),
                
                Tables\Columns\TextColumn::make('pending_balance')
                    ->money('USD')
                    ->sortable()
                    ->color('warning')
                    ->label('Pending'),
                
                Tables\Columns\TextColumn::make('paid_balance')
                    ->money('USD')
                    ->sortable()
                    ->color('success')
                    ->label('Paid'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'suspended',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ]),
                
                Tables\Filters\Filter::make('high_earners')
                    ->query(fn ($query) => $query->where('total_earnings', '>=', 1000))
                    ->label('High Earners ($1000+)'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('activate')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Affiliate $record) => $record->update(['status' => 'active']))
                    ->visible(fn (Affiliate $record) => $record->status !== 'active'),
                Tables\Actions\Action::make('suspend')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Affiliate $record) => $record->update(['status' => 'suspended']))
                    ->visible(fn (Affiliate $record) => $record->status === 'active'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('total_earnings', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Affiliate Overview')
                    ->schema([
                        Components\TextEntry::make('user.name')
                            ->label('User'),
                        Components\TextEntry::make('user.email')
                            ->label('Email')
                            ->copyable(),
                        Components\TextEntry::make('affiliate_code')
                            ->copyable()
                            ->fontFamily('mono'),
                        Components\TextEntry::make('commission_rate')
                            ->suffix('%'),
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'warning',
                                'suspended' => 'danger',
                            }),
                    ])
                    ->columns(2),
                
                Components\Section::make('Earnings Summary')
                    ->schema([
                        Components\TextEntry::make('total_earnings')
                            ->money('USD'),
                        Components\TextEntry::make('pending_balance')
                            ->money('USD'),
                        Components\TextEntry::make('paid_balance')
                            ->money('USD'),
                        Components\TextEntry::make('referrals_count')
                            ->label('Total Referrals'),
                    ])
                    ->columns(4),
                
                Components\Section::make('Payment Information')
                    ->schema([
                        Components\TextEntry::make('payment_method')
                            ->label('Preferred Method'),
                        Components\TextEntry::make('payment_details')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
            'index' => Pages\ListAffiliates::route('/'),
            'create' => Pages\CreateAffiliate::route('/create'),
            'view' => Pages\ViewAffiliate::route('/{record}'),
            'edit' => Pages\EditAffiliate::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}
