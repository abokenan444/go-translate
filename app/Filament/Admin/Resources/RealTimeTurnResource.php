<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RealTimeTurnResource\Pages;
use App\Models\RealTimeTurn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RealTimeTurnResource extends Resource
{
    protected static ?string $model = RealTimeTurn::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'Real-Time & AI';
    protected static ?string $navigationLabel = 'Real-Time Turns';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('session_id')->disabled(),
            Forms\Components\Textarea::make('source_text')->rows(3),
            Forms\Components\Textarea::make('translated_text')->rows(3),
            Forms\Components\TextInput::make('direction'),
            Forms\Components\TextInput::make('latency_ms'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('session.public_id')->label('Session')->limit(12),
                Tables\Columns\TextColumn::make('direction'),
                Tables\Columns\TextColumn::make('source_text')->limit(40),
                Tables\Columns\TextColumn::make('translated_text')->limit(40),
                Tables\Columns\TextColumn::make('latency_ms')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRealTimeTurns::route('/'),
            'view'  => Pages\ViewRealTimeTurn::route('/{record}'),
        ];
    }
}
