<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IndustryTemplateResource\Pages;
use App\Filament\Admin\Resources\IndustryTemplateResource\RelationManagers;
use App\Models\Cultural\IndustryTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndustryTemplateResource extends Resource
{
    protected static ?string $model = IndustryTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    
    protected static ?string $navigationLabel = 'قوالب الصناعات';
    
    protected static ?string $modelLabel = 'قالب صناعة';
    
    protected static ?string $pluralModelLabel = 'قوالب الصناعات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('المفتاح')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('locale')
                            ->label('اللغة')
                            ->options([
                                'en' => 'English',
                                'ar' => 'العربية',
                                'es' => 'Español',
                                'fr' => 'Français',
                                'de' => 'Deutsch',
                            ])
                            ->default('en')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(3),
                
                Forms\Components\Section::make('قالب التعليمات')
                    ->schema([
                        Forms\Components\Textarea::make('prompt_template')
                            ->label('قالب التعليمات (Prompt Template)')
                            ->required()
                            ->rows(8)
                            ->helperText('استخدم هذا الحقل لتحديد كيفية تعامل النظام مع هذه الصناعة'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('المفتاح')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('locale')
                    ->label('اللغة')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->label('اللغة')
                    ->options([
                        'en' => 'English',
                        'ar' => 'العربية',
                        'es' => 'Español',
                        'fr' => 'Français',
                        'de' => 'Deutsch',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListIndustryTemplates::route('/'),
            'create' => Pages\CreateIndustryTemplate::route('/create'),
            'edit' => Pages\EditIndustryTemplate::route('/{record}/edit'),
        ];
    }
}
