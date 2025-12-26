@extends('layouts.app')
@section('title', 'University Dashboard')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $university->name }}</h1>
        <p class="text-gray-600">University Dashboard - Manage students and translation services</p>
    </div>
    
    @if(!$university->is_verified)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Your university account is pending verification. You will be able to add students once approved.
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Total Students</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_students'] }}</p>
            <p class="text-xs text-gray-500 mt-1">of {{ $stats['max_students'] }} max</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Active Departments</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['active_departments'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Using service</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">Monthly Usage</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['monthly_usage']) }}</p>
            <p class="text-xs text-gray-500 mt-1">translations</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-1">API Calls</p>
            <p class="text-2xl font-bold text-amber-600">{{ number_format($stats['api_calls']) }}</p>
            <p class="text-xs text-gray-500 mt-1">this month</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('university.students') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-purple-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">Manage Students</h3>
            <p class="text-sm text-gray-600 mt-1">Add or remove students</p>
        </a>
        
        <a href="{{ route('university.subscription') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-blue-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">Subscription Plan</h3>
            <p class="text-sm text-gray-600 mt-1">Manage your plan</p>
        </a>
        
        <a href="{{ route('partner.api-keys') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition text-center">
            <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
            <h3 class="font-semibold text-gray-900">API Integration</h3>
            <p class="text-sm text-gray-600 mt-1">API keys & docs</p>
        </a>
    </div>
    
    <!-- Student Limit Progress -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Student Capacity</h2>
        <div class="mb-2">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>{{ $stats['total_students'] }} / {{ $stats['max_students'] }} students</span>
                <span>{{ round(($stats['total_students'] / max($stats['max_students'], 1)) * 100, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-purple-600 h-3 rounded-full" style="width: {{ min(($stats['total_students'] / max($stats['max_students'], 1)) * 100, 100) }}%"></div>
            </div>
        </div>
        @if($stats['total_students'] >= $stats['max_students'] * 0.8)
        <p class="text-sm text-amber-600 mt-2">
            You're approaching your student limit. Consider upgrading your plan.
        </p>
        @endif
    </div>
</div>
@endsection
