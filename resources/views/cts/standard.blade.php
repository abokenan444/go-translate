@extends('layouts.app')

@section('title', __('CTS™ Standard - Technical Specifications'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">{{ __('CTS™ Standard Technical Specifications') }}</h1>
            <p class="text-xl text-indigo-100">{{ __('Version 2.0 - Updated December 2025') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Overview -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Standard Overview') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('The CTS™ Standard defines a comprehensive framework for evaluating and certifying culturally-aware translations. It consists of five evaluation layers, each with specific criteria and scoring mechanisms.') }}
            </p>
        </div>

        <!-- Five Layers -->
        <div class="space-y-8">
            <!-- Layer 1 -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">1</span>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Linguistic Integrity Layer') }}</h3>
                </div>
                <div class="ml-14">
                    <p class="text-gray-700 mb-4">{{ __('Evaluates the fundamental linguistic quality of the translation.') }}</p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Grammar Accuracy:') }}</strong> {{ __('Correct syntax, verb conjugation, and sentence structure') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Terminology Consistency:') }}</strong> {{ __('Uniform use of technical and domain-specific terms') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Stylistic Appropriateness:') }}</strong> {{ __('Tone and register matching source intent') }}</span>
                        </li>
                    </ul>
                    <div class="mt-4 p-4 bg-blue-50 rounded">
                        <p class="text-sm text-gray-700"><strong>{{ __('Scoring:') }}</strong> {{ __('0-100 points based on error density and severity') }}</p>
                    </div>
                </div>
            </div>

            <!-- Layer 2 -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-purple-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">2</span>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Cultural Norms Layer') }}</h3>
                </div>
                <div class="ml-14">
                    <p class="text-gray-700 mb-4">{{ __('Assesses alignment with target culture norms and values.') }}</p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Idiom Adaptation:') }}</strong> {{ __('Localization of expressions and metaphors') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Social Norms:') }}</strong> {{ __('Respect for hierarchy, formality, and communication styles') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Visual Elements:') }}</strong> {{ __('Color symbolism, imagery, and design considerations') }}</span>
                        </li>
                    </ul>
                    <div class="mt-4 p-4 bg-purple-50 rounded">
                        <p class="text-sm text-gray-700"><strong>{{ __('Scoring:') }}</strong> {{ __('Cultural adaptation score (0-100) based on localization depth') }}</p>
                    </div>
                </div>
            </div>

            <!-- Layer 3 -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">3</span>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Sensitivity Screening Layer') }}</h3>
                </div>
                <div class="ml-14">
                    <p class="text-gray-700 mb-4">{{ __('Identifies potential religious, political, and social sensitivities.') }}</p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Religious Content:') }}</strong> {{ __('Detection and appropriate handling of religious references') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Political Sensitivities:') }}</strong> {{ __('Awareness of geopolitical issues and territorial disputes') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Social Taboos:') }}</strong> {{ __('Identification of culturally sensitive topics') }}</span>
                        </li>
                    </ul>
                    <div class="mt-4 p-4 bg-pink-50 rounded">
                        <p class="text-sm text-gray-700"><strong>{{ __('Scoring:') }}</strong> {{ __('Risk assessment (Low/Medium/High) with mitigation recommendations') }}</p>
                    </div>
                </div>
            </div>

            <!-- Layer 4 -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">4</span>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Legal Compliance Layer') }}</h3>
                </div>
                <div class="ml-14">
                    <p class="text-gray-700 mb-4">{{ __('Ensures compliance with legal and regulatory requirements.') }}</p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Legal Terminology:') }}</strong> {{ __('Accurate translation of legal phrases and contracts') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Regulatory Compliance:') }}</strong> {{ __('Adherence to industry-specific regulations') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Data Privacy:') }}</strong> {{ __('GDPR, CCPA, and other privacy law compliance') }}</span>
                        </li>
                    </ul>
                    <div class="mt-4 p-4 bg-green-50 rounded">
                        <p class="text-sm text-gray-700"><strong>{{ __('Scoring:') }}</strong> {{ __('Pass/Fail with detailed compliance checklist') }}</p>
                    </div>
                </div>
            </div>

            <!-- Layer 5 -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-4">
                    <span class="bg-yellow-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold mr-4">5</span>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Audience Tolerance Layer') }}</h3>
                </div>
                <div class="ml-14">
                    <p class="text-gray-700 mb-4">{{ __('Evaluates content appropriateness for target audience segments.') }}</p>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Age Appropriateness:') }}</strong> {{ __('Content rating for different age groups') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Professional Context:') }}</strong> {{ __('Workplace and business environment suitability') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span><strong>{{ __('Cultural Sensitivity:') }}</strong> {{ __('Conservative vs. liberal audience considerations') }}</span>
                        </li>
                    </ul>
                    <div class="mt-4 p-4 bg-yellow-50 rounded">
                        <p class="text-sm text-gray-700"><strong>{{ __('Scoring:') }}</strong> {{ __('Audience suitability matrix with recommendations') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certification Process -->
        <div class="bg-white rounded-lg shadow-md p-8 mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Certification Process') }}</h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <span class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4 flex-shrink-0">1</span>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ __('Submission') }}</h4>
                        <p class="text-gray-700">{{ __('Upload source and translated content for evaluation') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4 flex-shrink-0">2</span>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ __('Automated Analysis') }}</h4>
                        <p class="text-gray-700">{{ __('AI-powered evaluation across all five layers') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4 flex-shrink-0">3</span>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ __('Expert Review') }}</h4>
                        <p class="text-gray-700">{{ __('Human linguists and cultural experts validate findings') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4 flex-shrink-0">4</span>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ __('Certification') }}</h4>
                        <p class="text-gray-700">{{ __('Issue CTS™ certificate with unique verification code') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-12">
            <a href="{{ route('register') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                {{ __('Get CTS™ Certified Today') }}
            </a>
        </div>
    </div>
</div>
@endsection
