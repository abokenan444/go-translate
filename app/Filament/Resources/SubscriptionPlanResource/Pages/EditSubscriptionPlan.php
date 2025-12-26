<?php

namespace App\Filament\Resources\SubscriptionPlanResource\Pages;

use App\Filament\Resources\SubscriptionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Stripe\StripeClient;
use Filament\Notifications\Notification;

class EditSubscriptionPlan extends EditRecord
{
    protected static string $resource = SubscriptionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_stripe_price')
                ->label('Create Stripe Price')
                ->icon('heroicon-o-credit-card')
                ->color('success')
                ->visible(fn () => empty($this->record->stripe_price_id))
                ->action(function () {
                    $this->createStripePrice();
                })
                ->requiresConfirmation()
                ->modalHeading('Create Stripe Price')
                ->modalDescription('This will create a new product and price in your Stripe account.'),

            Actions\Action::make('sync_stripe')
                ->label('Sync with Stripe')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => !empty($this->record->stripe_price_id))
                ->action(function () {
                    $this->syncWithStripe();
                }),

            Actions\DeleteAction::make()
                ->before(function () {
                    // Check if plan has active subscriptions
                    if ($this->record->subscriptions()->where('status', 'active')->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Cannot Delete')
                            ->body('This plan has active subscriptions. Please cancel them first.')
                            ->send();
                        $this->halt();
                    }
                }),
        ];
    }

    protected function createStripePrice(): void
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Create product
            $product = $stripe->products->create([
                'name' => $this->record->name,
                'description' => $this->record->description ?? '',
            ]);

            // Create price
            $interval = match ($this->record->billing_cycle) {
                'yearly' => 'year',
                default => 'month',
            };

            $priceData = [
                'product' => $product->id,
                'unit_amount' => (int)($this->record->price * 100),
                'currency' => 'usd',
            ];

            if ($this->record->billing_cycle !== 'one-time') {
                $priceData['recurring'] = ['interval' => $interval];
            }

            $price = $stripe->prices->create($priceData);

            // Update the record
            $this->record->update([
                'stripe_product_id' => $product->id,
                'stripe_price_id' => $price->id,
            ]);

            Notification::make()
                ->success()
                ->title('Stripe Price Created')
                ->body("Price ID: {$price->id}")
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Stripe Error')
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function syncWithStripe(): void
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Update product name if we have product ID
            if ($this->record->stripe_product_id) {
                $stripe->products->update($this->record->stripe_product_id, [
                    'name' => $this->record->name,
                    'description' => $this->record->description ?? '',
                ]);
            }

            Notification::make()
                ->success()
                ->title('Synced with Stripe')
                ->body('Product information updated in Stripe.')
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Stripe Sync Error')
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
