<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نسيت كلمة المرور - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Cairo', sans-serif; }
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
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1), 0 0 20px rgba(108, 99, 255, 0.2);
        }
    </style>
</head>
<body class="text-white">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">
                        Cultural Translate
                    </h1>
                </a>
                <p class="mt-3 text-gray-400">استعادة كلمة المرور</p>
            </div>

            <!-- Form Card -->
            <div class="glass-card rounded-2xl p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <p class="text-gray-300 text-sm mb-6">
                            أدخل بريدك الإلكتروني وسنرسل لك رابط لإعادة تعيين كلمة المرور
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            البريد الإلكتروني
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 input-glow focus:outline-none transition"
                            placeholder="your@email.com"
                        >
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full btn-premium text-white font-semibold py-3.5 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50"
                    >
                        إرسال رابط الاستعادة
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center pt-4">
                        <a href="{{ route('login') }}" class="text-sm text-purple-400 hover:text-purple-300 transition">
                            العودة لتسجيل الدخول
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-500 text-sm mt-8">
                © 2025 Cultural Translate. جميع الحقوق محفوظة
            </p>
        </div>
    </div>
</body>
</html>
