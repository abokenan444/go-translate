{{-- resources/views/realtime/call-mode.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Call – {{ $session->title ?? 'Translation Call' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            overflow: hidden;
        }
        
        .video-circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .video-circle video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .control-btn {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .control-btn:hover {
            transform: scale(1.1);
        }
        
        .control-btn.end-call {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .control-btn.mute {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .control-btn.mute.active {
            background: #ef4444;
        }
        
        .latency-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .latency-good {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }
        
        .latency-medium {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }
        
        .latency-bad {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .wave-animation {
            animation: wave 1.5s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(1.5); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div
        id="ct-call-config"
        data-session-public-id="{{ $session->public_id }}"
        data-user-id="{{ (int) auth()->id() }}"
        data-default-receive-language="{{ $session->target_language ?? 'en' }}"
        class="hidden"
    ></div>
    <div class="max-w-2xl w-full px-4">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="latency-indicator latency-good mb-4" id="latency-indicator">
                <span class="inline-block w-2 h-2 rounded-full bg-current"></span>
                <span id="latency-value">< 300ms</span>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">{{ $session->title ?? 'Translation Call' }}</h1>
            <p class="text-white/60 text-sm">
                {{ strtoupper($session->source_language) }} ⇆ {{ strtoupper($session->target_language) }}
            </p>
        </div>

        {{-- Video Section --}}
        <div class="flex justify-center items-center gap-12 mb-12">
            {{-- Local Video --}}
            <div class="text-center">
                <div class="video-circle mb-4 mx-auto">
                    <video id="local-video" autoplay playsinline muted class="w-full h-full"></video>
                </div>
                <p class="text-white font-medium">You</p>
                <div class="flex justify-center gap-1 mt-2" id="local-audio-bars">
                    <div class="w-1 h-4 bg-green-500 rounded wave-animation" style="animation-delay: 0s"></div>
                    <div class="w-1 h-4 bg-green-500 rounded wave-animation" style="animation-delay: 0.1s"></div>
                    <div class="w-1 h-4 bg-green-500 rounded wave-animation" style="animation-delay: 0.2s"></div>
                    <div class="w-1 h-4 bg-green-500 rounded wave-animation" style="animation-delay: 0.3s"></div>
                </div>
            </div>

            {{-- Remote Video --}}
            <div class="text-center">
                <div class="video-circle mb-4 mx-auto">
                    <video id="remote-video" autoplay playsinline class="w-full h-full"></video>
                </div>
                <p class="text-white font-medium" id="remote-name">Connecting...</p>
                <div class="flex justify-center gap-1 mt-2" id="remote-audio-bars" style="opacity: 0">
                    <div class="w-1 h-4 bg-blue-500 rounded wave-animation" style="animation-delay: 0s"></div>
                    <div class="w-1 h-4 bg-blue-500 rounded wave-animation" style="animation-delay: 0.1s"></div>
                    <div class="w-1 h-4 bg-blue-500 rounded wave-animation" style="animation-delay: 0.2s"></div>
                    <div class="w-1 h-4 bg-blue-500 rounded wave-animation" style="animation-delay: 0.3s"></div>
                </div>
            </div>
        </div>

        {{-- Controls --}}
        <div class="flex justify-center items-center gap-6 mb-8">
            <button id="toggle-video" class="control-btn mute" title="Toggle Camera">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </button>

            <button id="toggle-audio" class="control-btn mute" title="Toggle Microphone">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                </svg>
            </button>

            <button id="end-call" class="control-btn end-call" title="End Call">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"></path>
                </svg>
            </button>
        </div>

        {{-- Translation Display --}}
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold">Live Translation</h3>
                <span class="text-xs text-white/60">Real-time</span>
            </div>

            {{-- Language Controls (per participant) --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-xs text-white/70 mb-1">Send language</label>
                    <select id="send-language" class="w-full bg-white/10 text-white text-sm rounded-lg px-3 py-2 border border-white/20">
                        <option value="auto">Auto</option>
                        @foreach(config('languages.supported', []) as $code => $meta)
                            <option value="{{ $code }}">{{ strtoupper($code) }} — {{ $meta['native'] ?? $meta['name'] ?? $code }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-white/70 mb-1">Receive language</label>
                    <select id="receive-language" class="w-full bg-white/10 text-white text-sm rounded-lg px-3 py-2 border border-white/20">
                        @foreach(config('languages.supported', []) as $code => $meta)
                            <option value="{{ $code }}">{{ strtoupper($code) }} — {{ $meta['native'] ?? $meta['name'] ?? $code }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="translation-display" class="space-y-3 max-h-40 overflow-y-auto">
                <p class="text-white/60 text-sm text-center py-4">Waiting for speech...</p>
            </div>
        </div>

        {{-- Call Duration --}}
        <div class="text-center mt-6">
            <p class="text-white/60 text-sm">Call Duration: <span id="call-duration" class="text-white font-mono">00:00</span></p>
        </div>
    </div>

    <script>
        const configEl = document.getElementById('ct-call-config');
        const SESSION_PUBLIC_ID = configEl.getAttribute('data-session-public-id');
        const CURRENT_USER_ID = Number(configEl.getAttribute('data-user-id'));
        const DEFAULT_RECEIVE_LANGUAGE = configEl.getAttribute('data-default-receive-language') || 'en';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const JOIN_URL = `/realtime/api/sessions/${SESSION_PUBLIC_ID}/participants/join`;
        const UPDATE_ME_URL = `/realtime/api/sessions/${SESSION_PUBLIC_ID}/participants/me`;
        const AUDIO_URL = `/realtime/api/sessions/${SESSION_PUBLIC_ID}/audio`;
        const POLL_URL = `/realtime/api/sessions/${SESSION_PUBLIC_ID}/poll`;

        // WebRTC Configuration
        const configuration = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' }
            ]
        };

        let localStream = null;
        let peerConnection = null;
        let isVideoEnabled = true;
        let isAudioEnabled = true;
        let callStartTime = Date.now();
        let durationInterval = null;

        // Live translation streaming
        let mediaRecorder = null;
        let lastSeenTurnId = 0;
        let pollInterval = null;

        let sendLanguage = 'auto';
        let receiveLanguage = DEFAULT_RECEIVE_LANGUAGE;

        // Initialize
        document.addEventListener('DOMContentLoaded', async () => {
            await initializeMedia();
            connectToSignaling();
            startCallDuration();
            setupControls();

            setupLanguageControls();
            await joinAsParticipant();
            startAudioStreaming();
            startPollingTurns();
        });

        // Initialize media
        async function initializeMedia() {
            try {
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 640, height: 480 },
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true
                    }
                });

                document.getElementById('local-video').srcObject = localStream;
                updateLatency(Math.floor(Math.random() * 200) + 100);
            } catch (error) {
                console.error('Error accessing media:', error);
                alert('Could not access camera/microphone');
            }
        }

        // Connect to signaling
        function connectToSignaling() {
            // Simplified for call mode - direct P2P
            console.log('Connecting to signaling server...');
            
            // Simulate remote connection after 2 seconds
            setTimeout(() => {
                document.getElementById('remote-name').textContent = 'Participant';
                document.getElementById('remote-audio-bars').style.opacity = '1';
            }, 2000);
        }

        // Setup controls
        function setupControls() {
            document.getElementById('toggle-video').addEventListener('click', toggleVideo);
            document.getElementById('toggle-audio').addEventListener('click', toggleAudio);
            document.getElementById('end-call').addEventListener('click', endCall);
        }

        function setupLanguageControls() {
            const sendSelect = document.getElementById('send-language');
            const receiveSelect = document.getElementById('receive-language');

            // Default UI values
            sendSelect.value = sendLanguage;
            receiveSelect.value = receiveLanguage;

            sendSelect.addEventListener('change', async (e) => {
                sendLanguage = e.target.value;
                await updateMyLanguages();
            });

            receiveSelect.addEventListener('change', async (e) => {
                receiveLanguage = e.target.value;
                await updateMyLanguages();
            });
        }

        async function joinAsParticipant() {
            const body = {
                display_name: 'You',
                role: 'speaker',
                send_language: sendLanguage,
                receive_language: receiveLanguage,
            };

            const res = await fetch(JOIN_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify(body),
            });

            if (!res.ok) {
                const txt = await res.text();
                console.error('Join failed', txt);
            }
        }

        async function updateMyLanguages() {
            const body = {
                send_language: sendLanguage,
                receive_language: receiveLanguage,
            };

            const res = await fetch(UPDATE_ME_URL, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify(body),
            });

            if (!res.ok) {
                const txt = await res.text();
                console.error('Update languages failed', txt);
            }
        }

        function startAudioStreaming() {
            if (!localStream) return;

            const audioTrack = localStream.getAudioTracks()[0];
            if (!audioTrack) return;

            const audioOnlyStream = new MediaStream([audioTrack]);

            try {
                mediaRecorder = new MediaRecorder(audioOnlyStream, { mimeType: 'audio/webm' });
            } catch (e) {
                console.warn('MediaRecorder init failed, trying default', e);
                mediaRecorder = new MediaRecorder(audioOnlyStream);
            }

            const chunkMs = 2000;

            mediaRecorder.addEventListener('dataavailable', async (event) => {
                if (!event.data || event.data.size === 0) return;
                if (!isAudioEnabled) return;

                const form = new FormData();
                form.append('audio', event.data, `chunk_${Date.now()}.webm`);
                form.append('duration_ms', String(chunkMs));
                form.append('direction', 'custom');

                try {
                    const res = await fetch(AUDIO_URL, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        },
                        body: form,
                    });

                    const payload = await res.json().catch(() => null);
                    if (!res.ok || !payload?.ok) {
                        console.error('Audio upload failed', payload);
                        return;
                    }

                    // Show what you said and how it was translated for the other participant
                    addTranslation(payload.source_text || '', payload.translated_text || '', 'source');
                } catch (err) {
                    console.error('Audio upload error', err);
                }
            });

            mediaRecorder.start(chunkMs);
        }

        function startPollingTurns() {
            pollInterval = setInterval(async () => {
                try {
                    const res = await fetch(POLL_URL, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        }
                    });
                    if (!res.ok) return;
                    const payload = await res.json();
                    if (!payload?.ok) return;

                    // New turns are returned newest-first
                    const turns = payload.turns || [];
                    const newest = turns.length ? turns[0].id : 0;
                    if (newest <= lastSeenTurnId) return;

                    // Process in chronological order for playback
                    const newTurns = turns
                        .filter(t => t.id > lastSeenTurnId)
                        .sort((a, b) => a.id - b.id);

                    for (const t of newTurns) {
                        // Only play/display turns from the other participant
                        if (t.user_id && t.user_id === CURRENT_USER_ID) continue;

                        addTranslation(t.source_text || '', t.translated_text || '', 'target');

                        if (t.translated_audio_url) {
                            const audio = new Audio(t.translated_audio_url);
                            audio.play().catch(() => {});
                        }
                    }

                    lastSeenTurnId = newest;
                } catch (e) {
                    // ignore transient
                }
            }, 1500);
        }

        // Toggle video
        function toggleVideo() {
            isVideoEnabled = !isVideoEnabled;
            localStream.getVideoTracks().forEach(track => {
                track.enabled = isVideoEnabled;
            });
            document.getElementById('toggle-video').classList.toggle('active', !isVideoEnabled);
        }

        // Toggle audio
        function toggleAudio() {
            isAudioEnabled = !isAudioEnabled;
            localStream.getAudioTracks().forEach(track => {
                track.enabled = isAudioEnabled;
            });
            document.getElementById('toggle-audio').classList.toggle('active', !isAudioEnabled);
            
            if (!isAudioEnabled) {
                document.getElementById('local-audio-bars').style.opacity = '0.3';
            } else {
                document.getElementById('local-audio-bars').style.opacity = '1';
            }
        }

        // End call
        function endCall() {
            if (confirm('End this call?')) {
                if (localStream) {
                    localStream.getTracks().forEach(track => track.stop());
                }
                if (peerConnection) {
                    peerConnection.close();
                }
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                }
                if (pollInterval) {
                    clearInterval(pollInterval);
                }
                clearInterval(durationInterval);
                window.location.href = '/dashboard';
            }
        }

        // Update latency indicator
        function updateLatency(ms) {
            const indicator = document.getElementById('latency-indicator');
            const value = document.getElementById('latency-value');
            
            indicator.className = 'latency-indicator';
            
            if (ms < 300) {
                indicator.classList.add('latency-good');
                value.textContent = `${ms}ms - Excellent`;
            } else if (ms < 500) {
                indicator.classList.add('latency-medium');
                value.textContent = `${ms}ms - Good`;
            } else {
                indicator.classList.add('latency-bad');
                value.textContent = `${ms}ms - Poor`;
            }
        }

        // Start call duration timer
        function startCallDuration() {
            durationInterval = setInterval(() => {
                const elapsed = Math.floor((Date.now() - callStartTime) / 1000);
                const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
                const seconds = (elapsed % 60).toString().padStart(2, '0');
                document.getElementById('call-duration').textContent = `${minutes}:${seconds}`;
            }, 1000);
        }

        // Add translation to display
        function addTranslation(source, target, direction) {
            const container = document.getElementById('translation-display');
            
            // Clear placeholder
            if (container.querySelector('p.text-center')) {
                container.innerHTML = '';
            }
            
            const item = document.createElement('div');
            item.className = 'bg-white/5 rounded-lg p-3 text-sm';
            item.innerHTML = `
                <div class="text-white/60 mb-1">${direction === 'source' ? 'You' : 'Them'}:</div>
                <div class="text-white">${source}</div>
                <div class="text-white/40 text-xs mt-1">→ ${target}</div>
            `;
            
            container.insertBefore(item, container.firstChild);
            
            // Keep only last 5 translations
            while (container.children.length > 5) {
                container.removeChild(container.lastChild);
            }
        }

        // Simulate latency updates (placeholder)
        setInterval(() => {
            updateLatency(Math.floor(Math.random() * 300) + 100);
        }, 3000);
    </script>
</body>
</html>
