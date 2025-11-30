<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeatureResource\Pages;
use App\Models\Feature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'Super Admin';
    protected static ?string $navigationLabel = 'Feature Flags';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->maxLength(100)
                ->helperText('Unique feature key (e.g. realtime_meetings)'),
            Forms\Components\Toggle::make('enabled')
                ->default(false)
                ->label('Enabled'),
            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->maxLength(500),
            Forms\Components\KeyValue::make('meta')
                ->keyLabel('Meta Key')
                ->valueLabel('Meta Value')
                ->helperText('Additional configuration data'),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('key')->searchable()->sortable(),
            Tables\Columns\IconColumn::make('enabled')->boolean(),
            Tables\Columns\TextColumn::make('description')->limit(40),
            Tables\Columns\TextColumn::make('updated_at')->dateTime()->since()->sortable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ])
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatures::route('/'),
            'create' => Pages\CreateFeature::route('/create'),
            'edit' => Pages\EditFeature::route('/{record}/edit'),
        ];
    }
}
