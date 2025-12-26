@extends('layouts.government')

@section('title', __('Compliance Dashboard'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ __('Compliance & Governance Dashboard') }}</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['pending_verifications'] }}</div>
                    <div class="text-sm opacity-90">{{ __('Pending Verifications') }}</div>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['approved_today'] }}</div>
                    <div class="text-sm opacity-90">{{ __('Approved Today') }}</div>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['total_documents'] }}</div>
                    <div class="text-sm opacity-90">{{ __('Total Documents') }}</div>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['classified_documents'] }}</div>
                    <div class="text-sm opacity-90">{{ __('Classified Documents') }}</div>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('government.compliance.audit-trail') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <svg class="w-10 h-10 text-blue-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="font-bold text-gray-800">{{ __('Audit Trail') }}</div>
                    <div class="text-sm text-gray-500">{{ __('View complete evidence chain') }}</div>
                </div>
            </div>
        </a>

        <a href="{{ route('government.compliance.classifications') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <svg class="w-10 h-10 text-purple-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <div>
                    <div class="font-bold text-gray-800">{{ __('Classifications') }}</div>
                    <div class="text-sm text-gray-500">{{ __('Security level management') }}</div>
                </div>
            </div>
        </a>

        <a href="{{ route('government.compliance.reports') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <svg class="w-10 h-10 text-green-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <div class="font-bold text-gray-800">{{ __('Reports') }}</div>
                    <div class="text-sm text-gray-500">{{ __('Generate compliance reports') }}</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Pending Verifications -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __('Pending Verifications') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Document') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Priority') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Requested By') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendingVerifications as $verification)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($verification->document->title, 40) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ ucwords(str_replace('_', ' ', $verification->verification_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($verification->priority == 'urgent') bg-red-100 text-red-800
                                @elseif($verification->priority == 'high') bg-orange-100 text-orange-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($verification->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $verification->requestedBy->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $verification->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('government.compliance.show', $verification) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ __('Review') }} →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            {{ __('No pending verifications.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingVerifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pendingVerifications->links() }}
        </div>
        @endif
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __('Recent Verifications') }}</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recentVerifications as $verification)
                <div class="flex items-start border-l-4 
                    @if($verification->status == 'approved') border-green-500
                    @elseif($verification->status == 'rejected') border-red-500
                    @else border-yellow-500
                    @endif pl-4 py-2">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $verification->document->title }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ ucfirst($verification->status) }} by {{ $verification->verifier->name ?? 'System' }}
                            • {{ $verification->verified_at ? $verification->verified_at->diffForHumans() : $verification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($verification->status == 'approved') bg-green-100 text-green-800
                        @elseif($verification->status == 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($verification->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
