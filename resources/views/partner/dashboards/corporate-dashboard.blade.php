@extends('layouts.app')
@section('title', 'Corporate Dashboard')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Corporate Dashboard</h1>
        <p class="text-gray-600">Manage teams, budgets, and enterprise translation services</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Total Teams</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_teams'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Active departments</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Active Users</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_users'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Employee accounts</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Budget Used</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($stats['monthly_revenue'] ?? 0, 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">This month</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">API Calls</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['api_calls'] ?? 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $usage['remaining'] ?? 0 }} remaining</p>
        </div>
    </div>
    
    <!-- Usage Progress -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Quota Usage</h2>
        <div class="mb-2">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>{{ number_format($usage['used'] ?? 0) }} / {{ number_format($usage['quota'] ?? 0) }} translations</span>
                <span>{{ $usage['percentage'] ?? 0 }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ min($usage['percentage'] ?? 0, 100) }}%"></div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('partner.api-keys') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-indigo-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">API Integration</h3>
            <p class="text-sm text-gray-600 mt-1">Manage API keys & SSO</p>
        </a>
        
        <a href="{{ route('partner.subscription') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-blue-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">Enterprise Plan</h3>
            <p class="text-sm text-gray-600 mt-1">Manage subscription</p>
        </a>
        
        <a href="{{ route('partner.earnings.overview') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">Analytics & Reports</h3>
            <p class="text-sm text-gray-600 mt-1">View detailed insights</p>
        </a>
    </div>
    
    <!-- Recent Projects -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Recent Projects</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentProjects ?? [] as $project)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
                        <p class="text-sm text-gray-600">Team: {{ $project->metadata['team_name'] ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 text-sm rounded-full 
                            @if($project->status === 'completed') bg-green-100 text-green-800
                            @elseif($project->status === 'active') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($project->status) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $project->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No projects yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
