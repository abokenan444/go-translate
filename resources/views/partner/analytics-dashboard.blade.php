@extends('layouts.app')
@section('title', 'Analytics Dashboard')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Advanced Analytics</h1>
            <p class="text-gray-600">Comprehensive insights into your partnership performance</p>
        </div>
        <div class="flex gap-3">
            <select id="period-selector" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month" selected>This Month</option>
                <option value="quarter">This Quarter</option>
                <option value="year">This Year</option>
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Export CSV
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Total API Calls</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($analytics['overview']['api_calls'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Translations</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($analytics['overview']['translations'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Characters</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($analytics['overview']['characters'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Revenue</p>
            <p class="text-2xl font-bold text-amber-600">${{ number_format($analytics['overview']['revenue'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Commission</p>
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($analytics['overview']['commission'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Usage Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Usage Trends</h2>
            <canvas id="usageTrendChart" height="250"></canvas>
        </div>

        <!-- Revenue Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Revenue Trends</h2>
            <canvas id="revenueTrendChart" height="250"></canvas>
        </div>
    </div>

    <!-- API Performance & Revenue Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- API Performance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">API Performance</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Success Rate</span>
                        <span class="text-sm font-semibold">{{ $analytics['api']['success_rate'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ $analytics['api']['success_rate'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Successful</p>
                        <p class="text-xl font-bold text-green-600">{{ number_format($analytics['api']['successful'] ?? 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Failed</p>
                        <p class="text-xl font-bold text-red-600">{{ number_format($analytics['api']['failed'] ?? 0) }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Avg Response Time</p>
                    <p class="text-xl font-bold text-blue-600">{{ $analytics['api']['avg_response_time'] ?? 0 }}ms</p>
                </div>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Revenue Breakdown</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                    <span class="text-sm font-medium">Pending</span>
                    <span class="text-lg font-bold text-yellow-600">${{ number_format($analytics['revenue']['pending']['amount'] ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                    <span class="text-sm font-medium">Approved</span>
                    <span class="text-lg font-bold text-blue-600">${{ number_format($analytics['revenue']['approved']['amount'] ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                    <span class="text-sm font-medium">Paid</span>
                    <span class="text-lg font-bold text-green-600">${{ number_format($analytics['revenue']['paid']['amount'] ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Endpoints & Performance Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Endpoints -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Top API Endpoints</h2>
            <div class="space-y-3">
                @forelse($analytics['api']['top_endpoints'] ?? [] as $endpoint)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">{{ $endpoint->endpoint }}</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($endpoint->count) }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Performance Metrics</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Avg Daily Translations</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($analytics['performance']['avg_daily_translations'] ?? 0) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Peak Day</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $analytics['performance']['peak_day']['date'] ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ number_format($analytics['performance']['peak_day']['count'] ?? 0) }} translations</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Avg Characters per Translation</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($analytics['performance']['avg_characters_per_translation'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Usage Trend Chart
const usageCtx = document.getElementById('usageTrendChart').getContext('2d');
new Chart(usageCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($analytics['trends']['labels'] ?? []) !!},
        datasets: [{
            label: 'Translations',
            data: {!! json_encode($analytics['trends']['translations'] ?? []) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Revenue Trend Chart
const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($analytics['trends']['labels'] ?? []) !!},
        datasets: [{
            label: 'Revenue ($)',
            data: {!! json_encode($analytics['trends']['revenue'] ?? []) !!},
            backgroundColor: 'rgba(16, 185, 129, 0.8)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endsection
