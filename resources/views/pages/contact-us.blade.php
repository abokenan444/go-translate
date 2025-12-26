@extends('layouts.app')
@section('title', 'Contact Us - Cultural Translate')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Contact Us</h1>
            <p class="text-2xl text-gray-600">We're here to help</p>
        </div>
        <div class="grid md:grid-cols-2 gap-12">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                <form class="space-y-6" onsubmit="alert('Thank you! We will get back to you soon.'); return false;">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required></textarea>
                    </div>
                    <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-green-600 text-white font-bold rounded-lg">Send Message</button>
                </form>
            </div>
            <div class="space-y-8">
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">📧 Email</h3>
                    <p class="text-gray-700">support@culturaltranslate.com</p>
                </div>
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">💬 Live Chat</h3>
                    <p class="text-gray-700">Available 24/7 for instant support</p>
                </div>
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">📍 Office</h3>
                    <p class="text-gray-700">Global headquarters in multiple locations</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
