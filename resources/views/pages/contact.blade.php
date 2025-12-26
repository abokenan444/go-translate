<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us - CulturalTranslate</title>
    <meta name="description" content="Get in touch with CulturalTranslate. We're here to help with any questions about our AI-powered translation platform.">
    <script src="https://cdn.tailwindcss.com"></script>
    @if(config('services.recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="/" class="text-2xl font-bold text-indigo-600">Cultural Translate</a>
                <div class="hidden md:flex items-center space-x-reverse space-x-8">
                    <a href="/" class="text-gray-700 hover:text-indigo-600">Home</a>
                    <a href="/features" class="text-gray-700 hover:text-indigo-600">Features</a>
                    <a href="/pricing" class="text-gray-700 hover:text-indigo-600">Pricing</a>
                    <a href="/contact" class="text-indigo-600 font-semibold">Contact</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl">We're here to help and answer any question you might have</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                <- Backup created automatically with full Honeypot field -->
                <input type="text" name="website_url" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
                <input type="hidden" name="form_start_time" id="contactFormStartTime" value="">
                        @csrf
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Message</label>
                            <textarea name="message" rows="5" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" id="contactSubmitBtn" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="text-indigo-600 text-2xl ml-4">📧</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                                    <a href="mailto:support@culturaltranslate.com" class="text-indigo-600 hover:underline">support@culturaltranslate.com</a>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-indigo-600 text-2xl ml-4">⏰</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Response Time</h3>
                                    <p class="text-gray-700">We typically respond within 24 hours</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-indigo-600 text-2xl ml-4">🌍</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Office Hours</h3>
                                    <p class="text-gray-700">Monday - Friday: 9:00 AM - 6:00 PM (CET)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Business Hours</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Monday - Friday</span>
                                <span class="text-gray-600">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Saturday</span>
                                <span class="text-gray-600">10:00 AM - 4:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700 font-semibold">Sunday</span>
                                <span class="text-gray-600">Closed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 CulturalTranslate. All rights reserved.</p>
            <p class="text-sm mt-2">NL KvK 83656480</p>
        </div>
    </footer>

    @if(config('services.recaptcha.site_key'))
    <script>
    document.querySelector('form[action="{{ route('contact.submit') }}"]').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = document.getElementById('contactSubmitBtn');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = 'Sending...';
        
        try {
            // Get reCAPTCHA token
            const token = await grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'contact_form'});
            
            // Add token to form
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recaptcha_token';
            input.value = token;
            this.appendChild(input);
            
            // Submit form
            this.submit();
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            alert('Security verification failed. Please try again.');
        }
    });
    </script>
    @endif
</body>
</html>
