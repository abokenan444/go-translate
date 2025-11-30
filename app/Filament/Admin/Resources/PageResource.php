<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'الصفحات';
    
    protected static ?string $pluralModelLabel = 'الصفحات';
    
    protected static ?string $navigationGroup = 'إدارة المحتوى';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الصفحة')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('العنوان')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('الرابط (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('سيكون الرابط: https://culturaltranslate.com/page/{slug}')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'draft' => 'مسودة',
                                'published' => 'منشور',
                                'archived' => 'مؤرشف',
                            ])
                            ->default('draft')
                            ->required(),
                    ])->columns(3),
                
                Forms\Components\Section::make('المحتوى')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('المحتوى')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'heading',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('عرض في القوائم')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('show_in_header')
                                    ->label('عرض في الهيدر (Header)')
                                    ->helperText('سيظهر رابط الصفحة في قائمة الهيدر')
                                    ->reactive(),
                                
                                Forms\Components\TextInput::make('header_order')
                                    ->label('ترتيب الهيدر')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('الرقم الأصغر يظهر أولاً')
                                    ->visible(fn (callable $get) => $get('show_in_header')),
                            ]),
                        
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('show_in_footer')
                                    ->label('عرض في الفوتر (Footer)')
                                    ->helperText('سيظهر رابط الصفحة في قائمة الفوتر')
                                    ->reactive(),
                                
                                Forms\Components\Select::make('footer_column')
                                    ->label('عمود الفوتر')
                                    ->options([
                                        'column1' => 'العمود الأول',
                                        'column2' => 'العمود الثاني',
                                        'column3' => 'العمود الثالث',
                                    ])
                                    ->helperText('اختر في أي عمود تريد عرض الرابط')
                                    ->visible(fn (callable $get) => $get('show_in_footer')),
                                
                                Forms\Components\TextInput::make('footer_order')
                                    ->label('ترتيب الفوتر')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('الرقم الأصغر يظهر أولاً')
                                    ->visible(fn (callable $get) => $get('show_in_footer')),
                            ]),
                    ]),
                
                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('عنوان SEO')
                            ->helperText('إذا تركته فارغاً، سيتم استخدام عنوان الصفحة')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('meta_description')
                            ->label('وصف SEO')
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\TagsInput::make('meta_keywords')
                            ->label('الكلمات المفتاحية')
                            ->separator(',')
                            ->helperText('اضغط Enter بعد كل كلمة'),
                    ])->columns(1)->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('الرابط')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ الرابط!')
                    ->copyMessageDuration(1500),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'danger' => 'archived',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'مسودة',
                        'published' => 'منشور',
                        'archived' => 'مؤرشف',
                        default => $state,
                    }),
                
                Tables\Columns\IconColumn::make('show_in_header')
                    ->label('الهيدر')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\IconColumn::make('show_in_footer')
                    ->label('الفوتر')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'published' => 'منشور',
                        'archived' => 'مؤرشف',
                    ]),
                
                Tables\Filters\TernaryFilter::make('show_in_header')
                    ->label('يظهر في الهيدر'),
                
                Tables\Filters\TernaryFilter::make('show_in_footer')
                    ->label('يظهر في الفوتر'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
            'view' => Pages\ViewPage::route('/{record}'),
        ];
    }
}
