@extends('layouts.app')

@section('title', 'Compliance Report')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header with Date Range Picker -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold text-blue-900">📊 Compliance Report</h1>
                <p class="text-gray-600 mt-1">Generate comprehensive compliance reports for your government entity</p>
            </div>
        </div>

        <!-- Date Range Picker Form -->
        <form method="GET" action="{{ route('government.compliance-report') }}" class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ request('end_date', now()->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Update Report
                    </button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quick Ranges</label>
                    <div class="flex gap-2">
                        <button type="button" 
                                onclick="setDateRange(7)"
                                class="px-3 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-md transition">
                            7 Days
                        </button>
                        <button type="button" 
                                onclick="setDateRange(30)"
                                class="px-3 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-md transition">
                            30 Days
                        </button>
                        <button type="button" 
                                onclick="setDateRange(90)"
                                class="px-3 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-md transition">
                            90 Days
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Current Report Info & Export Buttons -->
        <div class="flex justify-between items-center border-t pt-4">
            <div>
                <p class="text-sm text-gray-600">
                    Showing data from <strong>{{ $start_date->format('M j, Y') }}</strong> to <strong>{{ $end_date->format('M j, Y') }}</strong>
                </p>
                <p class="text-xs text-gray-500 mt-1">Generated: {{ $report_generated_at->format('F j, Y H:i:s') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="?format=pdf&start_date={{ $start_date->format('Y-m-d') }}&end_date={{ $end_date->format('Y-m-d') }}" 
                   class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <a href="?format=csv&start_date={{ $start_date->format('Y-m-d') }}&end_date={{ $end_date->format('Y-m-d') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Total Documents -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-100 uppercase font-semibold">Total Documents</p>
                    <p class="text-4xl font-bold mt-2">{{ number_format($total_documents) }}</p>
                    <p class="text-xs text-blue-100 mt-2">Submitted in period</p>
                </div>
                <div class="text-blue-200 opacity-50">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path>
                        <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Certificates Issued -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-100 uppercase font-semibold">Certificates Issued</p>
                    <p class="text-4xl font-bold mt-2">{{ number_format($certificates_issued) }}</p>
                    <p class="text-xs text-green-100 mt-2">Successfully certified</p>
                </div>
                <div class="text-green-200 opacity-50">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Compliance Rate -->
        <div class="bg-gradient-to-br {{ $compliance_rate >= 90 ? 'from-emerald-500 to-emerald-600' : 'from-orange-500 to-orange-600' }} text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm {{ $compliance_rate >= 90 ? 'text-emerald-100' : 'text-orange-100' }} uppercase font-semibold">Compliance Rate</p>
                    <p class="text-4xl font-bold mt-2">{{ $compliance_rate }}%</p>
                    <p class="text-xs {{ $compliance_rate >= 90 ? 'text-emerald-100' : 'text-orange-100' }} mt-2">
                        {{ $compliance_rate >= 90 ? 'Excellent performance' : 'Needs improvement' }}
                    </p>
                </div>
                <div class="{{ $compliance_rate >= 90 ? 'text-emerald-200' : 'text-orange-200' }} opacity-50">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Avg Processing Time -->
        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 uppercase font-semibold">Avg Processing Time</p>
                    <p class="text-3xl font-bold text-blue-900 mt-1">{{ $avg_processing_hours }}h</p>
                    <p class="text-xs text-gray-500 mt-1">Hours to complete</p>
                </div>
                <div class="text-blue-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Disputes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-2 {{ $total_disputes == 0 ? 'border-green-100' : 'border-orange-100' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 uppercase font-semibold">Total Disputes</p>
                    <p class="text-3xl font-bold {{ $total_disputes == 0 ? 'text-green-600' : 'text-orange-600' }} mt-1">
                        {{ number_format($total_disputes) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Active disputes</p>
                </div>
                <div class="{{ $total_disputes == 0 ? 'text-green-500' : 'text-orange-500' }}">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Dispute Rate -->
        <div class="bg-white rounded-lg shadow-md p-6 border-2 {{ $dispute_rate < 5 ? 'border-green-100' : 'border-orange-100' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 uppercase font-semibold">Dispute Rate</p>
                    <p class="text-3xl font-bold {{ $dispute_rate < 5 ? 'text-green-600' : 'text-orange-600' }} mt-1">
                        {{ $dispute_rate }}%
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Of total certificates</p>
                </div>
                <div class="{{ $dispute_rate < 5 ? 'text-green-500' : 'text-orange-500' }}">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents by Status Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Documents by Status
        </h2>
        <div class="space-y-4">
            @php
                $maxCount = count($documents_by_status) > 0 ? max(array_values($documents_by_status)) : 1;
                $statusColors = [
                    'pending' => 'bg-yellow-500',
                    'processing' => 'bg-blue-500',
                    'completed' => 'bg-green-500',
                    'failed' => 'bg-red-500',
                    'reviewing' => 'bg-purple-500'
                ];
            @endphp
            @forelse($documents_by_status as $status => $count)
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                        <span class="text-xs text-gray-500">{{ $maxCount > 0 ? round(($count / $maxCount) * 100) : 0 }}%</span>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                    <div class="{{ $statusColors[$status] ?? 'bg-gray-500' }} h-4 rounded-full transition-all duration-500 flex items-center justify-end pr-2" 
                         style="width: {{ $maxCount > 0 ? ($count / $maxCount * 100) : 0 }}%;">
                        @if($count > 0 && $maxCount > 0 && ($count / $maxCount * 100) > 10)
                            <span class="text-xs text-white font-bold">{{ $count }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
                <p class="text-gray-500 text-center py-4">No document status data available</p>
            @endforelse
        </div>
    </div>

    <!-- Documents by Type -->
    @if($documents_by_type->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Top Document Types
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Visual</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($documents_by_type as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-400">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ ucwords(str_replace('_', ' ', $item->document_type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold">
                            {{ number_format($item->count) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ $total_documents > 0 ? round(($item->count / $total_documents) * 100, 1) : 0 }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="w-24 bg-gray-200 rounded-full h-2 inline-block">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $total_documents > 0 ? ($item->count / $total_documents * 100) : 0 }}%;"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Ledger Events -->
    @if(!empty($ledger_events))
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Decision Ledger Activity
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($ledger_events as $eventType => $count)
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">{{ ucwords(str_replace('_', ' ', $eventType)) }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($count) }}</p>
                    </div>
                    <div class="text-gray-400">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Certificates -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            Recent Certificates (Last 50)
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cert ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Legal Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issued At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recent_certificates as $cert)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-blue-600">
                            #{{ $cert->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                            {{ $cert->document_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'valid' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'frozen' => 'bg-orange-100 text-orange-800',
                                    'revoked' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$cert->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($cert->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $legalStatus = $cert->legal_status ?? 'valid';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$legalStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($legalStatus) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $cert->issued_at?->format('M j, Y H:i') }}
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No certificates found in this period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function setDateRange(days) {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(endDate.getDate() - days);
    
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
}
</script>
@endsection
