<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CulturalMemoryResource\Pages;
use App\Models\CulturalMemory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CulturalMemoryResource extends Resource
{
    protected static ?string $model = CulturalMemory::class;
    protected static ?string $navigationGroup = 'Cultural Intelligence';
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Memories';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('target_language')->disabled(),
            Forms\Components\Textarea::make('source_text')->disabled()->rows(6),
            Forms\Components\Textarea::make('translated_text')->disabled()->rows(6),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user_id')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('source_language')->label('Src')->searchable(),
                Tables\Columns\TextColumn::make('target_language')->label('Tgt')->searchable(),
                Tables\Columns\TextColumn::make('target_culture')->label('Culture')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCulturalMemories::route('/'),
            'view' => Pages\ViewCulturalMemory::route('/{record}'),
        ];
    }
}
