@extends('layouts.app')
@section('title', __('messages.government_portal'))
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    🏛️ {{ __('messages.for_government_entities') }}
                </div>
                <h1 class="text-5xl font-bold text-gray-900 mb-6">{{ __('messages.government_portal') }}</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ __('messages.government_portal_desc') }}
                </p>
            </div>

            <!-- Main Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 mb-8">
                <form action="{{ route('government.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.department') }} *</label>
                            <input type="text" name="department" id="department" required 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                        <div>
                            <label for="contact_name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.contact_name') }} *</label>
                            <input type="text" name="contact_name" id="contact_name" required 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }} *</label>
                            <input type="email" name="email" id="email" required 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.phone') }} *</label>
                            <input type="tel" name="phone" id="phone" required 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                        </div>
                    </div>

                    <div>
                        <label for="document_type" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.document_type') }} *</label>
                        <select name="document_type" id="document_type" required 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                            <option value="">{{ __('messages.select_type') }}</option>
                            <option value="legal">{{ __('messages.legal_document') }}</option>
                            <option value="certificate">{{ __('messages.certificate') }}</option>
                            <option value="contract">{{ __('messages.contract') }}</option>
                            <option value="policy">{{ __('messages.policy_document') }}</option>
                            <option value="regulation">{{ __('messages.regulation') }}</option>
                            <option value="other">{{ __('messages.other') }}</option>
                        </select>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="source_language" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.source_language') }} *</label>
                            <select name="source_language" id="source_language" required 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                                <option value="en">English</option>
                                <option value="ar">العربية</option>
                                <option value="fr">Français</option>
                                <option value="es">Español</option>
                            </select>
                        </div>
                        <div>
                            <label for="target_language" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.target_language') }} *</label>
                            <select name="target_language" id="target_language" required 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                                <option value="ar">العربية</option>
                                <option value="en">English</option>
                                <option value="fr">Français</option>
                                <option value="es">Español</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="file-upload" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.upload_document') }} *</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-500 transition cursor-pointer">
                            <input type="file" name="document" required accept=".pdf,.doc,.docx" class="hidden" id="file-upload">
                            <label for="file-upload" class="cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">{{ __('messages.click_to_upload') }}</p>
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX (Max 10MB)</p>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.additional_notes') }}</label>
                        <textarea name="notes" id="notes" rows="4" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"></textarea>
                    </div>

                    <div class="flex items-center space-x-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <input type="checkbox" name="agree_terms" id="agree_terms" required class="w-5 h-5 text-purple-600">
                        <label for="agree_terms" class="text-sm text-gray-700">
                            {{ __('messages.agree_to') }} <a href="{{ url('/terms') }}" class="text-purple-600 hover:underline">{{ __('messages.terms_and_conditions') }}</a>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-4 px-8 rounded-lg hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition duration-300 shadow-lg">
                        {{ __('messages.submit_for_translation') }}
                    </button>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-start space-x-4">
                    <svg class="w-8 h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold mb-2">🔒 {{ __('messages.security_confidentiality') }}</h3>
                        <p class="text-blue-100">{{ __('messages.government_security_notice') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
