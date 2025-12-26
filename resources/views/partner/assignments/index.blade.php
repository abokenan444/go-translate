@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Assignments</h1>

    @foreach (['success','warning','info'] as $k)
        @if (session($k))
            <div class="mb-3 p-3 rounded bg-gray-100 border-l-4 border-{{ $k === 'success' ? 'green' : ($k === 'warning' ? 'yellow' : 'blue') }}-500">
                {{ session($k) }}
            </div>
        @endif
    @endforeach

    <div class="space-y-3">
        @forelse($assignments as $a)
            <div class="p-4 border rounded shadow-sm bg-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-lg">Document #{{ $a->document_id }}</div>
                        <div class="text-sm text-gray-600 mt-1">
                            <span class="inline-block mr-3">
                                <strong>Status:</strong> 
                                <span class="px-2 py-1 rounded text-xs {{ $a->status === 'offered' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($a->status) }}
                                </span>
                            </span>
                            @if($a->expires_at)
                                <span class="inline-block">
                                    <strong>Expires:</strong> {{ $a->expires_at->format('Y-m-d H:i:s') }}
                                    @if($a->status === 'offered')
                                        <span class="ml-1 text-red-600 font-semibold">
                                            ({{ $a->expires_at->diffForHumans() }})
                                        </span>
                                    @endif
                                </span>
                            @endif
                        </div>
                        @if($a->document)
                            <div class="text-xs text-gray-500 mt-2">
                                {{ $a->document->source_lang }} → {{ $a->document->target_lang }} | 
                                {{ ucfirst($a->document->document_type) }}
                            </div>
                        @endif
                    </div>

                    @if($a->status === 'offered')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('partner.assignments.accept', $a->id) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    ✓ Accept
                                </button>
                            </form>

                            <form method="POST" action="{{ route('partner.assignments.reject', $a->id) }}">
                                @csrf
                                <input type="hidden" name="reason" value="busy">
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                    ✗ Reject
                                </button>
                            </form>
                        </div>
                    @else
                        <span class="text-sm text-gray-500 italic">{{ ucfirst($a->status) }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <p class="text-lg">No assignments at the moment</p>
                <p class="text-sm mt-2">You'll receive a notification when a new document requires your review</p>
            </div>
        @endforelse
    </div>

    @if($assignments->hasPages())
        <div class="mt-6">
            {{ $assignments->links() }}
        </div>
    @endif
</div>
@endsection
