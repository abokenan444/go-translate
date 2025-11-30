<?php
namespace App\Filament\Admin\Resources;

use App\Models\CompanyIntegration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompanyIntegrationResource extends Resource
{
    protected static ?string $model = CompanyIntegration::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'تكاملات الشركات';
    protected static ?string $modelLabel = 'تكامل';
    protected static ?string $pluralModelLabel = 'التكاملات';
    protected static ?string $navigationGroup = 'إدارة الشركات';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات التكامل')
                ->description('أنشئ تكاملاً جديداً للسماح للشركة بالاتصال عبر API')
                ->schema([
                    Forms\Components\Select::make('company_id')
                        ->label('الشركة')
                        ->relationship('company', 'name')
                        ->searchable()
                        ->required()
                        ->preload(),
                    
                    Forms\Components\Select::make('provider')
                        ->label('المزوّد - نوع المنصة')
                        ->options([
                            'custom' => 'Custom API - واجهة برمجية مخصصة',
                            'wordpress' => 'WordPress - ووردبريس',
                            'drupal' => 'Drupal - دروبال',
                            'shopify' => 'Shopify - شوبيفاي',
                            'magento' => 'Magento - ماجنتو',
                            'woocommerce' => 'WooCommerce - ووكومرس',
                        ])
                        ->required()
                        ->searchable()
                        ->helperText('اختر نوع المنصة التي تستخدمها الشركة'),
                    
                    Forms\Components\TextInput::make('api_key')
                        ->label('API Key - المفتاح العام')
                        ->default(fn() => 'ck_' . bin2hex(random_bytes(16)))
                        ->helperText('سيتم إنشاؤه تلقائياً - أعطِه للشركة لاستخدامه في طلبات API')
                        ->password()
                        ->revealable()
                        ->dehydrated(true)
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('generate_api_key')
                                ->icon('heroicon-m-arrow-path')
                                ->label('إنشاء جديد')
                                ->action(function (Forms\Set $set) {
                                    $set('api_key', 'ck_' . bin2hex(random_bytes(16)));
                                })
                        ),
                    
                    Forms\Components\TextInput::make('api_secret')
                        ->label('API Secret - المفتاح السري')
                        ->default(fn() => 'cs_' . bin2hex(random_bytes(32)))
                        ->helperText('للتوقيع على Webhooks - احتفظ به سرياً')
                        ->password()
                        ->revealable()
                        ->dehydrated(true)
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('generate_api_secret')
                                ->icon('heroicon-m-arrow-path')
                                ->label('إنشاء جديد')
                                ->action(function (Forms\Set $set) {
                                    $set('api_secret', 'cs_' . bin2hex(random_bytes(32)));
                                })
                        ),
                    
                    Forms\Components\TextInput::make('webhook_url')
                        ->label('Webhook URL - رابط استقبال الأحداث')
                        ->url()
                        ->placeholder('https://company.com/api/webhooks/cultural-translate')
                        ->helperText('الرابط الذي تملكه الشركة لاستقبال إشعارات الأحداث')
                        ->columnSpanFull(),
                    
                    Forms\Components\CheckboxList::make('events')
                        ->label('الأحداث المُفعّلة')
                        ->options([
                            'translation.completed' => 'translation.completed - عند اكتمال الترجمة',
                            'translation.failed' => 'translation.failed - عند فشل الترجمة',
                            'glossary.updated' => 'glossary.updated - عند تحديث المصطلحات',
                            'brand_profile.updated' => 'brand_profile.updated - عند تحديث البراند',
                            'usage.threshold' => 'usage.threshold - عند الوصول لحد الاستخدام',
                        ])
                        ->columns(2)
                        ->default(['translation.completed', 'translation.failed'])
                        ->helperText('اختر الأحداث التي سيتم إرسال إشعارات بها')
                        ->columnSpanFull(),
                    
                    Forms\Components\Repeater::make('domains')
                        ->label('النطاقات المسموحة')
                        ->schema([
                            Forms\Components\TextInput::make('domain')
                                ->label('نطاق')
                                ->placeholder('example.com')
                                ->required()
                        ])
                        ->collapsed()
                        ->helperText('النطاقات المصرح لها بالاتصال بهذا التكامل')
                        ->columnSpanFull(),
                    
                    Forms\Components\KeyValue::make('features_flags')
                        ->label('ميزات مخصصة')
                        ->keyLabel('اسم الميزة')
                        ->valueLabel('القيمة')
                        ->helperText('إعدادات إضافية خاصة بهذا التكامل')
                        ->columnSpanFull(),
                    
                    Forms\Components\Toggle::make('status')
                        ->label('نشط')
                        ->inline(false)
                        ->default(true)
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->helperText('تفعيل/تعطيل هذا التكامل'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')->label('الشركة')->searchable(),
                Tables\Columns\TextColumn::make('provider')->label('المزوّد')->badge()->color('info'),
                Tables\Columns\IconColumn::make('status')->label('نشط')->boolean(),
                Tables\Columns\TextColumn::make('webhook_url')->label('Webhook')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_success_at')->dateTime()->label('آخر نجاح')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_error_at')->dateTime()->label('آخر خطأ')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('أُنشئ')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider')->options([
                    'custom' => 'Custom','wordpress'=>'WordPress','drupal'=>'Drupal','shopify'=>'Shopify'
                ]),
                Tables\Filters\TernaryFilter::make('status')->label('نشط'),
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
            'index' => CompanyIntegrationResource\Pages\ListCompanyIntegrations::route('/'),
            'create' => CompanyIntegrationResource\Pages\CreateCompanyIntegration::route('/create'),
            'edit' => CompanyIntegrationResource\Pages\EditCompanyIntegration::route('/{record}/edit'),
            'view' => CompanyIntegrationResource\Pages\ViewCompanyIntegration::route('/{record}'),
        ];
    }
}
