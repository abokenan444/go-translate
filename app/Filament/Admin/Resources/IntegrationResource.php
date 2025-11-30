<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IntegrationResource\Pages;
use App\Filament\Admin\Resources\IntegrationResource\RelationManagers;
use App\Models\Integration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IntegrationResource extends Resource
{
    protected static ?string $model = Integration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make("user_id")->relationship("user", "name")->required(),
                Forms\Components\TextInput::make("platform")->required(),
                Forms\Components\TextInput::make("site_url"),
                Forms\Components\Textarea::make("credentials"),
                Forms\Components\Select::make("status")->options(["active" => "Active", "inactive" => "Inactive", "error" => "Error"]),
                Forms\Components\KeyValue::make("metadata"),
                Forms\Components\DateTimePicker::make("last_sync_at"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("user.name")->searchable(),
                Tables\Columns\TextColumn::make("platform")->searchable(),
                Tables\Columns\TextColumn::make("site_url"),
                Tables\Columns\TextColumn::make("status"),
                Tables\Columns\TextColumn::make("last_sync_at")->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListIntegrations::route('/'),
            'create' => Pages\CreateIntegration::route('/create'),
            'edit' => Pages\EditIntegration::route('/{record}/edit'),
        ];
    }
}
