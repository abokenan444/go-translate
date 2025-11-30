<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebsiteContentResource\Pages;
use App\Models\WebsiteContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteContentResource extends Resource
{
    protected static ?string $model = WebsiteContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Website Content';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Information')
                    ->schema([
                        Forms\Components\TextInput::make('page_slug')
                            ->label('Page Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('URL-friendly identifier (e.g., privacy, terms, about)')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('locale')
                            ->label('Language')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                                'es' => 'Spanish',
                                'fr' => 'French',
                                'de' => 'German',
                                'it' => 'Italian',
                                'pt' => 'Portuguese',
                                'ru' => 'Russian',
                                'zh' => 'Chinese',
                                'ja' => 'Japanese',
                                'ko' => 'Korean',
                                'hi' => 'Hindi',
                                'tr' => 'Turkish',
                            ])
                            ->default('en')
                            ->required(),
                        
                        Forms\Components\TextInput::make('page_title')
                            ->label('Page Title')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Page Content')
                    ->schema([
                        Forms\Components\Repeater::make('sections')
                            ->label('Content Sections')
                            ->schema([
                                Forms\Components\TextInput::make('heading')
                                    ->label('Section Heading')
                                    ->maxLength(255),
                                
                                Forms\Components\RichEditor::make('content')
                                    ->label('Section Content')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? 'Untitled Section')
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('SEO Settings')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(60)
                            ->helperText('Recommended: 50-60 characters'),
                        
                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Recommended: 150-160 characters'),
                        
                        Forms\Components\TextInput::make('seo_keywords')
                            ->label('SEO Keywords')
                            ->helperText('Comma-separated keywords'),
                    ])->columns(1)->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_slug')
                    ->label('Page')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('locale')
                    ->label('Language')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('page_title')
                    ->label('Title')
                    ->searchable()
                    ->limit(50),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ]),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
                
                Tables\Filters\SelectFilter::make('locale')
                    ->label('Language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                        'es' => 'Spanish',
                        'fr' => 'French',
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
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteContents::route('/'),
            'create' => Pages\CreateWebsiteContent::route('/create'),
            'edit' => Pages\EditWebsiteContent::route('/{record}/edit'),
        ];
    }
}
