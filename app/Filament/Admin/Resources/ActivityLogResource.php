<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'سجل النشاطات';
    
    protected static ?string $pluralModelLabel = 'سجل النشاطات';
    
    protected static ?string $navigationGroup = 'النظام';
    
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->sortable()
                    ->searchable()
                    ->default('نظام'),
                
                Tables\Columns\BadgeColumn::make('action')
                    ->label('الإجراء')
                    ->colors([
                        'success' => 'create',
                        'warning' => 'update',
                        'danger' => 'delete',
                        'info' => 'view',
                        'primary' => 'login',
                        'secondary' => 'logout',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'create' => 'إنشاء',
                        'update' => 'تحديث',
                        'delete' => 'حذف',
                        'view' => 'عرض',
                        'login' => 'تسجيل دخول',
                        'logout' => 'تسجيل خروج',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('model')
                    ->label('النموذج')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : '-')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('model_id')
                    ->label('رقم السجل')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('عنوان IP')
                    ->copyable()
                    ->copyMessage('تم نسخ IP!')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('الإجراء')
                    ->options([
                        'create' => 'إنشاء',
                        'update' => 'تحديث',
                        'delete' => 'حذف',
                        'view' => 'عرض',
                        'login' => 'تسجيل دخول',
                        'logout' => 'تسجيل خروج',
                    ]),
                

                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // تحديث تلقائي كل 30 ثانية
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // منع الإنشاء اليدوي
    }
    
    public static function canEdit($record): bool
    {
        return false; // منع التعديل
    }
    
    public static function canDelete($record): bool
    {
        return false; // منع الحذف
    }
}
