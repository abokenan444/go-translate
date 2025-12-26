<?php

namespace App\Filament\Resources\SubscriptionPlanResource\Pages;

use App\Filament\Resources\SubscriptionPlanResource;
use Filament\Resources\Pages\CreateRecord;
use Stripe\StripeClient;

class CreateSubscriptionPlan extends CreateRecord
{
    protected static string $resource = SubscriptionPlanResource::class;

    protected function afterCreate(): void
    {
        // Auto-create Stripe product and price if not provided
        if (empty($this->record->stripe_price_id) && $this->record->price > 0) {
            $this->createStripePrice();
        }
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

        } catch (\Exception $e) {
            \Log::error('Failed to create Stripe price: ' . $e->getMessage());
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
