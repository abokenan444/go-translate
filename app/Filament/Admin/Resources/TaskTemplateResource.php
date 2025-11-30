<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TaskTemplateResource\Pages;
use App\Models\TaskTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskTemplateResource extends Resource
{
    protected static ?string $model = TaskTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Task Templates';
    protected static ?string $pluralLabel = 'Task Templates';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Template Key')
                    ->placeholder('e.g., translation.general_1'),
                    
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Template Name')
                    ->placeholder('e.g., General Cultural Translation #1'),
                    
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'translation' => 'Translation',
                        'copywriting' => 'Copywriting',
                        'content' => 'Content Creation',
                        'email' => 'Email',
                        'social' => 'Social Media',
                    ])
                    ->default('translation'),
                    
                Forms\Components\Select::make('category')
                    ->options([
                        'generic' => 'Generic',
                        'marketing' => 'Marketing',
                        'technical' => 'Technical',
                        'legal' => 'Legal',
                        'medical' => 'Medical',
                        'ecommerce' => 'E-Commerce',
                        'saas' => 'SaaS',
                        'tourism' => 'Tourism',
                        'finance' => 'Finance',
                        'education' => 'Education',
                    ]),
                    
                Forms\Components\TextInput::make('industry_key')
                    ->label('Industry Key')
                    ->placeholder('e.g., ecommerce'),
                    
                Forms\Components\Textarea::make('base_prompt')
                    ->required()
                    ->rows(10)
                    ->label('Base Prompt Template')
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('meta')
                    ->label('Metadata (JSON)')
                    ->rows(5)
                    ->columnSpanFull()
                    ->helperText('Store additional metadata in JSON format'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'translation',
                        'success' => 'copywriting',
                        'warning' => 'content',
                        'danger' => 'email',
                        'info' => 'social',
                    ]),
                    
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Category')
                    ->colors([
                        'secondary' => 'generic',
                        'success' => 'marketing',
                        'primary' => 'technical',
                        'warning' => 'legal',
                        'danger' => 'medical',
                        'info' => 'ecommerce',
                    ]),
                    
                Tables\Columns\TextColumn::make('industry_key')
                    ->label('Industry')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'translation' => 'Translation',
                        'copywriting' => 'Copywriting',
                        'content' => 'Content Creation',
                        'email' => 'Email',
                        'social' => 'Social Media',
                    ]),
                    
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'generic' => 'Generic',
                        'marketing' => 'Marketing',
                        'technical' => 'Technical',
                        'legal' => 'Legal',
                        'medical' => 'Medical',
                        'ecommerce' => 'E-Commerce',
                        'saas' => 'SaaS',
                        'tourism' => 'Tourism',
                        'finance' => 'Finance',
                        'education' => 'Education',
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
            ->defaultSort('id', 'asc');
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
            'index' => Pages\ListTaskTemplates::route('/'),
            'create' => Pages\CreateTaskTemplate::route('/create'),
            'edit' => Pages\EditTaskTemplate::route('/{record}/edit'),
        ];
    }
}
