<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SuperAI Agent - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Fira+Code:wght@400;500;600&display=swap');
        
        * {
            font-family: 'Cairo', sans-serif;
        }

        .font-mono {
            font-family: 'Fira Code', monospace;
        }

        body {
            background: linear-gradient(135deg, #0a0e27 0%, #16213e 50%, #0f3460 100%);
        }

        .status-online {
            animation: pulse-green 2s ease-in-out infinite;
        }

        @keyframes pulse-green {
            0%, 100% {
                box-shadow: 0 0 10px rgba(52, 199, 89, 0.5);
            }
            50% {
                box-shadow: 0 0 20px rgba(52, 199, 89, 0.8);
            }
        }

        .chat-message {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading-dots::after {
            content: '...';
            animation: dots 1.5s steps(3, end) infinite;
        }

        @keyframes dots {
            0%, 20% {
                content: '.';
            }
            40% {
                content: '..';
            }
            60%, 100% {
                content: '...';
            }
        }

        .terminal {
            background: #1a1b26;
            border: 1px solid #414868;
        }

        .health-good {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .health-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .health-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
</head>
<body class="min-h-screen" x-data="superAI()">
    
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        
        <!-- الهيدر -->
        <div class="bg-gradient-to-r from-purple-900/50 to-blue-900/50 backdrop-blur-lg rounded-2xl p-6 mb-6 border border-purple-500/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="text-5xl">🤖</div>
                    <div>
                        <h1 class="text-3xl font-black text-white">SuperAI Agent</h1>
                        <p class="text-purple-200">Master Control Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-left">
                        <div class="text-xs text-gray-400">Session Time</div>
                        <div class="text-white font-mono" x-text="sessionTime">00:00:00</div>
                    </div>
                    <form method="POST" action="{{ route('super-ai.logout') }}">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-bold transition-all">
                            🚪 خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- حالة النظام -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <template x-for="(check, key) in systemHealth" :key="key">
                <div class="bg-gray-900/50 backdrop-blur-lg rounded-xl p-4 border border-gray-700"
                     :class="{
                         'border-green-500': check.status === 'healthy',
                         'border-yellow-500': check.status === 'warning',
                         'border-red-500': check.status === 'error'
                     }">
                    <div class="text-center">
                        <div class="text-3xl mb-2" x-text="check.icon"></div>
                        <div class="text-white font-bold text-sm" x-text="check.name"></div>
                        <div class="mt-2">
                            <span class="px-2 py-1 rounded text-xs font-bold"
                                  :class="{
                                      'bg-green-500 text-white': check.status === 'healthy',
                                      'bg-yellow-500 text-black': check.status === 'warning',
                                      'bg-red-500 text-white': check.status === 'error'
                                  }"
                                  x-text="check.status">
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- منطقة الدردشة الرئيسية -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- نافذة الدردشة -->
                <div class="bg-gray-900/80 backdrop-blur-lg rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
                        <h2 class="text-xl font-black text-white flex items-center gap-2">
                            <span>💬</span>
                            <span>AI Agent Chat</span>
                        </h2>
                    </div>

                    <div class="h-96 overflow-y-auto p-4 space-y-4" id="chatMessages">
                        <template x-for="(msg, index) in messages" :key="index">
                            <div class="chat-message"
                                 :class="msg.type === 'user' ? 'text-left' : 'text-right'">
                                <div class="inline-block max-w-[80%] rounded-lg p-4"
                                     :class="msg.type === 'user' ? 
                                         'bg-blue-600 text-white' : 
                                         'bg-gray-800 text-gray-100'">
                                    <div class="text-xs opacity-75 mb-1" x-text="msg.type === 'user' ? '👤 أنت' : '🤖 AI'"></div>
                                    <div class="whitespace-pre-wrap" x-html="msg.content"></div>
                                    <div class="text-xs opacity-50 mt-2" x-text="msg.time"></div>
                                </div>
                            </div>
                        </template>

                        <div x-show="isProcessing" class="text-center py-4">
                            <div class="inline-block bg-purple-600/20 border border-purple-500 rounded-lg px-6 py-3">
                                <span class="text-purple-300 loading-dots">جاري المعالجة</span>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="sendMessage" class="p-4 border-t border-gray-700">
                        <div class="flex gap-3">
                            <input 
                                type="text" 
                                x-model="prompt"
                                :disabled="isProcessing"
                                placeholder="اكتب طلبك للـ AI Agent هنا..."
                                class="flex-1 bg-gray-800 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 disabled:opacity-50"
                            >
                            <button 
                                type="submit"
                                :disabled="isProcessing || !prompt.trim()"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold px-6 py-3 rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isProcessing">🚀 إرسال</span>
                                <span x-show="isProcessing">⏳</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- الشريط الجانبي -->
            <div class="space-y-6">
                
                <!-- أدوات سريعة -->
                <div class="bg-gray-900/80 backdrop-blur-lg rounded-2xl border border-gray-700 p-6">
                    <h3 class="text-xl font-black text-white mb-4 flex items-center gap-2">
                        <span>⚡</span>
                        <span>أدوات سريعة</span>
                    </h3>
                    <div class="space-y-3">
                        <button @click="analyzeLogsAction" 
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 rounded-lg transition-all">
                            📋 تحليل السجلات
                        </button>
                        <button @click="clearCacheAction"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-lg transition-all">
                            🗑️ تنظيف الكاش
                        </button>
                        <button @click="healthCheckAction"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition-all">
                            🏥 فحص الصحة
                        </button>
                        <button @click="suggestImprovementsAction"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg transition-all">
                            💡 اقتراحات التحسين
                        </button>
                    </div>
                </div>

                <!-- معلومات الجلسة -->
                <div class="bg-gray-900/80 backdrop-blur-lg rounded-2xl border border-gray-700 p-6">
                    <h3 class="text-xl font-black text-white mb-4">🔐 معلومات الجلسة</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <div class="text-gray-400">IP Address</div>
                            <div class="text-white font-mono">{{ request()->ip() }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Login Time</div>
                            <div class="text-white font-mono">{{ session('super_ai_login_time')->format('Y-m-d H:i:s') }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Expires At</div>
                            <div class="text-white font-mono">{{ session('super_ai_login_time')->addHours(4)->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function superAI() {
            return {
                prompt: '',
                messages: [],
                isProcessing: false,
                sessionTime: '00:00:00',
                systemHealth: @json($health ?? []),

                init() {
                    this.updateSessionTime();
                    setInterval(() => this.updateSessionTime(), 1000);
                    this.loadSystemHealth();
                    setInterval(() => this.loadSystemHealth(), 30000); // كل 30 ثانية
                },

                updateSessionTime() {
                    const loginTime = new Date('{{ session("super_ai_login_time") }}');
                    const now = new Date();
                    const diff = Math.floor((now - loginTime) / 1000);
                    const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
                    const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
                    const seconds = (diff % 60).toString().padStart(2, '0');
                    this.sessionTime = `${hours}:${minutes}:${seconds}`;
                },

                async sendMessage() {
                    if (!this.prompt.trim() || this.isProcessing) return;

                    const userMessage = this.prompt;
                    this.messages.push({
                        type: 'user',
                        content: userMessage,
                        time: new Date().toLocaleTimeString('ar-SA')
                    });
                    
                    this.prompt = '';
                    this.isProcessing = true;
                    this.scrollToBottom();

                    try {
                        const response = await fetch('{{ route("super-ai.process") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ prompt: userMessage })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.messages.push({
                                type: 'ai',
                                content: this.formatResponse(data.result),
                                time: new Date().toLocaleTimeString('ar-SA')
                            });
                        } else {
                            this.messages.push({
                                type: 'ai',
                                content: `❌ خطأ: ${data.message}`,
                                time: new Date().toLocaleTimeString('ar-SA')
                            });
                        }
                    } catch (error) {
                        this.messages.push({
                            type: 'ai',
                            content: `❌ حدث خطأ: ${error.message}`,
                            time: new Date().toLocaleTimeString('ar-SA')
                        });
                    } finally {
                        this.isProcessing = false;
                        this.scrollToBottom();
                    }
                },

                formatResponse(result) {
                    let html = '';
                    
                    if (result.analysis) {
                        html += `<div class="mb-3"><strong>📊 التحليل:</strong><br>${result.analysis.intent || 'جاري التحليل...'}</div>`;
                    }
                    
                    if (result.plan && result.plan.length > 0) {
                        html += '<div class="mb-3"><strong>📋 الخطة:</strong><ul class="list-disc list-inside mt-2">';
                        result.plan.forEach(step => {
                            html += `<li>${step.order}. ${step.action}</li>`;
                        });
                        html += '</ul></div>';
                    }

                    if (result.execution && result.execution.length > 0) {
                        html += '<div class="mb-3"><strong>✅ التنفيذ:</strong><ul class="list-disc list-inside mt-2">';
                        result.execution.forEach(ex => {
                            const icon = ex.success ? '✅' : '❌';
                            html += `<li>${icon} ${ex.step}: ${ex.output || ex.error}</li>`;
                        });
                        html += '</ul></div>';
                    }

                    return html || 'تم تنفيذ العملية بنجاح';
                },

                async analyzeLogsAction() {
                    this.isProcessing = true;
                    try {
                        const response = await fetch('{{ route("super-ai.analyze-logs") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await response.json();
                        this.messages.push({
                            type: 'ai',
                            content: data.analysis || 'تم تحليل السجلات',
                            time: new Date().toLocaleTimeString('ar-SA')
                        });
                    } catch (error) {
                        alert('خطأ: ' + error.message);
                    } finally {
                        this.isProcessing = false;
                        this.scrollToBottom();
                    }
                },

                async clearCacheAction() {
                    if (!confirm('هل أنت متأكد من تنظيف الكاش؟')) return;
                    
                    this.isProcessing = true;
                    try {
                        const response = await fetch('{{ route("super-ai.clear-cache") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await response.json();
                        this.messages.push({
                            type: 'ai',
                            content: '✅ تم تنظيف الكاش بنجاح',
                            time: new Date().toLocaleTimeString('ar-SA')
                        });
                        this.loadSystemHealth();
                    } catch (error) {
                        alert('خطأ: ' + error.message);
                    } finally {
                        this.isProcessing = false;
                    }
                },

                async healthCheckAction() {
                    this.isProcessing = true;
                    await this.loadSystemHealth();
                    this.isProcessing = false;
                },

                async suggestImprovementsAction() {
                    this.isProcessing = true;
                    try {
                        const response = await fetch('{{ route("super-ai.improvements") }}');
                        const data = await response.json();
                        this.messages.push({
                            type: 'ai',
                            content: data.suggestions || 'لا توجد اقتراحات حالياً',
                            time: new Date().toLocaleTimeString('ar-SA')
                        });
                    } catch (error) {
                        alert('خطأ: ' + error.message);
                    } finally {
                        this.isProcessing = false;
                        this.scrollToBottom();
                    }
                },

                async loadSystemHealth() {
                    try {
                        const response = await fetch('{{ route("super-ai.health") }}');
                        const data = await response.json();
                        if (data.checks) {
                            this.systemHealth = data.checks;
                        }
                    } catch (error) {
                        console.error('خطأ في تحميل حالة النظام:', error);
                    }
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const chatDiv = document.getElementById('chatMessages');
                        chatDiv.scrollTop = chatDiv.scrollHeight;
                    }, 100);
                }
            }
        }
    </script>
</body>
</html>
