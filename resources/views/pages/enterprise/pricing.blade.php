@extends('layouts.app')
@section('title', 'Enterprise Pricing - CulturalTranslate')
@section('content')
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Enterprise Pricing</h1>
        <p class="text-xl mb-8">Custom solutions for large organizations</p>
    </div>
</section>
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-8">Enterprise Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="flex items-start">
                    <i class="fas fa-check text-green-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h3 class="font-bold">Unlimited Translations</h3>
                        <p class="text-gray-600">No character limits</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check text-green-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h3 class="font-bold">Dedicated Account Manager</h3>
                        <p class="text-gray-600">Personal support</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check text-green-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h3 class="font-bold">Custom API Integration</h3>
                        <p class="text-gray-600">Tailored to your needs</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check text-green-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h3 class="font-bold">Priority Support</h3>
                        <p class="text-gray-600">24/7 dedicated support</p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('enterprise.request') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Request Enterprise Quote
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
