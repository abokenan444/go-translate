<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Cultural Translate
                </a>
                <p class="text-gray-400 mt-2">Start your journey with us</p>
            </div>

            <!-- Form -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 shadow-2xl border border-gray-700">
                <h2 class="text-2xl font-bold text-white mb-6">Create New Account</h2>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg">
                        <div class="font-semibold mb-2">⚠️ Please fix the following errors:</div>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Success Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-500/20 border border-green-500 text-green-200 px-4 py-3 rounded-lg">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" autocomplete="name" placeholder="John Doe" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" autocomplete="email" placeholder="your@email.com" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name -->
                    <div class="mb-4">
                        <label for="company" class="block text-gray-300 mb-2">Company Name (Optional)</label>
                        <input type="text" name="company" id="company" autocomplete="organization" placeholder="Your Company" value="{{ old('company') }}"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500">
                    </div>

                    <!-- Account Type -->
                    <div class="mb-4">
                        <label for="account_type" class="block text-gray-300 mb-2">Account Type</label>
                        <select name="account_type" id="account_type" autocomplete="off" required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 @error('account_type') border-red-500 @enderror">
                            <option value="">Choose your account type</option>
                            <option value="customer" {{ old('account_type') == 'customer' ? 'selected' : '' }}>Customer Account</option>
                            <option value="affiliate" {{ old('account_type') == 'affiliate' ? 'selected' : '' }}>Affiliate Account</option>
                            <option value="partner" {{ old('account_type') == 'partner' ? 'selected' : '' }}>Partner Account (requires verification)</option>
                            <option value="translator" {{ old('account_type') == 'translator' ? 'selected' : '' }}>Translator Account (requires verification)</option>
                            <option value="university" {{ old('account_type') == 'university' ? 'selected' : '' }}>University Account (requires verification)</option>
                        </select>
                        <p class="text-gray-400 text-sm mt-1">Choose the account type that best describes you. <br><span class="text-purple-400">Government/Authority accounts are invite-only</span> - contact us for access.</p>
                        @error('account_type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-300 mb-2">Password</label>
                        <input type="password" name="password" id="password" autocomplete="new-password" placeholder="••••••••" required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 @error('password') border-red-500 @enderror">
                        <p class="text-gray-400 text-sm mt-1">At least 8 characters</p>
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-300 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" placeholder="••••••••" required
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500">
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms" id="terms" required class="mt-1 mr-2">
                            <span class="text-gray-300 text-sm">
                                I agree to the 
                                <a href="/terms" class="text-purple-400 hover:text-purple-300">Terms and Conditions</a> 
                                and 
                                <a href="/privacy" class="text-purple-400 hover:text-purple-300">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Create Account
                        </span>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-400">
                        Already have an account? 
                        <a href="/login" class="text-purple-400 hover:text-purple-300 font-semibold">Log in</a>
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-4 text-center">
                    <a href="/" class="text-gray-400 hover:text-gray-300 text-sm">
                        ← Back to home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
