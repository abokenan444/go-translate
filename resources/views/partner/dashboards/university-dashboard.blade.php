@extends('layouts.app')
@section('title', 'University Dashboard')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">University Dashboard</h1>
        <p class="text-gray-600">Manage students, departments, and research translations</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Total Students</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_students'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Active users</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Active Departments</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['active_departments'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Using service</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Research Projects</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['total_projects'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stats['active_projects'] ?? 0 }} active</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Monthly Usage</p>
            <p class="text-2xl font-bold text-amber-600">{{ number_format($stats['monthly_translations'] ?? 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">translations</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('partner.api-keys') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-purple-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">API Integration</h3>
            <p class="text-sm text-gray-600 mt-1">Manage API keys</p>
        </a>
        
        <a href="{{ route('partner.subscription') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-blue-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">Subscription Plan</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $usage['used'] ?? 0 }}/{{ $usage['quota'] ?? 0 }} used</p>
        </a>
        
        <a href="{{ route('partner.earnings.overview') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">Earnings & Reports</h3>
            <p class="text-sm text-gray-600 mt-1">${{ number_format($stats['monthly_commission'] ?? 0, 2) }} this month</p>
        </a>
    </div>
    
    <!-- Recent Projects -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Recent Research Projects</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentProjects ?? [] as $project)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
                        <p class="text-sm text-gray-600">Department: {{ $project->metadata['department'] ?? 'N/A' }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full 
                        @if($project->status === 'completed') bg-green-100 text-green-800
                        @elseif($project->status === 'active') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No projects yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
