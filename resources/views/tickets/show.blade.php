@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('tickets.index') }}" class="text-purple-600 hover:text-purple-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Tickets
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Ticket Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">{{ $ticket->subject }}</h1>
                        <p class="text-purple-100">Ticket #{{ $ticket->ticket_number }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusColors = [
                                'open' => 'bg-green-500',
                                'in_progress' => 'bg-blue-500',
                                'waiting_response' => 'bg-yellow-500',
                                'resolved' => 'bg-purple-500',
                                'closed' => 'bg-gray-500',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$ticket->status] ?? 'bg-gray-500' }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="p-6 border-b bg-gray-50">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Category</p>
                        <p class="font-semibold">{{ ucfirst($ticket->category) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Priority</p>
                        <p class="font-semibold">{{ ucfirst($ticket->priority) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="font-semibold">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-semibold">{{ $ticket->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Ticket Message -->
            <div class="p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Description</h3>
                <div class="bg-gray-50 rounded-lg p-4 whitespace-pre-wrap">{{ $ticket->message }}</div>

                @if($ticket->attachments && count($ticket->attachments) > 0)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Attachments</h4>
                        <div class="space-y-2">
                            @foreach($ticket->attachments as $attachment)
                                <a href="{{ Storage::url($attachment) }}" target="_blank" 
                                   class="flex items-center space-x-2 text-purple-600 hover:text-purple-700">
                                    <i class="fas fa-paperclip"></i>
                                    <span>{{ basename($attachment) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Admin Response -->
            @if($ticket->admin_response)
                <div class="p-6 bg-blue-50 border-t border-b">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-2">Support Team Response</h4>
                            <div class="bg-white rounded-lg p-4 whitespace-pre-wrap">{{ $ticket->admin_response }}</div>
                            @if($ticket->responded_at)
                                <p class="text-sm text-gray-600 mt-2">Responded: {{ $ticket->responded_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="p-6 bg-gray-50">
                @if($ticket->status !== 'closed')
                    <div class="flex justify-between items-center">
                        <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to close this ticket?')"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                                <i class="fas fa-times-circle mr-2"></i>Close Ticket
                            </button>
                        </form>

                        @if($ticket->status !== 'resolved')
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Our support team will respond within 24 hours
                            </p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-600">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            This ticket has been closed
                        </p>
                    </div>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
@endsection
