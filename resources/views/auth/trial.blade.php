<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Free Trial - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        
        body {
            background: linear-gradient(135deg, #0D0D0D 0%, #1a1a2e 100%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        
        .btn-premium {
            background: linear-gradient(135deg, #6C63FF 0%, #5A52D5 100%);
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-premium:hover {
            background: linear-gradient(135deg, #5A52D5 0%, #4840B0 100%);
            box-shadow: 0 15px 40px rgba(108, 99, 255, 0.5);
            transform: translateY(-2px);
        }
        
        .input-glow:focus {
            border-color: #6C63FF;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1),
                        0 0 20px rgba(108, 99, 255, 0.2);
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.15;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: #6C63FF;
            top: 5%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 350px;
            height: 350px;
            background: #5A52D5;
            bottom: 5%;
            right: 5%;
            animation-delay: 5s;
        }
        
        .shape-3 {
            width: 300px;
            height: 300px;
            background: #4840B0;
            top: 50%;
            right: 10%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .feature-icon {
            background: rgba(108, 99, 255, 0.1);
            border: 1px solid rgba(108, 99, 255, 0.2);
        }
    </style>
</head>
<body class="text-white relative">
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-4xl">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">
                        Cultural Translate
                    </h1>
                </a>
                <p class="text-gray-400 mt-2">Start Your Free Trial Now</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side - Features -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="text-xl font-bold mb-6 text-center">What You Get</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm">14 Days Free</h4>
                                    <p class="text-xs text-gray-400">Full experience with no limitations</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm">10,000 Words</h4>
                                    <p class="text-xs text-gray-400">Free translation</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm">13 Languages</h4>
                                    <p class="text-xs text-gray-400">Multi-language translation</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm">Smart AI</h4>
                                    <p class="text-xs text-gray-400">Accurate cultural translation</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm">No Card Required</h4>
                                    <p class="text-xs text-gray-400">No credit card needed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badge -->
                    <div class="glass-card rounded-2xl p-4 text-center">
                        <div class="flex items-center justify-center gap-2 text-green-400 mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="text-sm font-semibold">100% Safe Safe & 100% Secure</span>
                        </div>
                        <p class="text-xs text-gray-400">Your data is fully protected</p>
                    </div>
                </div>

                <!-- Right Side - Form -->
                <div class="lg:col-span-2">
                    <div class="glass-card rounded-2xl p-8">
                        <h2 class="text-2xl font-bold mb-6">Start Your Free Trial</h2>

                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                        Full Name *
                                    </label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}"
                                        required 
                                        autofocus
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none transition"
                                        placeholder="John Doe"
                                    >
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                        Email Address *
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}"
                                        required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none transition"
                                        placeholder="your@email.com"
                                    >
                                </div>
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-300 mb-2">
                                    Company Name (Optional)
                                </label>
                                <input 
                                    type="text" 
                                    id="company" 
                                    name="company" 
                                    value="{{ old('company') }}"
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none transition"
                                    placeholder="Your Company"
                                >
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                        Password *
                                    </label>
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none transition"
                                        placeholder="••••••••"
                                    >
                                    <p class="text-xs text-gray-500 mt-1">At least 8 characters</p>
                                </div>

                                <!-- Password Confirmation -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                        Confirm Password *
                                    </label>
                                    <input 
                                        type="password" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none transition"
                                        placeholder="••••••••"
                                    >
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="flex items-start">
                                <input 
                                    type="checkbox" 
                                    id="terms" 
                                    name="terms" 
                                    required
                                    class="w-4 h-4 mt-1 rounded border-white/10 bg-white/5 text-purple-600 focus:ring-purple-500 focus:ring-offset-0"
                                >
                                <label for="terms" class="mr-2 text-sm text-gray-400">
                                    I agree to the 
                                    <a href="#" class="text-purple-400 hover:text-purple-300">Terms and Conditions</a>
                                    و
                                    <a href="#" class="text-purple-400 hover:text-purple-300">Privacy Policy</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full btn-premium text-white px-6 py-4 rounded-xl font-semibold text-lg flex items-center justify-center gap-2"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Start Your Free Trial Now
                            </button>

                            <p class="text-center text-xs text-gray-500">
                                We will not ask for a credit card • Cancel anytime
                            </p>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-white/10"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-transparent text-gray-400">or</span>
                            </div>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="text-gray-400 text-sm">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-semibold transition">
                                    Sign in
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="/" class="text-gray-400 hover:text-white text-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
