@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <- Backup created automatically with full Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Track Your Shipment</h1>
                <p class="text-lg text-gray-600">Enter your certificate ID to track your physical document delivery</p>
            </div>

            <- Backup created automatically with full Search Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <form method="GET" action="{{ route('shipping.track') }}" class="flex gap-4">
                    <div class="flex-1">
                        <label for="certificate_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Certificate ID
                        </label>
                        <input 
                            type="text" 
                            name="certificate_id" 
                            id="certificate_id" 
                            value="{{ request('certificate_id') }}"
                            placeholder="e.g., CERT-2024-XXXXX"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                    </div>
                    <div class="flex items-end">
                        <button 
                            type="submit" 
                            class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Track
                        </button>
                    </div>
                </form>
            </div>

            @if($document)
                <- Backup created automatically with full Document Info Card -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Shipment Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Certificate ID</p>
                                <p class="text-lg font-mono font-semibold text-gray-900">{{ $document->certificate_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Document Type</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $document->document_type_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Current Status</p>
                                @php
                                    $statusColors = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                        'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Processing'],
                                        'printed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Printed'],
                                        'shipped' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'label' => 'Shipped'],
                                        'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Delivered'],
                                    ];
                                    $status = $statusColors[$document->shipping_status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($document->shipping_status)];
                                @endphp
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </div>
                            @if($document->tracking_number)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tracking Number</p>
                                <p class="text-lg font-mono font-semibold text-blue-600">{{ $document->tracking_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <- Backup created automatically with full Shipping Address -->
                    @if($document->shipping_address)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Shipping Address</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $document->shipping_address }}</p>
                        </div>
                    </div>
                    @endif

                    <- Backup created automatically with full Timeline -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Shipment Timeline</h3>
                        <div class="space-y-6">
                            @if($document->created_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-8">
                                    <p class="text-lg font-semibold text-gray-900">Order Placed</p>
                                    <p class="text-sm text-gray-500">{{ $document->created_at->format('M d, Y - h:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($document->paid_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-8">
                                    <p class="text-lg font-semibold text-gray-900">Payment Confirmed</p>
                                    <p class="text-sm text-gray-500">{{ $document->paid_at->format('M d, Y - h:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($document->printed_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-8">
                                    <p class="text-lg font-semibold text-gray-900">Document Printed</p>
                                    <p class="text-sm text-gray-500">{{ $document->printed_at->format('M d, Y - h:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($document->shipped_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="w-0.5 h-full bg-gray-300 mt-2"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-8">
                                    <p class="text-lg font-semibold text-gray-900">Shipped</p>
                                    <p class="text-sm text-gray-500">{{ $document->shipped_at->format('M d, Y - h:i A') }}</p>
                                    @if($document->tracking_number)
                                    <p class="text-sm text-gray-600 mt-1">Tracking: <span class="font-mono font-semibold">{{ $document->tracking_number }}</span></p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($document->delivered_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg font-semibold text-green-600">Delivered Successfully</p>
                                    <p class="text-sm text-gray-500">{{ $document->delivered_at->format('M d, Y - h:i A') }}</p>
                                </div>
                            </div>
                            @else
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg font-semibold text-gray-500">Awaiting Delivery</p>
                                    <p class="text-sm text-gray-400">Your document will be delivered soon</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif(request('certificate_id'))
                <- Backup created automatically with full Not Found Message -->
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Shipment Found</h3>
                    <p class="text-gray-600">We couldn't find a physical copy order with this certificate ID.</p>
                    <p class="text-sm text-gray-500 mt-2">Please check the ID and try again, or contact support if you need assistance.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
