@extends('layouts.app')

{{-- SEO Meta Tags --}}
@section('title', 'Help Center - CulturalTranslate')
@section('description', 'Find answers to frequently asked questions and access support resources for CulturalTranslate. Get help with translation, platform features, and account management.')

@section('content')

    {{-- Hero Section --}}
    <section class="py-20 bg-indigo-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                How Can We Help You Today?
            </h1>
            <p class="text-xl opacity-90 mb-8">
                Find instant answers, tutorials, and support resources for all your CulturalTranslate needs.
            </p>
        </div>
    </section>

    {{-- Search Bar Section --}}
    <section class="container mx-auto px-4 -mt-10 mb-12">
        <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-lg p-6">
            <form action="#" method="GET" class="flex items-center">
                <input type="search" placeholder="Search for articles, features, or troubleshooting..."
                    class="w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border border-gray-300 rounded-l-lg"
                    aria-label="Search help center">
                <button type="submit"
                    class="bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 px-6 rounded-r-lg transition duration-300 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </section>

    {{-- Quick Categories Section --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">Popular Topics</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Category Card 1 --}}
                <a href="#" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 border-t-4 border-indigo-500">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-800">Account Management</h3>
                    </div>
                    <p class="text-gray-600">Manage your profile, subscriptions, and security settings.</p>
                </a>

                {{-- Category Card 2 --}}
                <a href="#" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 border-t-4 border-green-500">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-800">Translation Services</h3>
                    </div>
                    <p class="text-gray-600">Guides on ordering, tracking, and receiving your translations.</p>
                </a>

                {{-- Category Card 3 --}}
                <a href="#" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 border-t-4 border-yellow-500">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <h3 class="text-xl font-semibold text-gray-800">Billing & Payments</h3>
                    </div>
                    <p class="text-gray-600">Information about pricing, invoices, and payment methods.</p>
                </a>
            </div>
        </div>
    </section>

    {{-- FAQ Section (Simple Accordion Structure) --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Frequently Asked Questions</h2>

            <div class="space-y-4">
                {{-- FAQ Item 1 --}}
                <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <button class="w-full flex justify-between items-center p-5 text-lg font-semibold text-left text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none"
                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');">
                        What is CulturalTranslate and how does it work?
                        <svg class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="p-5 text-gray-600 hidden">
                        CulturalTranslate is a platform that connects you with professional, culturally-aware translators for over 100 languages. You submit your document, choose your language pair and service level, and our system matches you with the best-suited expert. You receive the completed translation directly through your dashboard.
                    </div>
                </div>

                {{-- FAQ Item 2 --}}
                <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <button class="w-full flex justify-between items-center p-5 text-lg font-semibold text-left text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none"
                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');">
                        How long does a translation take?
                        <svg class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="p-5 text-gray-600 hidden">
                        The turnaround time depends on the length and complexity of the document, as well as the language pair. Our platform provides an estimated delivery time when you place your order. We also offer expedited services for urgent needs.
                    </div>
                </div>

                {{-- FAQ Item 3 --}}
                <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <button class="w-full flex justify-between items-center p-5 text-lg font-semibold text-left text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none"
                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');">
                        Is my data secure and confidential?
                        <svg class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="p-5 text-gray-600 hidden">
                        Yes, we take data security and confidentiality very seriously. All translators sign strict Non-Disclosure Agreements (NDAs), and our platform uses industry-standard encryption to protect your files and personal information.
                    </div>
                </div>

                {{-- FAQ Item 4 --}}
                <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <button class="w-full flex justify-between items-center p-5 text-lg font-semibold text-left text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none"
                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');">
                        What if I am not satisfied with the translation quality?
                        <svg class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="p-5 text-gray-600 hidden">
                        We offer a quality guarantee. If you have any concerns about the translation, please contact our support team within 14 days of delivery, and we will arrange for a free revision or a full review by a senior editor.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact/Support CTA Section --}}
    <section class="py-16 bg-indigo-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Still Need Help?</h2>
            <p class="text-xl text-gray-600 mb-8">
                Our dedicated support team is ready to assist you with any complex issues.
            </p>
            <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-6">
                <a href="#"
                    class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-300 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Live Chat Support
                </a>
                <a href="#"
                    class="inline-flex items-center justify-center px-8 py-3 border border-indigo-600 text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition duration-300 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Email Our Team
                </a>
            </div>
        </div>
    </section>

@endsection

{{-- Note: The FAQ section includes simple inline JavaScript for a basic accordion effect.
In a real Laravel/Tailwind project, this would typically be handled by a dedicated JS framework (like Alpine.js) or a custom script included in the main layout.
For a ready-to-use Blade file, this inline script provides immediate functionality. --}}
