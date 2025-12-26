@extends('layouts.app')
@section('title', __('Certificate Verification'))
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">{{ __('CTS™ Certificate Verification') }}</h1>
            <p class="text-xl">{{ __('Verify the authenticity of CTS™ certified translations') }}</p>
        </div>
    </div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6">{{ __('Verify Certificate') }}</h2>
            <form action="{{ url('/verify-certificate') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Certificate Number') }}</label>
                    <input type="text" name="certificate_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="CTS-XXXX-XXXX-XXXX" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Verification Code') }}</label>
                    <input type="text" name="verification_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="XXXXXX" required>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">{{ __('Verify Certificate') }}</button>
            </form>
        </div>
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-900 mb-2">{{ __('About Certificate Verification') }}</h3>
            <p class="text-blue-800 text-sm">{{ __('All CTS™ certified translations include a unique certificate number and verification code. Use this tool to confirm the authenticity and validity of any CTS™ certificate.') }}</p>
        </div>
    </div>
</div>
@endsection
