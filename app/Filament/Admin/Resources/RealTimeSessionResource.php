<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RealTimeSessionResource\Pages;
use App\Models\RealTimeSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RealTimeSessionResource extends Resource
{
    protected static ?string $model = RealTimeSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';
    protected static ?string $navigationGroup = 'Real-Time & AI';
    protected static ?string $navigationLabel = 'Real-Time Sessions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->maxLength(255),
            Forms\Components\Select::make('type')
                ->options([
                    'meeting' => 'Meeting',
                    'call' => 'Call',
                    'game' => 'Game',
                    'webinar' => 'Webinar',
                ]),
            Forms\Components\TextInput::make('source_language')->required(),
            Forms\Components\TextInput::make('target_language')->required(),
            Forms\Components\TextInput::make('source_culture_code'),
            Forms\Components\TextInput::make('target_culture_code'),
            Forms\Components\Toggle::make('bi_directional'),
            Forms\Components\Toggle::make('record_audio'),
            Forms\Components\Toggle::make('record_transcript'),
            Forms\Components\Toggle::make('is_active'),
            Forms\Components\TextInput::make('max_participants')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('public_id')->limit(12)->copyable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('source_language'),
                Tables\Columns\TextColumn::make('target_language'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('started_at')->dateTime(),
                Tables\Columns\TextColumn::make('ended_at')->dateTime(),
                Tables\Columns\TextColumn::make('turns_count')
                    ->label('Turns')
                    ->counts('turns'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRealTimeSessions::route('/'),
            'view'  => Pages\ViewRealTimeSession::route('/{record}'),
            'edit'  => Pages\EditRealTimeSession::route('/{record}/edit'),
        ];
    }
}
