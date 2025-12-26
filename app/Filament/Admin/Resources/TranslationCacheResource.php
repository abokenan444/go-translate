<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TranslationCacheResource\Pages;
use App\Models\TranslationCache;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TranslationCacheResource extends Resource
{
    protected static ?string $model = TranslationCache::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Translation Cache';

    protected static ?string $navigationGroup = 'Translation System';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cache Information')
                    ->schema([
                        Forms\Components\TextInput::make('source_text')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('translated_text')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('source_lang')
                            ->required()
                            ->options(config('languages'))
                            ->searchable(),
                        Forms\Components\Select::make('target_lang')
                            ->required()
                            ->options(config('languages'))
                            ->searchable(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Cache Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('cache_key')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('hit_count')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('updated_at')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_lang')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_lang')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source_text')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('translated_text')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('hit_count')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state > 50 => 'success',
                        $state > 10 => 'warning',
                        default => 'gray',
                    }),
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
                Tables\Filters\SelectFilter::make('source_lang')
                    ->options(config('languages'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('target_lang')
                    ->options(config('languages'))
                    ->searchable(),
                Tables\Filters\Filter::make('popular')
                    ->query(fn (Builder $query): Builder => $query->where('hit_count', '>', 10))
                    ->label('Popular (10+ hits)'),
                Tables\Filters\Filter::make('very_popular')
                    ->query(fn (Builder $query): Builder => $query->where('hit_count', '>', 50))
                    ->label('Very Popular (50+ hits)'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('hit_count', 'desc');
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
            'index' => Pages\ListTranslationCaches::route('/'),
            'create' => Pages\CreateTranslationCache::route('/create'),
            'view' => Pages\ViewTranslationCache::route('/{record}'),
            'edit' => Pages\EditTranslationCache::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
