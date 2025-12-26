<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TranslationMemoryResource\Pages;
use App\Models\TranslationMemory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TranslationMemoryResource extends Resource
{
    protected static ?string $model = TranslationMemory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    
    protected static ?string $navigationGroup = 'Cultural Engine';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $pluralModelLabel = 'Translation Memory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Translation Pair')
                    ->schema([
                        Forms\Components\Textarea::make('source_text')
                            ->required()
                            ->rows(4)
                            ->label('Source Text'),
                        
                        Forms\Components\Textarea::make('target_text')
                            ->required()
                            ->rows(4)
                            ->label('Target Text'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Language & Context')
                    ->schema([
                        Forms\Components\Select::make('source_language')
                            ->required()
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                                'nl' => 'Dutch',
                                'fr' => 'French',
                                'de' => 'German',
                                'es' => 'Spanish',
                            ])
                            ->label('Source Language'),
                        
                        Forms\Components\Select::make('target_language')
                            ->required()
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                                'nl' => 'Dutch',
                                'fr' => 'French',
                                'de' => 'German',
                                'es' => 'Spanish',
                            ])
                            ->label('Target Language'),
                        
                        Forms\Components\Select::make('context_type')
                            ->options([
                                'marketing' => 'Marketing',
                                'legal' => 'Legal',
                                'technical' => 'Technical',
                                'medical' => 'Medical',
                                'general' => 'General',
                            ])
                            ->label('Context Type'),
                        
                        Forms\Components\Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Associated Project'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->default(true)
                            ->label('Approved for Use'),
                        
                        Forms\Components\TextInput::make('usage_count')
                            ->numeric()
                            ->default(1)
                            ->label('Usage Count')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_text')
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip(fn ($record) => $record->source_text),
                
                Tables\Columns\TextColumn::make('target_text')
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip(fn ($record) => $record->target_text),
                
                Tables\Columns\BadgeColumn::make('source_language')
                    ->label('From')
                    ->colors([
                        'primary' => 'en',
                        'success' => 'ar',
                        'warning' => 'nl',
                    ]),
                
                Tables\Columns\BadgeColumn::make('target_language')
                    ->label('To')
                    ->colors([
                        'primary' => 'en',
                        'success' => 'ar',
                        'warning' => 'nl',
                    ]),
                
                Tables\Columns\BadgeColumn::make('context_type')
                    ->label('Context')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('usage_count')
                    ->sortable()
                    ->label('Used')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean()
                    ->label('Approved'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source_language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                        'nl' => 'Dutch',
                        'fr' => 'French',
                    ])
                    ->label('Source Language'),
                
                Tables\Filters\SelectFilter::make('target_language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                        'nl' => 'Dutch',
                        'fr' => 'French',
                    ])
                    ->label('Target Language'),
                
                Tables\Filters\SelectFilter::make('context_type')
                    ->options([
                        'marketing' => 'Marketing',
                        'legal' => 'Legal',
                        'technical' => 'Technical',
                        'medical' => 'Medical',
                        'general' => 'General',
                    ])
                    ->label('Context'),
                
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approved'),
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
            ->defaultSort('usage_count', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslationMemories::route('/'),
            'create' => Pages\CreateTranslationMemory::route('/create'),
            'edit' => Pages\EditTranslationMemory::route('/{record}/edit'),
        ];
    }
}
