<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BrandVoiceResource\Pages;
use App\Models\BrandVoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BrandVoiceResource extends Resource
{
    protected static ?string $model = BrandVoice::class;
    protected static ?string $navigationGroup = 'Cultural Intelligence';
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Brand Voices';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('tone'),
            Forms\Components\TextInput::make('formality'),
            Forms\Components\KeyValue::make('rules')->disableAddingRows(false)->disableEditingKeys(false),
            Forms\Components\KeyValue::make('vocabulary_use')->label('Vocabulary: Use'),
            Forms\Components\KeyValue::make('vocabulary_avoid')->label('Vocabulary: Avoid'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('tone')->searchable(),
                Tables\Columns\TextColumn::make('formality'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrandVoices::route('/'),
            'create' => Pages\CreateBrandVoice::route('/create'),
            'edit' => Pages\EditBrandVoice::route('/{record}/edit'),
        ];
    }
}
