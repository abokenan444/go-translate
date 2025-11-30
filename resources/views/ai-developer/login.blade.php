<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Developer System - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="glass rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">AI Developer System</h1>
            <p class="text-white/80">نظام التطوير الذكي المتكامل</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-white">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-white">
                {{ session('error') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('ai-developer.login.post') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="password" class="block text-sm font-medium text-white mb-2">
                    كلمة المرور
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    autofocus
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition"
                    placeholder="أدخل كلمة المرور"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full py-3 px-4 bg-white text-purple-600 font-semibold rounded-lg hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white/50 transition transform hover:scale-105"
            >
                دخول
            </button>
        </form>

        <!-- Info -->
        <div class="mt-8 p-4 bg-white/5 rounded-lg">
            <p class="text-sm text-white/70 text-center">
                💡 للدخول أو تعديل كلمة المرور استخدم متغير <code class="text-white font-mono">AI_DEV_ACCESS_PASSWORD</code> في ملف <code class="text-white font-mono">.env</code>
            </p>
        </div>
    </div>
</body>
</html>
