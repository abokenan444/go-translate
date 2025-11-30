<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static string $view = 'filament.admin.pages.settings';
    
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $navigationGroup = 'Settings';
    
    protected static ?string $title = 'Website Settings';
    
    protected static ?int $navigationSort = 100;
    
    protected static ?string $slug = 'settings-page';

    public ?array $data = [];

    public static function getRoutePath(): string
    {
        return '/settings-page';
    }

    public function mount(): void
    {
        $this->form->fill($this->getSettingsData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('site_email')
                                    ->label('Site Email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('site_phone')
                                    ->label('Site Phone')
                                    ->tel(),
                                Forms\Components\Textarea::make('site_description')
                                    ->label('Site Description')
                                    ->rows(3),
                                Forms\Components\TextInput::make('site_url')
                                    ->label('Site URL')
                                    ->url()
                                    ->required(),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Translation')
                            ->icon('heroicon-o-language')
                            ->schema([
                                Forms\Components\Select::make('default_language')
                                    ->label('Default Language')
                                    ->options([
                                        'en' => 'English',
                                        'ar' => 'Arabic',
                                        'fr' => 'French',
                                        'es' => 'Spanish',
                                        'de' => 'German',
                                        'it' => 'Italian',
                                        'pt' => 'Portuguese',
                                        'ru' => 'Russian',
                                        'zh' => 'Chinese',
                                        'ja' => 'Japanese',
                                        'ko' => 'Korean',
                                        'tr' => 'Turkish',
                                        'nl' => 'Dutch',
                                    ])
                                    ->required()
                                    ->default('en'),
                                Forms\Components\Select::make('ai_model')
                                    ->label('AI Model')
                                    ->options([
                                        'gpt-4' => 'GPT-4',
                                        'gpt-4-turbo' => 'GPT-4 Turbo',
                                        'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                                    ])
                                    ->required()
                                    ->default('gpt-4'),
                                Forms\Components\TextInput::make('openai_api_key')
                                    ->label('OpenAI API Key')
                                    ->password()
                                    ->revealable(),
                                Forms\Components\TextInput::make('max_words_per_translation')
                                    ->label('Max Words Per Translation')
                                    ->numeric()
                                    ->default(5000),
                                Forms\Components\TextInput::make('translation_timeout')
                                    ->label('Translation Timeout (seconds)')
                                    ->numeric()
                                    ->default(120),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Subscription')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Forms\Components\Toggle::make('subscriptions_enabled')
                                    ->label('Enable Subscriptions')
                                    ->default(true),
                                Forms\Components\Select::make('default_currency')
                                    ->label('Default Currency')
                                    ->options([
                                        'USD' => 'US Dollar (USD)',
                                        'EUR' => 'Euro (EUR)',
                                        'GBP' => 'British Pound (GBP)',
                                        'SAR' => 'Saudi Riyal (SAR)',
                                        'AED' => 'UAE Dirham (AED)',
                                    ])
                                    ->required()
                                    ->default('USD'),
                                Forms\Components\TextInput::make('free_trial_days')
                                    ->label('Free Trial Days')
                                    ->numeric()
                                    ->default(7),
                                Forms\Components\TextInput::make('free_trial_translations')
                                    ->label('Free Trial Translations')
                                    ->numeric()
                                    ->default(10),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Payment')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Forms\Components\Toggle::make('stripe_enabled')
                                    ->label('Enable Stripe')
                                    ->default(false),
                                Forms\Components\TextInput::make('stripe_public_key')
                                    ->label('Stripe Public Key')
                                    ->password()
                                    ->revealable(),
                                Forms\Components\TextInput::make('stripe_secret_key')
                                    ->label('Stripe Secret Key')
                                    ->password()
                                    ->revealable(),
                                Forms\Components\Toggle::make('paypal_enabled')
                                    ->label('Enable PayPal')
                                    ->default(false),
                                Forms\Components\TextInput::make('paypal_client_id')
                                    ->label('PayPal Client ID')
                                    ->password()
                                    ->revealable(),
                                Forms\Components\TextInput::make('paypal_secret')
                                    ->label('PayPal Secret')
                                    ->password()
                                    ->revealable(),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Email')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Forms\Components\Select::make('mail_driver')
                                    ->label('Mail Driver')
                                    ->options([
                                        'smtp' => 'SMTP',
                                        'sendmail' => 'Sendmail',
                                        'mailgun' => 'Mailgun',
                                        'ses' => 'Amazon SES',
                                    ])
                                    ->required()
                                    ->default('smtp'),
                                Forms\Components\TextInput::make('mail_host')
                                    ->label('Mail Host')
                                    ->default('smtp.gmail.com'),
                                Forms\Components\TextInput::make('mail_port')
                                    ->label('Mail Port')
                                    ->numeric()
                                    ->default(587),
                                Forms\Components\TextInput::make('mail_username')
                                    ->label('Mail Username')
                                    ->email(),
                                Forms\Components\TextInput::make('mail_password')
                                    ->label('Mail Password')
                                    ->password()
                                    ->revealable(),
                                Forms\Components\Select::make('mail_encryption')
                                    ->label('Mail Encryption')
                                    ->options([
                                        'tls' => 'TLS',
                                        'ssl' => 'SSL',
                                    ])
                                    ->default('tls'),
                                Forms\Components\TextInput::make('mail_from_address')
                                    ->label('Mail From Address')
                                    ->email(),
                                Forms\Components\TextInput::make('mail_from_name')
                                    ->label('Mail From Name'),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Security')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Toggle::make('two_factor_enabled')
                                    ->label('Enable Two-Factor Authentication')
                                    ->default(false),
                                Forms\Components\Toggle::make('force_https')
                                    ->label('Force HTTPS')
                                    ->default(true),
                                Forms\Components\TextInput::make('session_lifetime')
                                    ->label('Session Lifetime (minutes)')
                                    ->numeric()
                                    ->default(120),
                                Forms\Components\TextInput::make('password_min_length')
                                    ->label('Minimum Password Length')
                                    ->numeric()
                                    ->default(8),
                                Forms\Components\Toggle::make('password_require_uppercase')
                                    ->label('Require Uppercase in Password')
                                    ->default(true),
                                Forms\Components\Toggle::make('password_require_numbers')
                                    ->label('Require Numbers in Password')
                                    ->default(true),
                                Forms\Components\Toggle::make('password_require_symbols')
                                    ->label('Require Symbols in Password')
                                    ->default(false),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getSettingsData(): array
    {
        if (!Schema::hasTable('settings')) {
            $settings = [];
        } else {
            $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        }
        
        // Default values if settings don't exist
        return array_merge([
            'site_name' => 'Cultural Translate',
            'site_email' => 'info@culturaltranslate.com',
            'site_phone' => '',
            'site_description' => 'AI-Powered Cultural Translation Platform',
            'site_url' => 'https://culturaltranslate.com',
            'default_language' => 'en',
            'ai_model' => 'gpt-4',
            'openai_api_key' => env('OPENAI_API_KEY', ''),
            'max_words_per_translation' => 5000,
            'translation_timeout' => 120,
            'subscriptions_enabled' => true,
            'default_currency' => 'USD',
            'free_trial_days' => 7,
            'free_trial_translations' => 10,
            'stripe_enabled' => false,
            'stripe_public_key' => '',
            'stripe_secret_key' => '',
            'paypal_enabled' => false,
            'paypal_client_id' => '',
            'paypal_secret' => '',
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 587,
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => '',
            'mail_from_name' => '',
            'two_factor_enabled' => false,
            'force_https' => true,
            'session_lifetime' => 120,
            'password_min_length' => 8,
            'password_require_uppercase' => true,
            'password_require_numbers' => true,
            'password_require_symbols' => false,
        ], $settings);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (!Schema::hasTable('settings')) {
            Notification::make()->danger()->title('Settings table missing - run migrations.')->send();
            return;
        }

        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => is_bool($value) ? ($value ? '1' : '0') : $value,
                    'updated_at' => now(),
                ]
            );
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully!')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
