<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'المستخدمون';
    
    protected static ?string $modelLabel = 'مستخدم';
    
    protected static ?string $pluralModelLabel = 'المستخدمون';
    
    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('معلومات الحساب')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('الدور')
                            ->options([
                                'super_admin' => 'مدير عام',
                                'admin' => 'مدير',
                                'user' => 'مستخدم',
                                'translator' => 'مترجم',
                                'moderator' => 'مشرف',
                            ])
                            ->default('user')
                            ->required(),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true)
                            ->inline(false),
                        
                        Forms\Components\Toggle::make('email_verified')
                            ->label('البريد موثق')
                            ->default(false)
                            ->inline(false),
                    ])->columns(3),
                
                Forms\Components\Section::make('معلومات إضافية')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->maxLength(20),
                        
                        Forms\Components\Select::make('language')
                            ->label('اللغة')
                            ->options([
                                'ar' => 'العربية',
                                'en' => 'English',
                                'fr' => 'Français',
                            ])
                            ->default('ar'),
                        
                        Forms\Components\Select::make('timezone')
                            ->label('المنطقة الزمنية')
                            ->options([
                                'Asia/Riyadh' => 'الرياض',
                                'Asia/Dubai' => 'دبي',
                                'Africa/Cairo' => 'القاهرة',
                            ])
                            ->default('Asia/Riyadh'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\BadgeColumn::make('role')
                    ->label('الدور')
                    ->colors([
                        'danger' => 'super_admin',
                        'warning' => 'admin',
                        'success' => 'user',
                        'info' => 'translator',
                        'primary' => 'moderator',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'super_admin' => 'مدير عام',
                        'admin' => 'مدير',
                        'user' => 'مستخدم',
                        'translator' => 'مترجم',
                        'moderator' => 'مشرف',
                        default => $state,
                    }),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('email_verified')
                    ->label('موثق')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('الدور')
                    ->options([
                        'super_admin' => 'مدير عام',
                        'admin' => 'مدير',
                        'user' => 'مستخدم',
                        'translator' => 'مترجم',
                        'moderator' => 'مشرف',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('نشط')
                    ->falseLabel('غير نشط'),
                
                Tables\Filters\TernaryFilter::make('email_verified')
                    ->label('موثق')
                    ->placeholder('الكل')
                    ->trueLabel('موثق')
                    ->falseLabel('غير موثق'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
