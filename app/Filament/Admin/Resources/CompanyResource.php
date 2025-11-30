<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'الشركات';
    protected static ?string $modelLabel = 'شركة';
    protected static ?string $pluralModelLabel = 'الشركات';
    protected static ?string $navigationGroup = 'إدارة الشركات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الشركة')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الشركة')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('domain')
                            ->label('النطاق')
                            ->maxLength(255)
                            ->placeholder('example.com'),
                        
                        Forms\Components\Select::make('plan_id')
                            ->label('الباقة')
                            ->relationship('plan', 'name')
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'active' => 'نشط',
                                'inactive' => 'غير نشط',
                                'suspended' => 'معلق',
                            ])
                            ->default('active')
                            ->required(),
                        
                        Forms\Components\TextInput::make('member_count')
                            ->label('عدد الأعضاء')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('translation_memory_size')
                            ->label('حجم ذاكرة الترجمة')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('بالبايت'),
                        
                        Forms\Components\Toggle::make('sso_enabled')
                            ->label('تسجيل الدخول الموحد')
                            ->default(false),
                        
                        Forms\Components\Toggle::make('custom_domain_enabled')
                            ->label('نطاق مخصص')
                            ->default(false),
                    ])->columns(2),
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
                    ->label('اسم الشركة')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('domain')
                    ->label('النطاق')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('الباقة')
                    ->badge()
                    ->color('success')
                    ->default('لا يوجد'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'نشط',
                        'inactive' => 'غير نشط',
                        'suspended' => 'معلق',
                    }),
                
                Tables\Columns\TextColumn::make('member_count')
                    ->label('عدد الأعضاء')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('translation_memory_size')
                    ->label('ذاكرة الترجمة')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024 / 1024, 2) . ' MB' : '0 MB')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('sso_enabled')
                    ->label('SSO')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('custom_domain_enabled')
                    ->label('نطاق مخصص')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'active' => 'نشط',
                        'inactive' => 'غير نشط',
                        'suspended' => 'معلق',
                    ]),
                
                Tables\Filters\TernaryFilter::make('sso_enabled')
                    ->label('تسجيل الدخول الموحد'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\CompanyResource\RelationManagers\SettingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
            'view' => Pages\ViewCompany::route('/{record}'),
        ];
    }
}
