<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BrandVoiceResource\Pages;
use App\Models\BrandVoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BrandVoiceResource extends Resource
{
    protected static ?string $model = BrandVoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    
    protected static ?string $navigationGroup = 'Cultural Engine';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Brand Voice Name'),
                        
                        Forms\Components\Select::make('tone')
                            ->required()
                            ->options([
                                'formal' => 'Formal',
                                'friendly' => 'Friendly',
                                'bold' => 'Bold',
                                'corporate' => 'Corporate',
                                'professional' => 'Professional',
                                'casual' => 'Casual',
                                'enthusiastic' => 'Enthusiastic',
                            ])
                            ->label('Tone'),
                        
                        Forms\Components\Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Associated Project'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Brand Values')
                    ->schema([
                        Forms\Components\Repeater::make('values')
                            ->schema([
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->label('Brand Value'),
                            ])
                            ->label('Brand Values')
                            ->addActionLabel('Add Value')
                            ->defaultItems(0),
                    ]),
                
                Forms\Components\Section::make('Examples')
                    ->schema([
                        Forms\Components\Repeater::make('examples')
                            ->schema([
                                Forms\Components\Textarea::make('before')
                                    ->required()
                                    ->rows(2)
                                    ->label('Before'),
                                Forms\Components\Textarea::make('after')
                                    ->required()
                                    ->rows(2)
                                    ->label('After'),
                            ])
                            ->label('Before/After Examples')
                            ->addActionLabel('Add Example')
                            ->defaultItems(0)
                            ->columns(2),
                    ]),
                
                Forms\Components\Section::make('Guidelines')
                    ->schema([
                        Forms\Components\Textarea::make('guidelines')
                            ->rows(5)
                            ->label('Additional Writing Guidelines'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('tone')
                    ->colors([
                        'primary' => 'formal',
                        'success' => 'friendly',
                        'warning' => 'bold',
                        'info' => 'corporate',
                    ]),
                
                Tables\Columns\TextColumn::make('project.name')
                    ->searchable()
                    ->sortable()
                    ->label('Project'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tone')
                    ->options([
                        'formal' => 'Formal',
                        'friendly' => 'Friendly',
                        'bold' => 'Bold',
                        'corporate' => 'Corporate',
                        'professional' => 'Professional',
                        'casual' => 'Casual',
                        'enthusiastic' => 'Enthusiastic',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrandVoices::route('/'),
            'create' => Pages\CreateBrandVoice::route('/create'),
            'edit' => Pages\EditBrandVoice::route('/{record}/edit'),
        ];
    }
}
