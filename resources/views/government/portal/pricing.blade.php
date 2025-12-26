@extends('layouts.app')

@section('title', 'Pricing - ' . $portal->country_name . ' Portal')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <nav class="text-sm text-blue-200 mb-4">
                <a href="{{ route('gov.directory') }}" class="hover:text-white">Portals</a>
                <span class="mx-2">›</span>
                <a href="{{ route('gov.portal.index', ['country' => strtolower($portal->country_code)]) }}" class="hover:text-white">{{ $portal->country_name }}</a>
                <span class="mx-2">›</span>
                <span>Pricing</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-bold">Translation Pricing</h1>
            <p class="text-blue-100 mt-2 text-lg">Transparent pricing for {{ $portal->country_name }} official document translations</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Pricing Tiers --}}
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            {{-- Standard --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Standard</h3>
                    <p class="text-sm text-gray-500 mt-1">5-7 business days</p>
                    <div class="mt-4">
                        <span class="text-4xl font-bold text-gray-900">$0.12</span>
                        <span class="text-gray-500">/word</span>
                    </div>
                    <ul class="mt-6 space-y-3 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Certified Translation
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Certificate of Accuracy
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Digital Delivery
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            1 Revision Included
                        </li>
                    </ul>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('gov.portal.submit', ['country' => strtolower($portal->country_code)]) }}" 
                       class="block w-full py-3 px-4 text-center bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>

            {{-- Express --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden ring-2 ring-blue-500 relative">
                <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                    POPULAR
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Express</h3>
                    <p class="text-sm text-gray-500 mt-1">2-3 business days</p>
                    <div class="mt-4">
                        <span class="text-4xl font-bold text-gray-900">$0.18</span>
                        <span class="text-gray-500">/word</span>
                    </div>
                    <ul class="mt-6 space-y-3 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            All Standard Features
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Priority Processing
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Hard Copy Available
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            2 Revisions Included
                        </li>
                    </ul>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('gov.portal.submit', ['country' => strtolower($portal->country_code)]) }}" 
                       class="block w-full py-3 px-4 text-center bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>

            {{-- Rush --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Rush</h3>
                    <p class="text-sm text-gray-500 mt-1">24-48 hours</p>
                    <div class="mt-4">
                        <span class="text-4xl font-bold text-gray-900">$0.25</span>
                        <span class="text-gray-500">/word</span>
                    </div>
                    <ul class="mt-6 space-y-3 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            All Express Features
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Dedicated Translator
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Express Courier Option
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Unlimited Revisions
                        </li>
                    </ul>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('gov.portal.submit', ['country' => strtolower($portal->country_code)]) }}" 
                       class="block w-full py-3 px-4 text-center bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>
        </div>

        {{-- Additional Services --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12">
            <div class="p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Additional Services</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    @if($portal->requires_notarization)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-semibold text-gray-900">Notarization</h3>
                                <p class="text-sm text-gray-500">Official notary seal and signature</p>
                            </div>
                            <span class="text-lg font-bold text-gray-900">$25</span>
                        </div>
                    @endif
                    
                    @if($portal->requires_apostille)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-semibold text-gray-900">Apostille Processing</h3>
                                <p class="text-sm text-gray-500">International document authentication</p>
                            </div>
                            <span class="text-lg font-bold text-gray-900">$75</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-semibold text-gray-900">Hard Copy Delivery</h3>
                            <p class="text-sm text-gray-500">Physical document by mail</p>
                        </div>
                        <span class="text-lg font-bold text-gray-900">$15</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-semibold text-gray-900">Express Courier</h3>
                            <p class="text-sm text-gray-500">International express delivery</p>
                        </div>
                        <span class="text-lg font-bold text-gray-900">$45</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-semibold text-gray-900">Document Formatting</h3>
                            <p class="text-sm text-gray-500">Match original document layout</p>
                        </div>
                        <span class="text-lg font-bold text-gray-900">$20</span>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-semibold text-gray-900">Second Language Version</h3>
                            <p class="text-sm text-gray-500">Additional target language</p>
                        </div>
                        <span class="text-lg font-bold text-gray-900">50% off</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Document Type Pricing --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12">
            <div class="p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Flat Rate Documents</h2>
                <p class="text-gray-600 mb-6">Some standard documents have fixed pricing regardless of word count:</p>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Document Type</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-900">Standard</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-900">Express</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-900">Rush</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b">
                                <td class="py-3 px-4">Birth Certificate</td>
                                <td class="py-3 px-4 text-right">$30</td>
                                <td class="py-3 px-4 text-right">$45</td>
                                <td class="py-3 px-4 text-right">$65</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">Marriage Certificate</td>
                                <td class="py-3 px-4 text-right">$35</td>
                                <td class="py-3 px-4 text-right">$50</td>
                                <td class="py-3 px-4 text-right">$75</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">Death Certificate</td>
                                <td class="py-3 px-4 text-right">$30</td>
                                <td class="py-3 px-4 text-right">$45</td>
                                <td class="py-3 px-4 text-right">$65</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">Divorce Decree</td>
                                <td class="py-3 px-4 text-right">$40</td>
                                <td class="py-3 px-4 text-right">$60</td>
                                <td class="py-3 px-4 text-right">$90</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">Driver's License</td>
                                <td class="py-3 px-4 text-right">$25</td>
                                <td class="py-3 px-4 text-right">$40</td>
                                <td class="py-3 px-4 text-right">$55</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">Diploma/Degree</td>
                                <td class="py-3 px-4 text-right">$35</td>
                                <td class="py-3 px-4 text-right">$50</td>
                                <td class="py-3 px-4 text-right">$75</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Price Calculator --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12">
            <div class="p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Price Calculator</h2>
                
                <div class="grid md:grid-cols-2 gap-8" x-data="priceCalculator()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Word Count</label>
                            <input type="number" 
                                   x-model="wordCount"
                                   min="0"
                                   placeholder="Enter word count"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Speed</label>
                            <select x-model="speed" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="standard">Standard (5-7 days)</option>
                                <option value="express">Express (2-3 days)</option>
                                <option value="rush">Rush (24-48 hours)</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Additional Services</label>
                            @if($portal->requires_notarization)
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="notarization" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-600">Notarization (+$25)</span>
                                </label>
                            @endif
                            @if($portal->requires_apostille)
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="apostille" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-600">Apostille (+$75)</span>
                                </label>
                            @endif
                            <label class="flex items-center">
                                <input type="checkbox" x-model="hardCopy" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-600">Hard Copy Delivery (+$15)</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estimated Total</h3>
                        
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Translation (<span x-text="wordCount || 0"></span> words)</dt>
                                <dd class="font-medium" x-text="'$' + translationCost.toFixed(2)"></dd>
                            </div>
                            <template x-if="notarization">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Notarization</dt>
                                    <dd class="font-medium">$25.00</dd>
                                </div>
                            </template>
                            <template x-if="apostille">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Apostille</dt>
                                    <dd class="font-medium">$75.00</dd>
                                </div>
                            </template>
                            <template x-if="hardCopy">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Hard Copy Delivery</dt>
                                    <dd class="font-medium">$15.00</dd>
                                </div>
                            </template>
                        </dl>
                        
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="'$' + totalCost.toFixed(2)"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $portal->currency_code }}</p>
                        </div>
                        
                        <a href="{{ route('gov.portal.submit', ['country' => strtolower($portal->country_code)]) }}" 
                           class="mt-6 block w-full py-3 px-4 text-center bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>
                
                <div class="space-y-4">
                    <details class="group border rounded-lg">
                        <summary class="flex justify-between items-center cursor-pointer p-4 font-medium text-gray-900">
                            How is word count calculated?
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="px-4 pb-4 text-gray-600 text-sm">
                            Word count is based on the source document. Our team will provide an exact quote after reviewing your document. 
                            For standard documents like birth certificates, we offer flat-rate pricing.
                        </p>
                    </details>
                    
                    <details class="group border rounded-lg">
                        <summary class="flex justify-between items-center cursor-pointer p-4 font-medium text-gray-900">
                            What payment methods do you accept?
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="px-4 pb-4 text-gray-600 text-sm">
                            We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers. 
                            Payment is collected after you approve the quote.
                        </p>
                    </details>
                    
                    <details class="group border rounded-lg">
                        <summary class="flex justify-between items-center cursor-pointer p-4 font-medium text-gray-900">
                            Is there a minimum charge?
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="px-4 pb-4 text-gray-600 text-sm">
                            Yes, we have a minimum charge of $25 for certified translations. This ensures quality review and 
                            certification regardless of document length.
                        </p>
                    </details>
                    
                    <details class="group border rounded-lg">
                        <summary class="flex justify-between items-center cursor-pointer p-4 font-medium text-gray-900">
                            Do you offer discounts for bulk orders?
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="px-4 pb-4 text-gray-600 text-sm">
                            Yes! We offer volume discounts for orders over 5,000 words and enterprise pricing for businesses 
                            with regular translation needs. Contact us for a custom quote.
                        </p>
                    </details>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function priceCalculator() {
    return {
        wordCount: 0,
        speed: 'standard',
        notarization: {{ $portal->requires_notarization ? 'true' : 'false' }},
        apostille: false,
        hardCopy: false,
        
        get rates() {
            return {
                standard: 0.12,
                express: 0.18,
                rush: 0.25
            };
        },
        
        get translationCost() {
            return Math.max(25, (this.wordCount || 0) * this.rates[this.speed]);
        },
        
        get totalCost() {
            let total = this.translationCost;
            if (this.notarization) total += 25;
            if (this.apostille) total += 75;
            if (this.hardCopy) total += 15;
            return total;
        }
    }
}
</script>
@endpush
@endsection
