<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndustryBehaviorResource\Pages;
use App\Models\IndustryBehavior;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IndustryBehaviorResource extends Resource
{
    protected static ?string $model = IndustryBehavior::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Industry Behaviors';

    protected static ?string $navigationGroup = 'Cultural Intelligence';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('industry')
                            ->label('Industry')
                            ->required()
                            ->options([
                                'finance' => 'Finance',
                                'healthcare' => 'Healthcare',
                                'legal' => 'Legal',
                                'marketing' => 'Marketing',
                                'technology' => 'Technology',
                                'education' => 'Education',
                                'government' => 'Government',
                                'retail' => 'Retail',
                                'hospitality' => 'Hospitality',
                                'manufacturing' => 'Manufacturing',
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('language')
                            ->label('Language Code')
                            ->placeholder('ar, en, fr, etc.')
                            ->helperText('Leave empty for industry-wide rules')
                            ->maxLength(10),

                        Forms\Components\TextInput::make('culture')
                            ->label('Culture Code')
                            ->placeholder('ar_SA, en_US, etc.')
                            ->helperText('Leave empty for language-wide rules')
                            ->maxLength(10),

                        Forms\Components\Select::make('tone')
                            ->label('Tone')
                            ->options([
                                'formal' => 'Formal',
                                'informal' => 'Informal',
                                'technical' => 'Technical',
                                'conversational' => 'Conversational',
                                'professional' => 'Professional',
                            ])
                            ->default('formal'),

                        Forms\Components\Toggle::make('active')
                            ->label('Active')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Vocabulary Preferences')
                    ->schema([
                        Forms\Components\Repeater::make('vocabulary_preferred')
                            ->label('Preferred Terms')
                            ->helperText('Terms that should be used for this industry/language/culture')
                            ->simple(
                                Forms\Components\TextInput::make('term')
                                    ->label('Term')
                                    ->required()
                            )
                            ->defaultItems(0)
                            ->collapsible()
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('vocabulary_avoid')
                            ->label('Terms to Avoid')
                            ->helperText('Terms that should NOT be used (will be redacted)')
                            ->simple(
                                Forms\Components\TextInput::make('term')
                                    ->label('Term')
                                    ->required()
                            )
                            ->defaultItems(0)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Style Rules')
                    ->schema([
                        Forms\Components\Repeater::make('style_rules')
                            ->label('Style Guidelines')
                            ->helperText('Additional style rules for this industry context')
                            ->schema([
                                Forms\Components\TextInput::make('rule')
                                    ->label('Rule Name')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpan(2),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Documentation')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->helperText('Optional notes about this behavior configuration')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('industry')
                    ->label('Industry')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'finance' => 'success',
                        'healthcare' => 'danger',
                        'legal' => 'warning',
                        'marketing' => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('language')
                    ->label('Language')
                    ->default('—')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('culture')
                    ->label('Culture')
                    ->default('—')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tone')
                    ->label('Tone')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vocabulary_preferred')
                    ->label('Preferred Terms')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . ' terms' : '0 terms')
                    ->sortable(false),

                Tables\Columns\TextColumn::make('vocabulary_avoid')
                    ->label('Avoid Terms')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . ' terms' : '0 terms')
                    ->sortable(false),

                Tables\Columns\IconColumn::make('active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('industry')
                    ->options([
                        'finance' => 'Finance',
                        'healthcare' => 'Healthcare',
                        'legal' => 'Legal',
                        'marketing' => 'Marketing',
                        'technology' => 'Technology',
                        'education' => 'Education',
                        'government' => 'Government',
                        'retail' => 'Retail',
                        'hospitality' => 'Hospitality',
                        'manufacturing' => 'Manufacturing',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('industry', 'asc');
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
            'index' => Pages\ListIndustryBehaviors::route('/'),
            'create' => Pages\CreateIndustryBehavior::route('/create'),
            'edit' => Pages\EditIndustryBehavior::route('/{record}/edit'),
        ];
    }
}
