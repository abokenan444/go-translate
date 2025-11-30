<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GlossaryTermResource\Pages;
use App\Models\GlossaryTerm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GlossaryTermResource extends Resource
{
    protected static ?string $model = GlossaryTerm::class;
    protected static ?string $navigationGroup = 'Cultural Intelligence';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Glossary Terms';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('language')->required()->maxLength(10),
            Forms\Components\TextInput::make('term')->required(),
            Forms\Components\TextInput::make('preferred')->label('Preferred Translation'),
            Forms\Components\Toggle::make('forbidden')->inline(false),
            Forms\Components\TextInput::make('context')
                ->helperText('Short context where this term applies'),
            Forms\Components\KeyValue::make('metadata')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('language')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('term')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('preferred')->label('Preferred')->searchable(),
                Tables\Columns\IconColumn::make('forbidden')->boolean()->label('Forbidden'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGlossaryTerms::route('/'),
            'create' => Pages\CreateGlossaryTerm::route('/create'),
            'edit' => Pages\EditGlossaryTerm::route('/{record}/edit'),
        ];
    }
}
