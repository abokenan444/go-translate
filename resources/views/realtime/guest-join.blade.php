{{-- resources/views/realtime/guest-join.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Join Meeting – {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .qr-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="glass rounded-2xl p-8 shadow-2xl">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">🎥 Cultural Translate</h1>
                <p class="text-white/80">Join Real-Time Translation Meeting</p>
            </div>

            @if(isset($session))
                {{-- Session Info --}}
                <div class="bg-white/10 rounded-xl p-4 mb-6">
                    <h2 class="text-white font-semibold mb-2">📋 Meeting Details</h2>
                    <div class="space-y-2 text-sm text-white/90">
                        <div class="flex justify-between">
                            <span>Title:</span>
                            <span class="font-medium">{{ $session->title ?? 'Untitled' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Languages:</span>
                            <span class="font-medium">{{ strtoupper($session->source_language) }} ⇆ {{ strtoupper($session->target_language) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Session ID:</span>
                            <span class="font-mono text-xs">{{ $session->public_id }}</span>
                        </div>
                    </div>
                </div>

                {{-- Join Form --}}
                <form id="join-form" class="space-y-4">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Your Display Name</label>
                        <input 
                            type="text" 
                            id="guest-name" 
                            name="name" 
                            placeholder="Enter your name" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                        >
                    </div>

                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Email (Optional)</label>
                        <input 
                            type="email" 
                            id="guest-email" 
                            name="email" 
                            placeholder="your@email.com"
                            class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-white text-purple-600 font-semibold py-3 px-6 rounded-lg hover:bg-purple-50 transition duration-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Join Meeting
                    </button>
                </form>

                {{-- QR Code Section --}}
                @if(isset($qrCode))
                <div class="mt-6 text-center">
                    <p class="text-white/80 text-sm mb-3">Or scan to join:</p>
                    <div class="qr-container mx-auto inline-block">
                        {!! $qrCode !!}
                    </div>
                </div>
                @endif

            @else
                {{-- Invalid Token --}}
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">❌</div>
                    <h2 class="text-2xl font-bold text-white mb-2">Invalid Meeting Link</h2>
                    <p class="text-white/80 mb-6">This meeting link is invalid or has expired.</p>
                    <a href="{{ url('/') }}" class="inline-block bg-white text-purple-600 font-semibold py-2 px-6 rounded-lg hover:bg-purple-50 transition">
                        Go to Homepage
                    </a>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6">
            <p class="text-white/60 text-sm">
                Powered by <a href="{{ url('/') }}" class="text-white hover:underline">Cultural Translate Platform</a>
            </p>
        </div>
    </div>

    @if(isset($session))
    <script>
        document.getElementById('join-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const name = document.getElementById('guest-name').value.trim();
            const email = document.getElementById('guest-email').value.trim();
            
            if (!name) {
                alert('Please enter your name');
                return;
            }
            
            // Generate guest token
            const guestToken = 'guest_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            
            // Store guest info in localStorage
            localStorage.setItem('guestInfo', JSON.stringify({
                name: name,
                email: email,
                token: guestToken,
                sessionId: '{{ $session->public_id }}'
            }));
            
            // Redirect to meeting
            window.location.href = '{{ route("realtime.meeting.show", $session->public_id) }}?guest=' + guestToken;
        });
    </script>
    @endif
</body>
</html>
