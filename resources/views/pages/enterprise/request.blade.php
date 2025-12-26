@extends('layouts.app')
@section('title', 'Enterprise Request - CulturalTranslate')
@section('content')
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Request Enterprise Quote</h1>
        <p class="text-xl mb-8">Tell us about your needs and we'll get back to you</p>
    </div>
</section>
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('enterprise.submit-request') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Company Name</label>
                    <input type="text" name="company_name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Phone</label>
                    <input type="tel" name="phone" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Message</label>
                    <textarea name="message" rows="5" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <button type="submit" class="w-full px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Submit Request
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
