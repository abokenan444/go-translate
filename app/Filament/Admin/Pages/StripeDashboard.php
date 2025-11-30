<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Tables; 
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\PaymentTransaction;

class StripeDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Stripe Dashboard';
    protected static ?string $navigationGroup = 'Billing';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.admin.pages.stripe-dashboard';

    public static function getRoutePath(): string
    {
        return '/stripe';
    }

    protected function getTableQuery()
    {
        return PaymentTransaction::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('provider')->label('Provider'),
            Tables\Columns\TextColumn::make('type')->label('Type'),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
            Tables\Columns\TextColumn::make('amount')->label('Amount'),
            Tables\Columns\TextColumn::make('currency')->label('Currency'),
            Tables\Columns\TextColumn::make('provider_id')->label('Stripe ID')->copyable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created'),
        ];
    }

    public function getHeading(): string
    {
        return 'Stripe Overview';
    }

    public function getSubheading(): string
    {
        $key = env('STRIPE_KEY');
        $secretSet = env('STRIPE_SECRET') ? 'Yes' : 'No';
        $webhookSet = env('STRIPE_WEBHOOK_SECRET') ? 'Yes' : 'No';
        return "Key: " . ($key ? 'Present' : 'Missing') . " | Secret: $secretSet | Webhook: $webhookSet";
    }
}
