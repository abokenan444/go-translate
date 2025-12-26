<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TranslatorPerformanceResource\Pages;
use App\Models\TranslatorPerformance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TranslatorPerformanceResource extends Resource
{
    protected static ?string $model = TranslatorPerformance::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Partner Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Translator Performance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Translator Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->label('Translator'),

                        Forms\Components\DatePicker::make('period_start')
                            ->required(),

                        Forms\Components\DatePicker::make('period_end')
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Performance Metrics')
                    ->schema([
                        Forms\Components\TextInput::make('total_documents')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('completed_documents')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('average_quality_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->required(),

                        Forms\Components\TextInput::make('average_delivery_time')
                            ->numeric()
                            ->suffix('hours')
                            ->required(),

                        Forms\Components\TextInput::make('on_time_delivery_rate')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->required(),

                        Forms\Components\TextInput::make('revision_rate')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Scores')
                    ->schema([
                        Forms\Components\TextInput::make('quality_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('/100')
                            ->required()
                            ->helperText('40% weight'),

                        Forms\Components\TextInput::make('speed_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('/100')
                            ->required()
                            ->helperText('25% weight'),

                        Forms\Components\TextInput::make('reliability_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('/100')
                            ->required()
                            ->helperText('25% weight'),

                        Forms\Components\TextInput::make('communication_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('/100')
                            ->required()
                            ->helperText('10% weight'),

                        Forms\Components\TextInput::make('overall_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('/100')
                            ->required()
                            ->disabled()
                            ->helperText('Calculated weighted average'),
                    ])->columns(3),

                Forms\Components\Section::make('Additional Data')
                    ->schema([
                        Forms\Components\TextInput::make('disputes_count')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('client_satisfaction')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.1)
                            ->suffix('/ 5.0')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Translator')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('period_start')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('period_end')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('overall_score')
                    ->numeric(decimalPlaces: 1)
                    ->suffix('/100')
                    ->sortable()
                    ->color(fn ($state) => match(true) {
                        $state >= 90 => 'success',
                        $state >= 75 => 'primary',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('completed_documents')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('average_quality_score')
                    ->numeric(decimalPlaces: 1)
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('on_time_delivery_rate')
                    ->numeric(decimalPlaces: 1)
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('client_satisfaction')
                    ->numeric(decimalPlaces: 1)
                    ->suffix('/5.0')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('disputes_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('overall_score')
                    ->form([
                        Forms\Components\TextInput::make('score_from')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Forms\Components\TextInput::make('score_to')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['score_from'], fn ($q, $v) => $q->where('overall_score', '>=', $v))
                            ->when($data['score_to'], fn ($q, $v) => $q->where('overall_score', '<=', $v));
                    }),

                Tables\Filters\Filter::make('period')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $v) => $q->where('period_start', '>=', $v))
                            ->when($data['until'], fn ($q, $v) => $q->where('period_end', '<=', $v));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('overall_score', 'desc');
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
            'index' => Pages\ListTranslatorPerformances::route('/'),
            'create' => Pages\CreateTranslatorPerformance::route('/create'),
            'view' => Pages\ViewTranslatorPerformance::route('/{record}'),
            'edit' => Pages\EditTranslatorPerformance::route('/{record}/edit'),
        ];
    }
}
