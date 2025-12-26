<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebhookEndpointResource\Pages;
use App\Models\WebhookEndpoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebhookEndpointResource extends Resource
{
    protected static ?string $model = WebhookEndpoint::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Affiliates';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('affiliate_id')
                    ->label('Affiliate (optional - for global webhooks leave empty)')
                    ->relationship('affiliate', 'name')
                    ->searchable()
                    ->nullable(),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->required()
                    ->maxLength(255),
                Forms\Components\CheckboxList::make('events')
                    ->label('Events to Subscribe')
                    ->options([
                        'commission.created' => 'Commission Created',
                        'commission.paid' => 'Commission Paid',
                        'payout.initiated' => 'Payout Initiated',
                        'payout.paid' => 'Payout Paid',
                    ])
                    ->nullable()
                    ->helperText('Leave empty to receive all events'),
                Forms\Components\TextInput::make('secret')
                    ->label('Signing Secret')
                    ->maxLength(255)
                    ->helperText('Used to sign webhook payloads for verification'),
                Forms\Components\Toggle::make('active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('affiliate.name')->label('Affiliate')->default('Global'),
                Tables\Columns\TextColumn::make('url')->limit(40),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('logs_count')->counts('logs')->label('Deliveries'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhookEndpoints::route('/'),
            'create' => Pages\CreateWebhookEndpoint::route('/create'),
            'edit' => Pages\EditWebhookEndpoint::route('/{record}/edit'),
        ];
    }
}
