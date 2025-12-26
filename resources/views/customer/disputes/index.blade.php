@extends('layouts.customer')

@section('title', __('My Disputes'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('My Disputes') }}</h1>
        <a href="{{ route('customer.disputes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
            {{ __('Open New Dispute') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">{{ __('Open') }}</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $disputes->where('status', 'open')->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">{{ __('Under Review') }}</div>
            <div class="text-2xl font-bold text-blue-600">{{ $disputes->where('status', 'under_review')->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">{{ __('Investigating') }}</div>
            <div class="text-2xl font-bold text-purple-600">{{ $disputes->where('status', 'investigating')->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">{{ __('Resolved') }}</div>
            <div class="text-2xl font-bold text-green-600">{{ $disputes->where('status', 'resolved')->count() }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Document') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Priority') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Created') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($disputes as $dispute)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($dispute->document->title, 30) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ ucwords(str_replace('_', ' ', $dispute->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($dispute->priority == 'critical') bg-red-100 text-red-800
                                @elseif($dispute->priority == 'high') bg-orange-100 text-orange-800
                                @elseif($dispute->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($dispute->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($dispute->status == 'resolved') bg-green-100 text-green-800
                                @elseif($dispute->status == 'investigating') bg-purple-100 text-purple-800
                                @elseif($dispute->status == 'under_review') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $dispute->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $dispute->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('customer.disputes.show', $dispute) }}" class="text-blue-600 hover:text-blue-800">
                                {{ __('View Details') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            {{ __('No disputes found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $disputes->links() }}
    </div>
</div>
@endsection
