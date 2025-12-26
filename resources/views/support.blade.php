@extends('layouts.app')

@section('title', 'Technical Support - Certified Translation')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Certified Translation Support</h1>
            <p class="text-xl text-gray-600">Get help with your certified document translations</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-12">
            
            <!-- Quick Help -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Help</h2>
                
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">How to get a certified translation?</h3>
                        <p class="text-gray-600 text-sm">Upload your document, select languages, and submit. You'll receive a certified PDF with an official stamp and QR code.</p>
                    </div>
                    
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Supported file types</h3>
                        <p class="text-gray-600 text-sm">PDF, JPG, PNG (max 10MB)</p>
                    </div>
                    
                    <div class="border-l-4 border-purple-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Pricing</h3>
                        <p class="text-gray-600 text-sm">$10 per document or included in your subscription plan</p>
                    </div>
                    
                    <div class="border-l-4 border-orange-500 pl-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Verification</h3>
                        <p class="text-gray-600 text-sm">Scan the QR code on your certificate or visit the verification page with your certificate number</p>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Contact Support</h2>
                
                <form action="{{ route('support.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Certificate Number (if applicable)</label>
                        <input type="text" name="cert_number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="CT-2025-XXXXXX">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Issue Type</label>
                        <select name="issue_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select issue type</option>
                            <option value="upload_error">Upload Error</option>
                            <option value="translation_quality">Translation Quality</option>
                            <option value="payment_issue">Payment Issue</option>
                            <option value="verification_problem">Verification Problem</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Message</label>
                        <textarea name="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        Submit Support Request
                    </button>
                </form>
            </div>
        </div>

        <!-- FAQ -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Is the certified translation legally valid?</h3>
                    <p class="text-gray-600">Yes, our certified translations include an official stamp, certificate number, and QR verification, making them legally valid for official purposes.</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">How long does it take?</h3>
                    <p class="text-gray-600">Most translations are completed within 2-5 minutes, depending on document size and complexity.</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Can I verify my certificate?</h3>
                    <p class="text-gray-600">Yes, scan the QR code on your certificate or visit /certified-translation/verify/{your-certificate-number}</p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">What if I'm not satisfied with the translation?</h3>
                    <p class="text-gray-600">Contact our support team with your certificate number, and we'll review and revise the translation if needed.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
