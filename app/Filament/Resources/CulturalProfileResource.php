<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CulturalProfileResource\Pages;
use App\Models\CulturalProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CulturalProfileResource extends Resource
{
    protected static ?string $model = CulturalProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    
    protected static ?string $navigationLabel = 'Cultural Profiles';
    
    protected static ?string $navigationGroup = 'Translation Engine';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('culture_code')
                            ->label('Culture Code')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('e.g., ar-SA, en-US'),
                        
                        Forms\Components\TextInput::make('culture_name')
                            ->label('Culture Name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., Saudi Arabian Arabic'),
                        
                        Forms\Components\TextInput::make('native_name')
                            ->label('Native Name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., العربية السعودية'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Cultural Characteristics')
                    ->schema([
                        Forms\Components\Textarea::make('characteristics')
                            ->label('Characteristics')
                            ->rows(3)
                            ->placeholder('Key cultural characteristics'),
                        
                        Forms\Components\Textarea::make('preferred_tones')
                            ->label('Preferred Tones')
                            ->rows(2)
                            ->placeholder('Preferred communication tones'),
                        
                        Forms\Components\Textarea::make('taboos')
                            ->label('Taboos')
                            ->rows(2)
                            ->placeholder('Cultural taboos to avoid'),
                        
                        Forms\Components\Textarea::make('special_styles')
                            ->label('Special Styles')
                            ->rows(2)
                            ->placeholder('Special writing styles'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Communication Style')
                    ->schema([
                        Forms\Components\Select::make('formality_level')
                            ->label('Formality Level')
                            ->options([
                                'very_formal' => 'Very Formal',
                                'formal' => 'Formal',
                                'neutral' => 'Neutral',
                                'casual' => 'Casual',
                                'very_casual' => 'Very Casual',
                            ])
                            ->default('neutral'),
                        
                        Forms\Components\Select::make('directness')
                            ->label('Directness')
                            ->options([
                                'very_direct' => 'Very Direct',
                                'direct' => 'Direct',
                                'moderate' => 'Moderate',
                                'indirect' => 'Indirect',
                                'very_indirect' => 'Very Indirect',
                            ])
                            ->default('moderate'),
                        
                        Forms\Components\Toggle::make('uses_honorifics')
                            ->label('Uses Honorifics')
                            ->default(false),
                        
                        Forms\Components\TextInput::make('emotional_expressiveness')
                            ->label('Emotional Expressiveness (1-10)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->default(5),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Localization')
                    ->schema([
                        Forms\Components\Select::make('text_direction')
                            ->label('Text Direction')
                            ->options([
                                'ltr' => 'Left to Right',
                                'rtl' => 'Right to Left',
                            ])
                            ->default('ltr'),
                        
                        Forms\Components\TextInput::make('date_formats')
                            ->label('Date Formats')
                            ->placeholder('e.g., DD/MM/YYYY'),
                        
                        Forms\Components\TextInput::make('number_formats')
                            ->label('Number Formats')
                            ->placeholder('e.g., 1,234.56'),
                        
                        Forms\Components\TextInput::make('currency_symbol')
                            ->label('Currency Symbol')
                            ->placeholder('e.g., $, €, ر.س'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Translation Guidelines')
                    ->schema([
                        Forms\Components\Textarea::make('system_prompt')
                            ->label('System Prompt')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('AI system prompt for this culture'),
                        
                        Forms\Components\Textarea::make('translation_guidelines')
                            ->label('Translation Guidelines')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Specific guidelines for translators'),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher priority profiles appear first'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('culture_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('culture_name')
                    ->label('Culture')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                    
                Tables\Columns\TextColumn::make('native_name')
                    ->label('Native Name')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('formality_level')
                    ->label('Formality')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('text_direction')
                    ->label('Direction')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All profiles')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                Tables\Filters\SelectFilter::make('text_direction')
                    ->label('Text Direction')
                    ->options([
                        'ltr' => 'Left to Right',
                        'rtl' => 'Right to Left',
                    ]),
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
            ->defaultSort('priority', 'desc');
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
            'index' => Pages\ListCulturalProfiles::route('/'),
            'create' => Pages\CreateCulturalProfile::route('/create'),
            'edit' => Pages\EditCulturalProfile::route('/{record}/edit'),
        ];
    }
}
