<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSettingResource\Pages;
use App\Models\ContactSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactSettingResource extends Resource
{
    protected static ?string $model = ContactSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'معلومات الاتصال';

    protected static ?string $modelLabel = 'معلومة اتصال';

    protected static ?string $pluralModelLabel = 'معلومات الاتصال';

    protected static ?string $navigationGroup = 'الإعدادات';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('المفتاح')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('مثال: phone, email, address'),
                
                Forms\Components\TextInput::make('label')
                    ->label('التسمية')
                    ->required()
                    ->maxLength(255)
                    ->helperText('الاسم الذي سيظهر في الموقع'),
                
                Forms\Components\Select::make('type')
                    ->label('النوع')
                    ->options([
                        'text' => 'نص',
                        'email' => 'بريد إلكتروني',
                        'phone' => 'هاتف',
                        'textarea' => 'نص طويل',
                        'url' => 'رابط',
                    ])
                    ->required()
                    ->default('text'),
                
                Forms\Components\Textarea::make('value')
                    ->label('القيمة')
                    ->rows(3)
                    ->maxLength(65535)
                    ->columnSpanFull(),
                
                Forms\Components\Select::make('group')
                    ->label('المجموعة')
                    ->options([
                        'general' => 'عام',
                        'business_hours' => 'ساعات العمل',
                        'address' => 'العنوان',
                        'social' => 'وسائل التواصل',
                    ])
                    ->required()
                    ->default('general'),
                
                Forms\Components\TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('التسمية')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('key')
                    ->label('المفتاح')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('value')
                    ->label('القيمة')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('النوع')
                    ->colors([
                        'primary' => 'text',
                        'success' => 'email',
                        'warning' => 'phone',
                        'info' => 'url',
                    ]),
                
                Tables\Columns\TextColumn::make('group')
                    ->label('المجموعة')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('المجموعة')
                    ->options([
                        'general' => 'عام',
                        'business_hours' => 'ساعات العمل',
                        'address' => 'العنوان',
                        'social' => 'وسائل التواصل',
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactSettings::route('/'),
            'create' => Pages\CreateContactSetting::route('/create'),
            'edit' => Pages\EditContactSetting::route('/{record}/edit'),
        ];
    }
}
