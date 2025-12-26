@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Subscription & Usage</h1>
        <p class="text-gray-600 mt-2">Manage your subscription plan and monitor usage</p>
    </div>

    <!-- Current Plan -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-8 mb-8 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ ucfirst($subscription->subscription_tier) }} Plan</h2>
                <p class="text-blue-100 mb-4">Your current subscription</p>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ number_format($subscription->monthly_quota) }} translations/month</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $subscription->features['api_calls'] ?? 'Unlimited' }} API calls/month</span>
                    </div>
                    @if($subscription->features['white_label'] ?? false)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>White Label enabled</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold">${{ number_format($subscription->price, 0) }}</div>
                <div class="text-blue-100">per month</div>
                <button onclick="location.href='{{ route('partner.subscription.plans') }}'" 
                    class="mt-4 bg-white text-blue-600 px-6 py-2 rounded-lg hover:bg-blue-50 font-semibold">
                    Change Plan
                </button>
            </div>
        </div>
    </div>

    <!-- Usage Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-sm text-gray-600">Translations Used</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">
                        {{ number_format($usage['translations']) }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500">of {{ number_format($subscription->monthly_quota) }}</div>
                    <div class="text-sm font-semibold text-blue-600">
                        {{ number_format(($usage['translations'] / $subscription->monthly_quota) * 100, 1) }}%
                    </div>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" 
                    style="width: {{ min(($usage['translations'] / $subscription->monthly_quota) * 100, 100) }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-sm text-gray-600">API Calls</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">
                        {{ number_format($usage['api_calls']) }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500">this month</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                {{ number_format($usage['api_calls'] / max(date('d'), 1), 0) }} calls/day avg
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-sm text-gray-600">Revenue Generated</div>
                    <div class="text-2xl font-bold text-green-600 mt-1">
                        ${{ number_format($usage['revenue'], 2) }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500">this month</div>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                Commission: ${{ number_format($usage['commission'], 2) }}
            </div>
        </div>
    </div>

    <!-- Billing History -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Billing History</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($billingHistory as $bill)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $bill->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ ucfirst($bill->subscription_tier) }} Plan - Monthly
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ${{ number_format($bill->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                Paid
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="#" class="text-blue-600 hover:text-blue-800">Download</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            No billing history yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Next Billing -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="font-semibold text-gray-900">Next Billing Date</h3>
                <p class="text-gray-600 text-sm">{{ $subscription->next_billing_date->format('F d, Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-900">${{ number_format($subscription->price, 2) }}</div>
                <button class="text-sm text-red-600 hover:text-red-800 mt-1">Cancel Subscription</button>
            </div>
        </div>
    </div>
</div>
@endsection
