<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <a href="/use-cases" class="text-gray-700 hover:text-indigo-600">Use Cases</a>
                    <a href="/api-docs" class="text-gray-700 hover:text-indigo-600">API Docs</a>
                    <a href="/about" class="text-gray-700 hover:text-indigo-600">About</a>
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
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Subject</label>
                            <input type="text" name="subject" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Message</label>
                            <textarea name="message" rows="5" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                        <div class="space-y-6">
                            @php
                                $contactSettings = \App\Models\ContactSetting::where('group', 'general')->orderBy('order')->get();
                            @endphp
                            
                            @forelse($contactSettings as $setting)
                                <div class="flex items-start">
                                    @if($setting->type == 'email')
                                        <div class="text-indigo-600 text-2xl ml-4">📧</div>
                                    @elseif($setting->type == 'phone')
                                        <div class="text-indigo-600 text-2xl ml-4">📞</div>
                                    @elseif($setting->type == 'url')
                                        <div class="text-indigo-600 text-2xl ml-4">🌐</div>
                                    @else
                                        <div class="text-indigo-600 text-2xl ml-4">📍</div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $setting->label }}</h3>
                                        @if($setting->type == 'email')
                                            <a href="mailto:{{ $setting->value }}" class="text-indigo-600 hover:underline">{{ $setting->value }}</a>
                                        @elseif($setting->type == 'phone')
                                            <a href="tel:{{ $setting->value }}" class="text-indigo-600 hover:underline">{{ $setting->value }}</a>
                                        @elseif($setting->type == 'url')
                                            <a href="{{ $setting->value }}" target="_blank" class="text-indigo-600 hover:underline">{{ $setting->value }}</a>
                                        @else
                                            <p class="text-gray-700">{{ $setting->value }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>

                    @php
                        $businessHours = \App\Models\ContactSetting::where('group', 'business_hours')->orderBy('order')->get();
                    @endphp
                    
                    @if($businessHours->count() > 0)
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Business Hours</h3>
                        <div class="space-y-3">
                            @foreach($businessHours as $hour)
                                <div class="flex justify-between">
                                    <span class="text-gray-700 font-semibold">{{ $hour->label }}</span>
                                    <span class="text-gray-600">{{ $hour->value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2024 CulturalTranslate. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
