<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EmotionalToneResource\Pages;
use App\Filament\Admin\Resources\EmotionalToneResource\RelationManagers;
use App\Models\EmotionalTone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmotionalToneResource extends Resource
{
    protected static ?string $model = EmotionalTone::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("key")->required(),
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\Textarea::make("description"),
                Forms\Components\KeyValue::make("parameters_json"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("key")->searchable(),
                Tables\Columns\TextColumn::make("name")->searchable(),
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
            'index' => Pages\ListEmotionalTones::route('/'),
            'create' => Pages\CreateEmotionalTone::route('/create'),
            'edit' => Pages\EditEmotionalTone::route('/{record}/edit'),
        ];
    }
}
