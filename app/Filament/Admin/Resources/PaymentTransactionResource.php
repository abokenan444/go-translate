<?php

namespace App\Filament\Admin\Resources;

use App\Models\PaymentTransaction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;

class PaymentTransactionResource extends Resource
{
    protected static ?string $model = PaymentTransaction::class;

    protected static ?string $navigationGroup = 'Billing';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('user.email')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('provider')->label('Provider')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')->label('Type')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('Amount')->sortable()->money('usd'),
                Tables\Columns\TextColumn::make('currency')->label('Currency')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()->colors([
                    'success' => 'succeeded',
                    'danger' => 'failed',
                    'warning' => 'pending',
                ])->sortable(),
                Tables\Columns\TextColumn::make('provider_id')->label('Stripe ID')->copyable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created')->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'succeeded' => 'Succeeded',
                    'failed' => 'Failed',
                    'pending' => 'Pending',
                ]),
                Tables\Filters\SelectFilter::make('provider')->options([
                    'stripe' => 'Stripe',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            'view' => ViewTransaction::route('/{record}'),
        ];
    }
}

class ListTransactions extends ListRecords
{
    protected static string $resource = PaymentTransactionResource::class;
}

class ViewTransaction extends ViewRecord
{
    protected static string $resource = PaymentTransactionResource::class;
}
