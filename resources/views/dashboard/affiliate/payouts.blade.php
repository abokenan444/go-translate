@extends('layouts.dashboard')

@section('title', 'Payouts')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">
        {{ __('Payouts') }}
    </h1>

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-wallet text-3xl opacity-80"></i>
                <span class="text-sm opacity-90">{{ __('Available') }}</span>
            </div>
            <h2 class="text-4xl font-bold mb-1">${{ number_format($affiliate->pending_balance, 2) }}</h2>
            <p class="text-sm opacity-90">{{ __('Ready to withdraw') }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-clock text-3xl opacity-80"></i>
                <span class="text-sm opacity-90">{{ __('Processing') }}</span>
            </div>
            <h2 class="text-4xl font-bold mb-1">${{ number_format($processingAmount ?? 0, 2) }}</h2>
            <p class="text-sm opacity-90">{{ __('In review') }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-check-circle text-3xl opacity-80"></i>
                <span class="text-sm opacity-90">{{ __('Total Paid') }}</span>
            </div>
            <h2 class="text-4xl font-bold mb-1">${{ number_format($affiliate->paid_balance, 2) }}</h2>
            <p class="text-sm opacity-90">{{ __('All time') }}</p>
        </div>
    </div>

    <!-- Request Payout Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            {{ __('Request Payout') }}
        </h2>
        
        @if($affiliate->pending_balance >= 50)
        <form action="{{ route('dashboard.affiliate.request-payout') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">{{ __('Amount') }} *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500">$</span>
                        <input type="number" name="amount" step="0.01" min="50" 
                               max="{{ $affiliate->pending_balance }}"
                               value="{{ $affiliate->pending_balance }}"
                               class="w-full pl-8 pr-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               required>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Minimum: $50.00') }}
                    </p>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">{{ __('Payment Method') }} *</label>
                    <select name="payment_method" 
                            class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                        <option value="">{{ __('Select method') }}</option>
                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                        <option value="paypal">{{ __('PayPal') }}</option>
                        <option value="wise">{{ __('Wise') }}</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">{{ __('Payment Details') }} *</label>
                <textarea name="payment_details" rows="4" required
                          class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                          placeholder="{{ __('Enter your payment details (bank account, PayPal email, etc.)') }}"></textarea>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-1 mr-3"></i>
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-semibold mb-1">{{ __('Important Information:') }}</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>{{ __('Minimum payout amount is $50.00') }}</li>
                            <li>{{ __('Payouts are processed within 5-7 business days') }}</li>
                            <li>{{ __('Make sure your payment details are correct') }}</li>
                            <li>{{ __('Processing fees may apply depending on the method') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>{{ __('Request Payout') }}
            </button>
        </form>
        @else
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('Minimum Balance Not Reached') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                {{ __('You need at least $50.00 to request a payout. Keep promoting to reach the minimum!') }}
            </p>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 mb-2">
                <div class="bg-green-600 h-4 rounded-full transition-all" 
                     style="width: {{ min(($affiliate->pending_balance / 50) * 100, 100) }}%"></div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                ${{ number_format($affiliate->pending_balance, 2) }} / $50.00 
                ({{ number_format(min(($affiliate->pending_balance / 50) * 100, 100), 1) }}%)
            </p>
        </div>
        @endif
    </div>

    <!-- Payout History -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
            {{ __('Payout History') }}
        </h2>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-400">{{ __('Date') }}</th>
                        <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-400">{{ __('Method') }}</th>
                        <th class="text-right py-3 px-4 text-gray-600 dark:text-gray-400">{{ __('Amount') }}</th>
                        <th class="text-center py-3 px-4 text-gray-600 dark:text-gray-400">{{ __('Status') }}</th>
                        <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-400">{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-3 px-4 text-gray-900 dark:text-white">
                            {{ $payout->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4 text-gray-900 dark:text-white">
                            <span class="capitalize">{{ str_replace('_', ' ', $payout->payment_method) }}</span>
                        </td>
                        <td class="text-right py-3 px-4 font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($payout->amount, 2) }}
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($payout->status === 'paid')
                                <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>{{ __('Paid') }}
                                </span>
                            @elseif($payout->status === 'pending')
                                <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-3 py-1 rounded-full text-sm font-semibold">
                                    <i class="fas fa-clock mr-1"></i>{{ __('Pending') }}
                                </span>
                            @elseif($payout->status === 'processing')
                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-semibold">
                                    <i class="fas fa-spinner mr-1"></i>{{ __('Processing') }}
                                </span>
                            @else
                                <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-3 py-1 rounded-full text-sm font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i>{{ __('Rejected') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400 text-sm">
                            {{ $payout->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
                            <p class="text-lg">{{ __('No payout history yet') }}</p>
                            <p class="text-sm">{{ __('Your payout requests will appear here') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payouts->hasPages())
        <div class="mt-6">
            {{ $payouts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
