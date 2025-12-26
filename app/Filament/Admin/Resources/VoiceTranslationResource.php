<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VoiceTranslationResource\Pages;
use App\Models\VoiceTranslation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VoiceTranslationResource extends Resource
{
    protected static ?string $model = VoiceTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationGroup = 'Translations';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Voice Translations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('user_subscription_id')
                            ->relationship('subscription', 'id')
                            ->label('Subscription'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Translation Details')
                    ->schema([
                        Forms\Components\TextInput::make('source_language')
                            ->label('Source Language')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('target_language')
                            ->label('Target Language')
                            ->required()
                            ->maxLength(10),
                        Forms\Components\Select::make('status')
                            ->options([
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Audio Information')
                    ->schema([
                        Forms\Components\TextInput::make('audio_duration_seconds')
                            ->label('Duration (seconds)')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('audio_file_size')
                            ->label('File Size (bytes)')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('output_duration_seconds')
                            ->label('Output Duration (seconds)')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Transcription & Translation')
                    ->schema([
                        Forms\Components\Textarea::make('transcribed_text')
                            ->label('Transcribed Text')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('translated_text')
                            ->label('Translated Text')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('transcription_confidence')
                            ->label('Confidence Score')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%')
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Billing')
                    ->schema([
                        Forms\Components\TextInput::make('cost')
                            ->label('Cost ($)')
                            ->numeric()
                            ->step(0.0001)
                            ->disabled(),
                        Forms\Components\TextInput::make('pricing_model')
                            ->disabled(),
                        Forms\Components\TextInput::make('processing_time_ms')
                            ->label('Processing Time (ms)')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('source_language')
                    ->label('From')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('target_language')
                    ->label('To')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('audio_duration_seconds')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => gmdate('i:s', $state))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('transcription_confidence')
                    ->label('Confidence')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . '%' : 'N/A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Cost')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('processing_time_ms')
                    ->label('Time (ms)')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('source_language')
                    ->label('Source Language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                        'fr' => 'French',
                        'de' => 'German',
                        'es' => 'Spanish',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('to'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['to'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('play_audio')
                    ->label('Play')
                    ->icon('heroicon-o-play')
                    ->url(fn (VoiceTranslation $record) => $record->output_audio_path 
                        ? route('voice.audio', $record->id) 
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn (VoiceTranslation $record) => $record->output_audio_path !== null),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListVoiceTranslations::route('/'),
            'view' => Pages\ViewVoiceTranslation::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('created_at', '>=', now()->subDay())->count();
    }
}
