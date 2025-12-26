<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CulturalProfileResource\Pages;
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
    
    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Culture Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('e.g., ar-SA, en-US'),
                        
                        Forms\Components\TextInput::make('name')
                            ->label('Culture Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Saudi Arabian Arabic'),
                        
                        Forms\Components\TextInput::make('locale')
                            ->label('Locale')
                            ->required()
                            ->default('en')
                            ->maxLength(10)
                            ->placeholder('e.g., en, ar'),
                        
                        Forms\Components\TextInput::make('region')
                            ->label('Region')
                            ->maxLength(100)
                            ->placeholder('e.g., Middle East, North America'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Cultural Values (JSON)')
                    ->schema([
                        Forms\Components\Textarea::make('values_json')
                            ->label('Cultural Values')
                            ->rows(5)
                            ->placeholder('{"formality": "high", "directness": "indirect"}')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Examples (JSON)')
                    ->schema([
                        Forms\Components\Textarea::make('examples_json')
                            ->label('Translation Examples')
                            ->rows(5)
                            ->placeholder('{"greetings": ["مرحبا", "أهلا"], "farewells": ["وداعا"]}')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Culture Name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                    
                Tables\Columns\TextColumn::make('locale')
                    ->label('Locale')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('region')
                    ->label('Region')
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->label('Locale')
                    ->options([
                        'ar' => 'Arabic',
                        'en' => 'English',
                        'es' => 'Spanish',
                        'fr' => 'French',
                        'de' => 'German',
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
            ->defaultSort('code', 'asc');
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
