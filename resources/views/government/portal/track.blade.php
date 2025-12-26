@extends('layouts.app')

@section('title', 'Track Document - ' . $portal->country_name . ' Portal')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <nav class="text-sm text-blue-200 mb-2">
                <a href="{{ route('gov.directory') }}" class="hover:text-white">Portals</a>
                <span class="mx-2">›</span>
                <a href="{{ route('gov.portal.index', ['country' => strtolower($portal->country_code)]) }}" class="hover:text-white">{{ $portal->country_name }}</a>
                <span class="mx-2">›</span>
                <span>Track Document</span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-bold">Track Document Status</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(isset($document))
            {{-- Document Status Card --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 md:p-8">
                    {{-- Reference Header --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                        <div>
                            <p class="text-sm text-gray-500">Reference Number</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $document->reference_number }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'assigned' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-purple-100 text-purple-800',
                                    'review' => 'bg-indigo-100 text-indigo-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending Assignment',
                                    'assigned' => 'Assigned to Translator',
                                    'in_progress' => 'Translation in Progress',
                                    'review' => 'Under Review',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$document->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$document->status] ?? ucfirst($document->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Progress Timeline --}}
                    <div class="relative">
                        @php
                            $stages = [
                                ['key' => 'submitted', 'label' => 'Submitted', 'date' => $document->created_at],
                                ['key' => 'assigned', 'label' => 'Assigned', 'date' => $document->assigned_at],
                                ['key' => 'in_progress', 'label' => 'In Progress', 'date' => $document->started_at],
                                ['key' => 'review', 'label' => 'Review', 'date' => $document->review_started_at],
                                ['key' => 'completed', 'label' => 'Completed', 'date' => $document->completed_at],
                            ];
                            
                            $currentStageIndex = 0;
                            foreach ($stages as $i => $stage) {
                                if ($stage['date']) $currentStageIndex = $i;
                            }
                        @endphp
                        
                        <div class="flex items-center justify-between mb-4">
                            @foreach($stages as $i => $stage)
                                <div class="flex flex-col items-center flex-1 {{ $i < count($stages) - 1 ? 'relative' : '' }}">
                                    {{-- Connector line --}}
                                    @if($i < count($stages) - 1)
                                        <div class="absolute top-4 left-1/2 w-full h-1 {{ $i < $currentStageIndex ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                    @endif
                                    
                                    {{-- Circle --}}
                                    <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full 
                                        {{ $i <= $currentStageIndex ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                        @if($i < $currentStageIndex)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($i === $currentStageIndex)
                                            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                        @else
                                            <span class="text-xs">{{ $i + 1 }}</span>
                                        @endif
                                    </div>
                                    
                                    {{-- Label --}}
                                    <p class="mt-2 text-xs text-center font-medium {{ $i <= $currentStageIndex ? 'text-gray-900' : 'text-gray-400' }}">
                                        {{ $stage['label'] }}
                                    </p>
                                    @if($stage['date'])
                                        <p class="text-xs text-gray-400">{{ $stage['date']->format('M j, g:i A') }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Document Details --}}
                <div class="border-t bg-gray-50 p-6 md:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Details</h3>
                    
                    <dl class="grid md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Document Type</dt>
                            <dd class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Certification</dt>
                            <dd class="font-medium text-gray-900">{{ ucfirst($document->certification_type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Source Language</dt>
                            <dd class="font-medium text-gray-900">{{ strtoupper($document->source_lang) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Target Language</dt>
                            <dd class="font-medium text-gray-900">{{ strtoupper($document->target_lang) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Submitted</dt>
                            <dd class="font-medium text-gray-900">{{ $document->created_at->format('F j, Y g:i A') }}</dd>
                        </div>
                        @if($document->deadline_at)
                            <div>
                                <dt class="text-gray-500">Deadline</dt>
                                <dd class="font-medium {{ $document->deadline_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $document->deadline_at->format('F j, Y') }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Assignment Info --}}
                @if($document->status !== 'pending' && $document->status !== 'cancelled')
                    <div class="border-t p-6 md:p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment Information</h3>
                        
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Assigned Translator</p>
                                <p class="text-sm text-gray-500">
                                    Certified Professional (ID: {{ substr($document->reviewer_partner_id, 0, 8) }}...)
                                </p>
                            </div>
                        </div>

                        @if($document->assignment_attempts > 1)
                            <p class="mt-4 text-sm text-gray-500">
                                <span class="text-orange-600">Note:</span> This document was reassigned {{ $document->assignment_attempts - 1 }} time(s) to ensure quality.
                            </p>
                        @endif
                    </div>
                @endif

                {{-- Actions --}}
                <div class="border-t p-6 md:p-8 flex flex-wrap gap-4">
                    @if($document->status === 'completed' && $document->translated_file_path)
                        <a href="{{ route('documents.download', $document) }}" 
                           class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Translation
                        </a>
                        
                        @if($document->certificate_id)
                            <a href="{{ route('gov.portal.verify', ['country' => strtolower($portal->country_code), 'certificate' => $document->certificate_id]) }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                View Certificate
                            </a>
                        @endif
                    @endif
                    
                    @if(in_array($document->status, ['pending', 'assigned']))
                        <button onclick="cancelDocument('{{ $document->reference_number }}')"
                                class="inline-flex items-center px-6 py-3 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel Request
                        </button>
                    @endif
                </div>
            </div>

            {{-- Activity Log --}}
            @if($document->auditTrail && $document->auditTrail->count() > 0)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 md:p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Log</h3>
                        
                        <div class="space-y-4">
                            @foreach($document->auditTrail->sortByDesc('created_at') as $event)
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        @switch($event->action)
                                            @case('created')
                                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                                </svg>
                                                @break
                                            @case('assigned')
                                            @case('reassigned')
                                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"></path>
                                                </svg>
                                                @break
                                            @case('status_changed')
                                                <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                                </svg>
                                                @break
                                            @default
                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                        @endswitch
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $event->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $event->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @else
            {{-- Search Form --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="max-w-xl mx-auto text-center">
                        <svg class="mx-auto h-16 w-16 text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Track Your Document</h2>
                        <p class="text-gray-600 mb-6">
                            Enter your reference number to check the status of your translation request.
                        </p>

                        <form action="{{ route('gov.portal.track', ['country' => strtolower($portal->country_code)]) }}" method="GET">
                            <div class="flex gap-4">
                                <input type="text" 
                                       name="reference" 
                                       placeholder="Enter reference number"
                                       value="{{ request('reference') }}"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                <button type="submit" 
                                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                    Track
                                </button>
                            </div>
                        </form>

                        @if(request('reference') && !isset($document))
                            <div class="mt-6 p-4 bg-red-50 rounded-lg">
                                <p class="text-red-600">
                                    <svg class="inline w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    No document found with reference "{{ request('reference') }}". Please check the reference number and try again.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function cancelDocument(reference) {
    if (confirm('Are you sure you want to cancel this document request? This action cannot be undone.')) {
        // Submit cancellation form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/gov/{{ strtolower($portal->country_code) }}/cancel/${reference}`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
