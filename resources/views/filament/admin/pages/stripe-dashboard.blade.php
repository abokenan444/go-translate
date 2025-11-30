<x-filament::page>
    <div class="grid grid-cols-1 gap-6">
        <x-filament::section>
            <x-slot name="heading">Stripe Configuration</x-slot>
            <div class="text-sm">
                <div>Publishable Key: {{ env('STRIPE_KEY') ? 'Present' : 'Missing' }}</div>
                <div>Secret Key: {{ env('STRIPE_SECRET') ? 'Present' : 'Missing' }}</div>
                <div>Webhook Secret: {{ env('STRIPE_WEBHOOK_SECRET') ? 'Present' : 'Missing' }}</div>
                <div class="mt-2">
                    <a href="{{ url('/plans') }}" class="text-primary-600 hover:underline">View Pricing & Plans</a>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Recent Transactions</x-slot>
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament::page>
