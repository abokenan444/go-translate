<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>🚨 وصول الطوارئ - SuperAI Agent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
        
        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e1e2e 0%, #2d1b3d 50%, #1a1a2e 100%);
            min-height: 100vh;
        }

        .glow-box {
            box-shadow: 0 0 20px rgba(255, 59, 48, 0.3),
                        0 0 40px rgba(255, 59, 48, 0.2),
                        inset 0 0 60px rgba(255, 59, 48, 0.1);
        }

        .emergency-border {
            border: 2px solid #ff3b30;
            animation: pulse-border 2s ease-in-out infinite;
        }

        @keyframes pulse-border {
            0%, 100% {
                border-color: #ff3b30;
                box-shadow: 0 0 20px rgba(255, 59, 48, 0.5);
            }
            50% {
                border-color: #ff6b60;
                box-shadow: 0 0 40px rgba(255, 59, 48, 0.8);
            }
        }

        .warning-stripe {
            background: repeating-linear-gradient(
                45deg,
                #ff3b30,
                #ff3b30 10px,
                #ffd60a 10px,
                #ffd60a 20px
            );
            height: 8px;
        }

        .input-glow:focus {
            box-shadow: 0 0 15px rgba(52, 199, 89, 0.5);
            border-color: #34c759;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        
        <!-- تحذير الطوارئ -->
        <div class="mb-6 text-center">
            <div class="warning-stripe rounded-t-lg"></div>
            <div class="bg-red-600 text-white py-3 px-4 rounded-b-lg">
                <div class="text-3xl mb-2">🚨</div>
                <h1 class="text-xl font-black">وضع الطوارئ</h1>
                <p class="text-sm opacity-90 mt-1">Emergency SuperAI Agent Access</p>
            </div>
        </div>

        <!-- بطاقة تسجيل الدخول -->
        <div class="bg-gray-900/80 backdrop-blur-lg rounded-2xl emergency-border glow-box overflow-hidden">
            
            <!-- الرأس -->
            <div class="bg-gradient-to-r from-red-600 to-purple-600 p-6 text-center">
                <div class="text-5xl mb-3">🤖</div>
                <h2 class="text-2xl font-black text-white mb-1">SuperAI Agent</h2>
                <p class="text-white/80 text-sm">Master Control System</p>
            </div>

            <!-- النموذج -->
            <div class="p-8">
                
                @if(session('error'))
                    <div class="mb-6 bg-red-500/20 border-2 border-red-500 rounded-lg p-4 text-center">
                        <div class="text-3xl mb-2">❌</div>
                        <p class="text-red-200 font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 bg-green-500/20 border-2 border-green-500 rounded-lg p-4 text-center">
                        <div class="text-3xl mb-2">✅</div>
                        <p class="text-green-200 font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- معلومات مهمة -->
                <div class="mb-6 bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">⚠️</div>
                        <div class="text-yellow-200 text-sm">
                            <p class="font-bold mb-1">تحذير أمني:</p>
                            <ul class="space-y-1 text-xs">
                                <li>• هذا الوصول مخصص للطوارئ فقط</li>
                                <li>• الحد الأقصى: {{ config('ai_developer.emergency.max_login_attempts', 5) }} محاولات</li>
                                <li>• مدة الحظر: {{ config('ai_developer.emergency.lockout_minutes', 15) }} دقيقة</li>
                                <li>• مدة الجلسة: {{ config('ai_developer.emergency.session_lifetime_hours', 4) }} ساعات</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('super-ai.login.post') }}" class="space-y-6">
                    @csrf

                    <!-- حقل كلمة المرور -->
                    <div>
                        <label class="block text-white font-bold mb-2 flex items-center gap-2">
                            <span>🔐</span>
                            <span>كلمة المرور الرئيسية</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            required
                            autofocus
                            autocomplete="off"
                            placeholder="••••••••••••"
                            class="w-full bg-gray-800/50 border-2 border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none input-glow transition-all"
                        >
                        @error('password')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- معلومات الجلسة -->
                    <div class="bg-gray-800/30 border border-gray-700 rounded-lg p-4 text-xs text-gray-400">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <span class="block text-gray-500">IP Address:</span>
                                <span class="text-white font-mono">{{ request()->ip() }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500">Timestamp:</span>
                                <span class="text-white font-mono">{{ now()->format('H:i:s') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- زر تسجيل الدخول -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-purple-600 hover:from-red-700 hover:to-purple-700 text-white font-black text-lg py-4 rounded-lg transition-all transform hover:scale-105 active:scale-95 shadow-lg"
                    >
                        <span class="flex items-center justify-center gap-2">
                            <span>🚀</span>
                            <span>دخول النظام</span>
                            <span>ENTER SYSTEM</span>
                        </span>
                    </button>
                </form>

                <!-- رابط العودة -->
                <div class="mt-6 text-center">
                    <a href="/" class="text-gray-400 hover:text-white text-sm transition-colors inline-flex items-center gap-2">
                        <span>⬅️</span>
                        <span>العودة للصفحة الرئيسية</span>
                    </a>
                </div>
            </div>

            <!-- التذييل -->
            <div class="bg-gray-950/50 px-6 py-4 border-t border-gray-800">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <div>🔒 Encrypted Connection</div>
                    <div>Cultural Translate Platform</div>
                    <div>🛡️ Rate Limited</div>
                </div>
            </div>
        </div>

        <!-- تحذير إضافي -->
        <div class="mt-6 text-center text-gray-400 text-xs">
            <p>⚡ This system logs all access attempts</p>
            <p class="mt-1">جميع محاولات الوصول مسجلة ومراقبة</p>
        </div>
    </div>

    <script>
        // إضافة تأثير عند الضغط على Enter
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });

        // تنبيه عند محاولة النسخ/اللصق
        document.getElementById('password').addEventListener('paste', function(e) {
            console.warn('Paste detected - Security Alert');
        });
    </script>
</body>
</html>
