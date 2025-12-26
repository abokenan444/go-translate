@extends('layouts.app')

@section('title', 'Enterprise Solutions - CulturalTranslate')

@section('content')
<div class="bg-gray-50">
    {{-- Hero Section --}}
    <section class="bg-blue-800 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-4">CulturalTranslate for Enterprise</h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto">Powerful, scalable, and secure translation solutions designed for your global business needs.</p>
            <a href="#contact" class="bg-white text-blue-800 font-bold py-3 px-8 rounded-full hover:bg-gray-200 transition duration-300">Request a Demo</a>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">Everything you need to go global</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9V3m0 18a9 9 0 019-9"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Advanced Translation AI</h3>
                    <p class="text-gray-600">Leverage our state-of-the-art neural machine translation, trained on industry-specific data for unparalleled accuracy and nuance.</p>
                </div>
                <div class="text-center">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Bank-Grade Security</h3>
                    <p class="text-gray-600">Protect your sensitive data with end-to-end encryption, secure data centers, and compliance with global privacy standards.</p>
                </div>
                <div class="text-center">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Seamless Integration</h3>
                    <p class="text-gray-600">Integrate our powerful translation API into your existing workflows, CMS, and applications with ease. SDKs available for popular languages.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Ready to Scale Your Global Reach?</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">Our team is ready to help you design a custom plan that fits your enterprise needs. Let's talk about your localization strategy.</p>
            <a href="#contact" class="bg-blue-600 text-white font-bold py-4 px-10 rounded-full hover:bg-blue-700 transition duration-300">Contact Sales</a>
        </div>
    </section>

    {{-- Contact Form Section --}}
    <section id="contact" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">Request a Custom Plan</h2>
                <form class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-bold mb-2">Full Name</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-bold mb-2">Work Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="company" class="block text-gray-700 font-bold mb-2">Company</label>
                            <input type="text" id="company" name="company" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 font-bold mb-2">How can we help?</label>
                        <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition duration-300">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
