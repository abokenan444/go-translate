<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerEarningResource\Pages;
use App\Models\PartnerEarning;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerEarningResource extends Resource
{
    protected static ?string $model = PartnerEarning::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationGroup = 'Partner Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('partner_profile_id')
                    ->relationship('partnerProfile', 'id')
                    ->required()
                    ->searchable(),
                    
                Forms\Components\TextInput::make('document_id')
                    ->label('Document UUID')
                    ->required()
                    ->uuid(),
                    
                Forms\Components\TextInput::make('currency')
                    ->default('EUR')
                    ->maxLength(3)
                    ->required(),
                    
                Forms\Components\TextInput::make('amount_cents')
                    ->label('Amount (cents)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->helperText('Amount in cents (e.g., 1000 = €10.00)'),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                        'held' => 'Held (Dispute)',
                    ])
                    ->default('pending')
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('approved_at')
                    ->label('Approved At'),
                    
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Paid At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partnerProfile.user.name')
                    ->label('Partner')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('document_id')
                    ->label('Document')
                    ->searchable()
                    ->copyable()
                    ->limit(8),
                    
                Tables\Columns\TextColumn::make('amount_cents')
                    ->label('Amount')
                    ->money(fn ($record) => strtolower($record->currency ?? 'eur'), divideBy: 100)
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'paid' => 'info',
                        'held' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                        'held' => 'Held',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PartnerEarning $record) => $record->status === 'pending')
                    ->action(function (PartnerEarning $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ]);
                    }),
                    
                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn (PartnerEarning $record) => $record->status === 'approved')
                    ->action(function (PartnerEarning $record) {
                        $record->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update([
                                'status' => 'approved',
                                'approved_at' => now(),
                            ]);
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerEarnings::route('/'),
            'create' => Pages\CreatePartnerEarning::route('/create'),
            'edit' => Pages\EditPartnerEarning::route('/{record}/edit'),
        ];
    }
}
