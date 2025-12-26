@extends('layouts.app')

@section('title', 'Enterprise Request Form - Cultural Translate')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Enterprise Subscription Request</h1>
                <p class="text-gray-600">Fill out the form below and our team will contact you within 24 hours</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('enterprise.submit-request') }}" class="space-y-6">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                        <input type="text" name="company_name" id="company_name" required 
                               value="{{ old('company_name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">Company Email *</label>
                        <input type="email" name="company_email" id="company_email" required 
                               value="{{ old('company_email') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-2">Company Phone</label>
                        <input type="tel" name="company_phone" id="company_phone" 
                               value="{{ old('company_phone') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax ID / VAT Number</label>
                        <input type="text" name="tax_id" id="tax_id" 
                               value="{{ old('tax_id') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">Company Address</label>
                    <textarea name="company_address" id="company_address" rows="2" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('company_address') }}</textarea>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Contact</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="billing_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Name *</label>
                            <input type="text" name="billing_contact_name" id="billing_contact_name" required 
                                   value="{{ old('billing_contact_name') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="billing_contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email *</label>
                            <input type="email" name="billing_contact_email" id="billing_contact_email" required 
                                   value="{{ old('billing_contact_email') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Details</h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="preferred_plan_type" class="block text-sm font-medium text-gray-700 mb-2">Preferred Plan Type *</label>
                            <select name="preferred_plan_type" id="preferred_plan_type" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Select a plan</option>
                                <option value="pay_as_you_go" {{ old('preferred_plan_type') == 'pay_as_you_go' ? 'selected' : '' }}>Pay As You Go</option>
                                <option value="committed_volume" {{ old('preferred_plan_type') == 'committed_volume' ? 'selected' : '' }}>Committed Volume</option>
                                <option value="hybrid" {{ old('preferred_plan_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>

                        <div>
                            <label for="estimated_monthly_words" class="block text-sm font-medium text-gray-700 mb-2">Estimated Monthly Words</label>
                            <input type="number" name="estimated_monthly_words" id="estimated_monthly_words" min="0" 
                                   value="{{ old('estimated_monthly_words') }}"
                                   placeholder="e.g. 50000"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-2">Special Requirements</label>
                    <textarea name="special_requirements" id="special_requirements" rows="4" 
                              placeholder="Tell us about any specific needs, language pairs, or integration requirements..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('special_requirements') }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('enterprise.pricing') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        ??? Back to Pricing
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white py-3 px-8 rounded-lg hover:bg-indigo-700 font-semibold transition shadow-lg hover:shadow-xl">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
