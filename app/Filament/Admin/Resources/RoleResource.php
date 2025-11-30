<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    
    protected static ?string $navigationLabel = 'الأدوار';
    
    protected static ?string $modelLabel = 'دور';
    
    protected static ?string $pluralModelLabel = 'الأدوار';
    
    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الدور')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الدور')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true),
                    ]),
                
                Forms\Components\Section::make('الصلاحيات')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('الصلاحيات المتاحة')
                            ->options([
                                'users.view' => 'عرض المستخدمين',
                                'users.create' => 'إضافة مستخدمين',
                                'users.edit' => 'تعديل المستخدمين',
                                'users.delete' => 'حذف المستخدمين',
                                'roles.view' => 'عرض الأدوار',
                                'roles.create' => 'إضافة أدوار',
                                'roles.edit' => 'تعديل الأدوار',
                                'roles.delete' => 'حذف الأدوار',
                                'subscriptions.view' => 'عرض الاشتراكات',
                                'subscriptions.create' => 'إضافة اشتراكات',
                                'subscriptions.edit' => 'تعديل الاشتراكات',
                                'subscriptions.delete' => 'حذف الاشتراكات',
                                'content.view' => 'عرض المحتوى',
                                'content.create' => 'إضافة محتوى',
                                'content.edit' => 'تعديل المحتوى',
                                'content.delete' => 'حذف المحتوى',
                                'reports.view' => 'عرض التقارير',
                                'settings.view' => 'عرض الإعدادات',
                                'settings.edit' => 'تعديل الإعدادات',
                            ])
                            ->columns(3)
                            ->gridDirection('row'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدور')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(50),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
