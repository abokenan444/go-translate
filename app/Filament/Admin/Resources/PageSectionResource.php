<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageSectionResource\Pages;
use App\Models\PageSection;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    
    protected static ?string $navigationLabel = 'أقسام الصفحات';
    
    protected static ?string $pluralModelLabel = 'أقسام الصفحات';
    
    protected static ?string $navigationGroup = 'إدارة المحتوى';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات القسم')
                    ->schema([
                        Forms\Components\Select::make('page_id')
                            ->label('الصفحة')
                            ->relationship('page', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('section_type')
                            ->label('نوع القسم')
                            ->options([
                                'hero' => 'Hero - قسم رئيسي',
                                'features' => 'Features - المميزات',
                                'stats' => 'Stats - الإحصائيات',
                                'demo' => 'Demo - التجربة',
                                'cta' => 'CTA - دعوة لإجراء',
                                'content' => 'Content - محتوى',
                                'testimonials' => 'Testimonials - آراء العملاء',
                                'pricing' => 'Pricing - الأسعار',
                                'faq' => 'FAQ - الأسئلة الشائعة',
                            ])
                            ->required()
                            ->reactive(),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('order')
                            ->label('الترتيب')
                            ->numeric()
                            ->default(0)
                            ->helperText('الرقم الأصغر يظهر أولاً'),
                    ])->columns(4),
                
                Forms\Components\Section::make('المحتوى')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('العنوان')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('subtitle')
                            ->label('العنوان الفرعي')
                            ->rows(2)
                            ->columnSpanFull(),
                        
                        Forms\Components\RichEditor::make('content')
                            ->label('المحتوى')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('الأزرار')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('نص الزر الأول')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('button_link')
                            ->label('رابط الزر الأول')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('button_text_secondary')
                            ->label('نص الزر الثاني')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('button_link_secondary')
                            ->label('رابط الزر الثاني')
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('الصورة')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('الصورة')
                            ->image()
                            ->directory('page-sections')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('بيانات إضافية (JSON)')
                    ->schema([
                        Forms\Components\KeyValue::make('data')
                            ->label('بيانات إضافية')
                            ->helperText('للإحصائيات، الميزات، وغيرها من البيانات المخصصة')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page.title')
                    ->label('الصفحة')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('section_type')
                    ->label('نوع القسم')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('page_id')
                    ->label('الصفحة')
                    ->relationship('page', 'title'),
                
                Tables\Filters\SelectFilter::make('section_type')
                    ->label('نوع القسم')
                    ->options([
                        'hero' => 'Hero',
                        'features' => 'Features',
                        'stats' => 'Stats',
                        'demo' => 'Demo',
                        'cta' => 'CTA',
                        'content' => 'Content',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
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
            'index' => Pages\ListPageSections::route('/'),
            'create' => Pages\CreatePageSection::route('/create'),
            'edit' => Pages\EditPageSection::route('/{record}/edit'),
        ];
    }
}
