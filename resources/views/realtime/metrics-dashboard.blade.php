{{-- resources/views/realtime/metrics-dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Real-Time Metrics Dashboard')

@push('styles')
<style>
    .metric-card {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s;
    }
    
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(99, 102, 241, 0.15);
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
    }
    
    .status-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    .status-active {
        background: #22c55e;
        box-shadow: 0 0 8px #22c55e;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">📊 Real-Time Metrics Dashboard</h1>
            <p class="text-slate-600">Monitor translation quality, latency, and user activity in real-time</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="metric-card">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-600 text-sm font-medium">Active Sessions</span>
                    <span class="status-indicator status-active"></span>
                </div>
                <div class="metric-value" id="active-sessions">{{ $metrics['active_sessions'] ?? 0 }}</div>
                <p class="text-xs text-slate-500 mt-2">+{{ $metrics['sessions_change'] ?? 0 }}% from last hour</p>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-600 text-sm font-medium">Avg Latency</span>
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="metric-value" id="avg-latency">{{ $metrics['avg_latency'] ?? 0 }}<span class="text-lg">ms</span></div>
                <p class="text-xs text-slate-500 mt-2">Target: &lt;500ms</p>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-600 text-sm font-medium">Total Participants</span>
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="metric-value" id="total-participants">{{ $metrics['total_participants'] ?? 0 }}</div>
                <p class="text-xs text-slate-500 mt-2">Across all sessions</p>
            </div>

            <div class="metric-card">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-600 text-sm font-medium">Audio Quality</span>
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <div class="metric-value" id="audio-quality">{{ $metrics['audio_quality'] ?? 0 }}<span class="text-lg">%</span></div>
                <p class="text-xs text-slate-500 mt-2">Average quality score</p>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Latency Timeline --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">⚡ Latency Timeline</h3>
                <div class="chart-container">
                    <canvas id="latency-chart"></canvas>
                </div>
            </div>

            {{-- Audio Quality Timeline --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">🎵 Audio Quality Timeline</h3>
                <div class="chart-container">
                    <canvas id="quality-chart"></canvas>
                </div>
            </div>
        </div>

        {{-- Real-Time Users Map & Session List --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Active Sessions List --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">🔴 Active Sessions</h3>
                <div class="space-y-3" id="active-sessions-list">
                    @forelse($activeSessions ?? [] as $session)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($session->public_id, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-900">{{ $session->title ?? 'Untitled Session' }}</h4>
                                <p class="text-sm text-slate-500">
                                    {{ strtoupper($session->source_language) }} → {{ strtoupper($session->target_language) }} • 
                                    {{ $session->participants_count ?? 0 }} participants
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-slate-900">{{ $session->avg_latency ?? 0 }}ms</div>
                            <div class="text-xs text-slate-500">{{ $session->turns_count ?? 0 }} turns</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p>No active sessions</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- System Health --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">💚 System Health</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600">WebSocket Server</span>
                            <span class="text-green-600 font-medium">Online</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600">API Response Time</span>
                            <span class="text-green-600 font-medium">{{ $metrics['api_response_time'] ?? 45 }}ms</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600">Database Load</span>
                            <span class="text-yellow-600 font-medium">{{ $metrics['db_load'] ?? 42 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 42%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600">Storage Usage</span>
                            <span class="text-blue-600 font-medium">{{ $metrics['storage_usage'] ?? 28 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 28%"></div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-200">
                        <h4 class="text-sm font-semibold text-slate-700 mb-2">Recent Events</h4>
                        <div class="space-y-2 text-xs text-slate-600">
                            <div class="flex items-start gap-2">
                                <span class="text-green-500">●</span>
                                <span>Session started: #abc123</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-blue-500">●</span>
                                <span>New participant joined</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-yellow-500">●</span>
                                <span>High latency detected: 850ms</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Latency Chart
const latencyCtx = document.getElementById('latency-chart').getContext('2d');
const latencyChart = new Chart(latencyCtx, {
    type: 'line',
    data: {
        labels: @json($latencyTimeline['labels'] ?? ['00:00', '00:05', '00:10', '00:15', '00:20']),
        datasets: [{
            label: 'Latency (ms)',
            data: @json($latencyTimeline['data'] ?? [320, 450, 380, 290, 410]),
            borderColor: 'rgb(99, 102, 241)',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + 'ms';
                    }
                }
            }
        }
    }
});

// Audio Quality Chart
const qualityCtx = document.getElementById('quality-chart').getContext('2d');
const qualityChart = new Chart(qualityCtx, {
    type: 'line',
    data: {
        labels: @json($qualityTimeline['labels'] ?? ['00:00', '00:05', '00:10', '00:15', '00:20']),
        datasets: [{
            label: 'Quality Score (%)',
            data: @json($qualityTimeline['data'] ?? [95, 92, 97, 94, 96]),
            borderColor: 'rgb(168, 85, 247)',
            backgroundColor: 'rgba(168, 85, 247, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Real-time updates (every 5 seconds)
setInterval(async () => {
    try {
        const response = await fetch('/api/realtime/metrics');
        const data = await response.json();
        
        // Update metric cards
        document.getElementById('active-sessions').textContent = data.active_sessions;
        document.getElementById('avg-latency').innerHTML = data.avg_latency + '<span class="text-lg">ms</span>';
        document.getElementById('total-participants').textContent = data.total_participants;
        document.getElementById('audio-quality').innerHTML = data.audio_quality + '<span class="text-lg">%</span>';
        
        // Update charts
        latencyChart.data.labels.push(data.current_time);
        latencyChart.data.datasets[0].data.push(data.current_latency);
        if (latencyChart.data.labels.length > 20) {
            latencyChart.data.labels.shift();
            latencyChart.data.datasets[0].data.shift();
        }
        latencyChart.update();
        
        qualityChart.data.labels.push(data.current_time);
        qualityChart.data.datasets[0].data.push(data.current_quality);
        if (qualityChart.data.labels.length > 20) {
            qualityChart.data.labels.shift();
            qualityChart.data.datasets[0].data.shift();
        }
        qualityChart.update();
        
    } catch (error) {
        console.error('Failed to update metrics:', error);
    }
}, 5000);
</script>
@endpush
@endsection
