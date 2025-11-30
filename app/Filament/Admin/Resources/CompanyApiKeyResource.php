<?php
namespace App\Filament\Admin\Resources;

use App\Models\CompanyApiKey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CompanyApiKeyResource extends Resource
{
    protected static ?string $model = CompanyApiKey::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'مفاتيح API للشركات';
    protected static ?string $modelLabel = 'مفتاح API';
    protected static ?string $pluralModelLabel = 'مفاتيح API';
    protected static ?string $navigationGroup = 'إدارة الشركات';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('إعدادات المفتاح')
                ->schema([
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('name')->label('الاسم'),
                    Forms\Components\TextInput::make('key')
                        ->label('المفتاح')
                        ->dehydrated(true)
                        ->helperText('سيتم توليده تلقائياً إذا تُرك فارغاً')
                        ->afterStateHydrated(function ($component, $state) {
                            if (!$state) {
                                $component->state(Str::uuid()->toString() . '-' . Str::random(24));
                            }
                        }),
                    Forms\Components\KeyValue::make('scopes')
                        ->label('الصلاحيات')
                        ->helperText('مثال: translate:read, translate:write, glossary:manage'),
                    Forms\Components\TextInput::make('rate_limit_per_minute')
                        ->numeric()->label('الحد/دقيقة')->default(60),
                    Forms\Components\DateTimePicker::make('expires_at')->label('ينتهي في'),
                    Forms\Components\Toggle::make('revoked')->label('ملغى')->default(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')->label('الشركة')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('key')->label('المفتاح')->copyable(),
                Tables\Columns\TextColumn::make('rate_limit_per_minute')->label('الحد/دقيقة'),
                Tables\Columns\IconColumn::make('revoked')->label('ملغى')->boolean(),
                Tables\Columns\TextColumn::make('expires_at')->dateTime()->label('ينتهي في'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('أُنشئ')->toggleable(isToggledHiddenByDefault: true),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => CompanyApiKeyResource\Pages\ListCompanyApiKeys::route('/'),
            'create' => CompanyApiKeyResource\Pages\CreateCompanyApiKey::route('/create'),
            'edit' => CompanyApiKeyResource\Pages\EditCompanyApiKey::route('/{record}/edit'),
            'view' => CompanyApiKeyResource\Pages\ViewCompanyApiKey::route('/{record}'),
        ];
    }
}
