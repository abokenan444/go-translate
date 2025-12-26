@extends('layouts.partner')

@section('title', __('Performance Dashboard'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ __('Performance Dashboard') }}</h1>

    <!-- Current Performance Summary -->
    @if($latestPerformance)
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 text-white mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="text-center">
                <div class="text-4xl font-bold">{{ number_format($latestPerformance->overall_score, 1) }}</div>
                <div class="text-sm opacity-90">{{ __('Overall Score') }}</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ number_format($latestPerformance->quality_score, 1) }}</div>
                <div class="text-sm opacity-90">{{ __('Quality') }} (40%)</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ number_format($latestPerformance->speed_score, 1) }}</div>
                <div class="text-sm opacity-90">{{ __('Speed') }} (25%)</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ number_format($latestPerformance->reliability_score, 1) }}</div>
                <div class="text-sm opacity-90">{{ __('Reliability') }} (25%)</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ number_format($latestPerformance->communication_score, 1) }}</div>
                <div class="text-sm opacity-90">{{ __('Communication') }} (10%)</div>
            </div>
        </div>
        <div class="mt-4 text-center text-sm opacity-90">
            {{ __('Period') }}: {{ $latestPerformance->period_start->format('M d') }} - {{ $latestPerformance->period_end->format('M d, Y') }}
        </div>
    </div>
    @endif

    <!-- Current Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-800">{{ $currentStats['total_documents'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Total Documents') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-800">{{ $currentStats['completed_documents'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Completed') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-800">{{ $currentStats['in_progress'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('In Progress') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-800">{{ $currentStats['pending'] }}</div>
                    <div class="text-sm text-gray-500">{{ __('Pending') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    @if($performanceHistory->count() > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('Performance Trend') }}</h2>
        <canvas id="performanceChart" height="80"></canvas>
    </div>
    @endif

    <!-- Recent Payouts -->
    @if($recentPayouts->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('Recent Payouts') }}</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Method') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentPayouts as $payout)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $payout->currency }} {{ number_format($payout->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ucwords(str_replace('_', ' ', $payout->payment_method)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($payout->status == 'completed') bg-green-100 text-green-800
                                @elseif($payout->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($payout->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payout->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('partner.payouts.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                {{ __('View All Payouts') }} →
            </a>
        </div>
    </div>
    @endif
</div>

@if($performanceHistory->count() > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const history = @json($performanceHistory);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: history.map(h => new Date(h.period_end).toLocaleDateString('en-US', { month: 'short', year: 'numeric' })).reverse(),
            datasets: [
                {
                    label: 'Overall Score',
                    data: history.map(h => h.overall_score).reverse(),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Quality',
                    data: history.map(h => h.quality_score).reverse(),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Speed',
                    data: history.map(h => h.speed_score).reverse(),
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
@endif
@endsection
