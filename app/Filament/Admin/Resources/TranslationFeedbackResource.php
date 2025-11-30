<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TranslationFeedbackResource\Pages;
use App\Filament\Admin\Resources\TranslationFeedbackResource\RelationManagers;
use App\Models\TranslationFeedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TranslationFeedbackResource extends Resource
{
    protected static ?string $model = TranslationFeedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('translation_id')
                    ->relationship('translation', 'id')
                    ->required(),
                Forms\Components\Select::make('translation_version_id')
                    ->relationship('translationVersion', 'id'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tag'),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('suggested_text')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('translation.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('translationVersion.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTranslationFeedback::route('/'),
            'create' => Pages\CreateTranslationFeedback::route('/create'),
            'edit' => Pages\EditTranslationFeedback::route('/{record}/edit'),
        ];
    }
}
