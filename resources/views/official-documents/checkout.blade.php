@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center text-green-600">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center bg-green-600 text-white font-bold">✓</div>
                        <div class="ml-2 text-sm font-medium">Upload</div>
                    </div>
                    <div class="w-24 h-1 bg-green-600 mx-4"></div>
                    <div class="flex items-center text-blue-600">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center bg-blue-600 text-white font-bold">2</div>
                        <div class="ml-2 text-sm font-medium">Payment</div>
                    </div>
                    <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                    <div class="flex items-center text-gray-400">
                        <div class="rounded-full h-12 w-12 flex items-center justify-center bg-gray-300 text-gray-600 font-bold">3</div>
                        <div class="ml-2 text-sm font-medium">Processing</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-2 text-gray-900">Complete Payment</h1>
            <p class="text-gray-600 mb-8">Review your order and proceed to secure payment</p>

            <!-- Order Summary -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border border-blue-100">
                <h2 class="text-xl font-bold mb-4 text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Order Summary
                </h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700 font-medium">Document Type:</span>
                        <span class="text-gray-900 font-semibold">{{ $order->document->document_type_name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700 font-medium">Translation:</span>
                        <span class="text-gray-900 font-semibold flex items-center gap-2">
                            <span class="px-2 py-1 bg-white rounded text-sm">{{ strtoupper($order->document->source_language) }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            <span class="px-2 py-1 bg-white rounded text-sm">{{ strtoupper($order->document->target_language) }}</span>
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700 font-medium">Processing Time:</span>
                        <span class="text-gray-900 font-semibold">24-48 hours</span>
                    </div>

                    <div class="border-t border-blue-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total Amount:</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $order->formatted_price }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What's Included -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="font-bold text-lg mb-4 text-gray-900">What's Included:</h3>
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700">Professional AI-assisted translation with human review</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700">Official Cultural Translate certification seal</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700">Unique certificate ID with QR code verification</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700">Certified legal translation statement</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700">Accepted by embassies and government offices</span>
                    </li>
                </ul>
            </div>

            <!-- Payment Method -->
            <div class="mb-6">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Payment Method</h3>
                <div class="border-2 border-blue-500 rounded-lg p-4 bg-blue-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900">Credit / Debit Card</p>
                                <p class="text-sm text-gray-600">Secure payment powered by Stripe</p>
                            </div>
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe" class="h-6">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('official.documents.my-documents') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                    ← Cancel
                </a>
                <form action="{{ route('official.documents.checkout.create', $order->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-10 py-4 rounded-lg font-bold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Proceed to Secure Payment
                    </button>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-center text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>SSL Encrypted | PCI Compliant | Your payment information is secure</span>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h3 class="font-bold mb-3 text-gray-900">What happens after payment?</h3>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
                <li>Your document will be immediately queued for processing</li>
                <li>Our AI system will translate your document with cultural accuracy</li>
                <li>A human reviewer will verify the translation quality</li>
                <li>The official seal and certificate will be applied</li>
                <li>You'll receive an email notification when it's ready (24-48 hours)</li>
                <li>Download your certified translation from your dashboard</li>
            </ol>
        </div>
    </div>
</div>
@endsection
