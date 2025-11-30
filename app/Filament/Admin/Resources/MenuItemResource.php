<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    
    protected static ?string $navigationLabel = 'عناصر القائمة';
    
    protected static ?string $pluralModelLabel = 'عناصر القائمة';
    
    protected static ?string $navigationGroup = 'إدارة المحتوى';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات العنصر')
                    ->schema([
                        Forms\Components\Select::make('location')
                            ->label('الموقع')
                            ->options([
                                'header' => 'Header - الهيدر',
                                'footer' => 'Footer - الفوتر',
                            ])
                            ->required()
                            ->default('header'),
                        
                        Forms\Components\TextInput::make('title')
                            ->label('العنوان')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('url')
                            ->label('الرابط')
                            ->required()
                            ->maxLength(255)
                            ->helperText('يمكن استخدام / للصفحة الرئيسية، أو /about للصفحات الداخلية'),
                        
                        Forms\Components\Select::make('parent_id')
                            ->label('العنصر الأب (للقوائم المنسدلة)')
                            ->relationship('parent', 'title')
                            ->searchable()
                            ->preload()
                            ->helperText('اتركه فارغاً إذا كان عنصر رئيسي'),
                    ])->columns(2),
                
                Forms\Components\Section::make('الإعدادات')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('الترتيب')
                            ->numeric()
                            ->default(0)
                            ->helperText('الرقم الأصغر يظهر أولاً'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true),
                        
                        Forms\Components\Toggle::make('open_new_tab')
                            ->label('فتح في تبويب جديد')
                            ->default(false),
                        
                        Forms\Components\TextInput::make('icon')
                            ->label('الأيقونة (اختياري)')
                            ->maxLength(255)
                            ->helperText('مثل: heroicon-o-home'),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location')
                    ->label('الموقع')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'header' => 'info',
                        'footer' => 'warning',
                    })
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('url')
                    ->label('الرابط')
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('parent.title')
                    ->label('العنصر الأب')
                    ->searchable()
                    ->default('—'),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('open_new_tab')
                    ->label('تبويب جديد')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->label('الموقع')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
                
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('العنصر الأب')
                    ->relationship('parent', 'title')
                    ->searchable()
                    ->preload(),
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
            ->defaultSort('order');
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
