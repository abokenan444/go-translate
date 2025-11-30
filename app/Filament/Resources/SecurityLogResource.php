<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SecurityLogResource\Pages;
use App\Models\SecurityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SecurityLogResource extends Resource
{
    protected static ?string $model = SecurityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $navigationLabel = 'سجلات الأمان';

    protected static ?string $modelLabel = 'سجل أمني';

    protected static ?string $pluralModelLabel = 'سجلات الأمان';

    protected static ?string $navigationGroup = 'الأمان';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::recent(1)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::recent(1)->count();
        
        if ($count > 10) {
            return 'danger';
        } elseif ($count > 5) {
            return 'warning';
        }
        
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('attack_type')
                    ->label('نوع الهجوم')
                    ->disabled(),
                
                Forms\Components\TextInput::make('ip_address')
                    ->label('عنوان IP')
                    ->disabled(),
                
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->disabled()
                    ->columnSpanFull(),
                
                Forms\Components\TextInput::make('input_field')
                    ->label('الحقل المستهدف')
                    ->disabled(),
                
                Forms\Components\Textarea::make('suspicious_value')
                    ->label('القيمة المشبوهة')
                    ->disabled()
                    ->rows(3)
                    ->columnSpanFull(),
                
                Forms\Components\Textarea::make('user_agent')
                    ->label('User Agent')
                    ->disabled()
                    ->rows(2)
                    ->columnSpanFull(),
                
                Forms\Components\TextInput::make('request_method')
                    ->label('طريقة الطلب')
                    ->disabled(),
                
                Forms\Components\Select::make('severity')
                    ->label('مستوى الخطورة')
                    ->options([
                        'low' => 'منخفض',
                        'medium' => 'متوسط',
                        'high' => 'عالي',
                        'critical' => 'حرج',
                    ])
                    ->disabled(),
                
                Forms\Components\Toggle::make('blocked')
                    ->label('تم الحظر')
                    ->disabled(),
                
                Forms\Components\KeyValue::make('payload')
                    ->label('البيانات الكاملة')
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('attack_type')
                    ->label('نوع الهجوم')
                    ->colors([
                        'danger' => 'SQL Injection',
                        'warning' => 'XSS',
                        'info' => fn ($state): bool => !in_array($state, ['SQL Injection', 'XSS']),
                    ])
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('عنوان IP')
                    ->searchable()
                    ->copyable()
                    ->tooltip('انقر للنسخ'),
                
                Tables\Columns\TextColumn::make('url')
                    ->label('URL المستهدف')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->url),
                
                Tables\Columns\BadgeColumn::make('severity')
                    ->label('الخطورة')
                    ->colors([
                        'success' => 'low',
                        'warning' => 'medium',
                        'danger' => 'high',
                        'danger' => 'critical',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'منخفض',
                        'medium' => 'متوسط',
                        'high' => 'عالي',
                        'critical' => 'حرج',
                        default => $state,
                    })
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('blocked')
                    ->label('محظور')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\IconColumn::make('notified_at')
                    ->label('تم التنبيه')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->notified_at))
                    ->trueIcon('heroicon-o-bell')
                    ->falseIcon('heroicon-o-bell-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('attack_type')
                    ->label('نوع الهجوم')
                    ->options([
                        'SQL Injection' => 'SQL Injection',
                        'XSS' => 'XSS',
                        'CSRF' => 'CSRF',
                        'File Upload' => 'File Upload',
                        'Brute Force' => 'Brute Force',
                    ]),
                
                Tables\Filters\SelectFilter::make('severity')
                    ->label('مستوى الخطورة')
                    ->options([
                        'low' => 'منخفض',
                        'medium' => 'متوسط',
                        'high' => 'عالي',
                        'critical' => 'حرج',
                    ]),
                
                Tables\Filters\TernaryFilter::make('blocked')
                    ->label('محظور')
                    ->placeholder('الكل')
                    ->trueLabel('محظور')
                    ->falseLabel('غير محظور'),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('block_ip')
                    ->label('حظر IP')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (SecurityLog $record) {
                        // TODO: Add IP to blacklist
                        // You can implement IP blocking logic here
                    })
                    ->hidden(fn ($record) => $record->blocked),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSecurityLogs::route('/'),
            'view' => Pages\ViewSecurityLog::route('/{record}'),
        ];
    }
}
