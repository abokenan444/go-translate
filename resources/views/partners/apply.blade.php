@extends('layouts.app')

@section('title', 'Become a Partner - CulturalTranslate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">🤝 Become a Partner</h1>
                <p class="text-xl text-gray-600">Join our global network of certified translation partners</p>
            </div>

            <!-- Benefits -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                    <div class="text-4xl mb-3">💰</div>
                    <h3 class="font-bold text-lg mb-2">Competitive Revenue</h3>
                    <p class="text-gray-600">Earn up to 40% commission</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                    <div class="text-4xl mb-3">🌍</div>
                    <h3 class="font-bold text-lg mb-2">Global Reach</h3>
                    <p class="text-gray-600">Access international clients</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                    <div class="text-4xl mb-3">🛠️</div>
                    <h3 class="font-bold text-lg mb-2">Full Support</h3>
                    <p class="text-gray-600">Dedicated partner success team</p>
                </div>
            </div>

            <!-- Application Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Application Form</h2>
                
                <form action="{{ route('partner.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Company Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Company Information</h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="company_name" class="block text-gray-700 font-semibold mb-2">Company Name *</label>
                                <input type="text" id="company_name" name="company_name" autocomplete="organization" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                            </div>
                            
                            <div>
                                <label for="registration_number" class="block text-gray-700 font-semibold mb-2">Registration Number *</label>
                                <input type="text" id="registration_number" name="registration_number" autocomplete="off" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                            </div>
                            
                            <div>
                                <label for="country" class="block text-gray-700 font-semibold mb-2">Country *</label>
                                <select id="country" name="country" autocomplete="country" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                                    <option value="">Select Country</option>
                                    <option value="US">United States</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="AE">UAE</option>
                                    <option value="FR">France</option>
                                    <option value="DE">Germany</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="website" class="block text-gray-700 font-semibold mb-2">Website</label>
                                <input type="url" id="website" name="website" autocomplete="url" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none"
                                       placeholder="https://example.com">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Person -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Contact Person</h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_name" class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                                <input type="text" id="contact_name" name="contact_name" autocomplete="name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                            </div>
                            
                            <div>
                                <label for="position" class="block text-gray-700 font-semibold mb-2">Position *</label>
                                <input type="text" id="position" name="position" autocomplete="organization-title" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-gray-700 font-semibold mb-2">Email *</label>
                                <input type="email" id="email" name="email" autocomplete="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone *</label>
                                <input type="tel" id="phone" name="phone" autocomplete="tel" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Services Offered</h3>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <label class="flex items-center space-x-3 p-4 border border-gray-300 rounded-lg hover:border-purple-600 cursor-pointer">
                                <input type="checkbox" name="services[]" value="document_translation" class="w-5 h-5 text-purple-600">
                                <span>Document Translation</span>
                            </label>
                            
                            <label class="flex items-center space-x-3 p-4 border border-gray-300 rounded-lg hover:border-purple-600 cursor-pointer">
                                <input type="checkbox" name="services[]" value="certified_translation" class="w-5 h-5 text-purple-600">
                                <span>Certified Translation</span>
                            </label>
                            
                            <label class="flex items-center space-x-3 p-4 border border-gray-300 rounded-lg hover:border-purple-600 cursor-pointer">
                                <input type="checkbox" name="services[]" value="legal_translation" class="w-5 h-5 text-purple-600">
                                <span>Legal Translation</span>
                            </label>
                            
                            <label class="flex items-center space-x-3 p-4 border border-gray-300 rounded-lg hover:border-purple-600 cursor-pointer">
                                <input type="checkbox" name="services[]" value="technical_translation" class="w-5 h-5 text-purple-600">
                                <span>Technical Translation</span>
                            </label>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-8">
                        <label class="block text-gray-700 font-semibold mb-2">Why do you want to become a partner?</label>
                        <textarea name="message" rows="5" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none"
                                  placeholder="Tell us about your company and why you'd like to partner with us..."></textarea>
                    </div>

                    <!-- Terms -->
                    <div class="mb-8">
                        <label class="flex items-start space-x-3">
                            <input type="checkbox" name="agree_terms" required class="mt-1 w-5 h-5 text-purple-600">
                            <span class="text-gray-700">
                                I agree to the <a href="{{ route('terms') }}" class="text-purple-600 hover:underline">Terms and Conditions</a> 
                                and <a href="{{ route('privacy') }}" class="text-purple-600 hover:underline">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 rounded-lg font-semibold text-lg hover:from-purple-700 hover:to-blue-700 transition">
                        Submit Application
                    </button>
                </form>
            </div>

            <!-- Process -->
            <div class="mt-12 bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Application Process</h2>
                
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">1</span>
                        </div>
                        <h3 class="font-bold mb-2">Submit Application</h3>
                        <p class="text-sm text-gray-600">Fill out the form above</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">2</span>
                        </div>
                        <h3 class="font-bold mb-2">Review</h3>
                        <p class="text-sm text-gray-600">We'll review within 3-5 days</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">3</span>
                        </div>
                        <h3 class="font-bold mb-2">Interview</h3>
                        <p class="text-sm text-gray-600">Brief call with our team</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">4</span>
                        </div>
                        <h3 class="font-bold mb-2">Onboarding</h3>
                        <p class="text-sm text-gray-600">Get started as a partner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
