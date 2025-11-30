{{-- resources/views/realtime/meeting-video.blade.php --}}
@extends('layouts.app')

@section('title', ($session->title ?? 'Video Meeting') . ' – Video Conference')

@push('styles')
<style>
    .video-container {
        position: relative;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 16/9;
    }
    
    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .participant-name {
        position: absolute;
        bottom: 12px;
        left: 12px;
        background: rgba(0, 0, 0, 0.7);
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .video-controls {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 20px;
        display: flex;
        justify-content: center;
        gap: 12px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .video-container:hover .video-controls {
        opacity: 1;
    }
    
    .control-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .control-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }
    
    .control-btn.active {
        background: #ef4444;
    }
    
    .participant-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
    
    .connection-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .connection-status.connected {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }
    
    .connection-status.connecting {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
    }
    
    .connection-status.disconnected {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    .transcript-item {
        padding: 12px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold mb-1">🎥 {{ $session->title ?? 'Video Conference' }}</h1>
                <p class="text-sm text-slate-400">
                    Session ID: {{ $session->public_id }} • 
                    {{ strtoupper($session->source_language) }} ⇆ {{ strtoupper($session->target_language) }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span id="connection-status" class="connection-status connecting">
                    <span class="inline-block w-2 h-2 rounded-full bg-current animate-pulse"></span>
                    Connecting...
                </span>
                <button onclick="window.location.href='{{ route('realtime.meeting.show', $session->public_id) }}'" 
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm transition">
                    Audio Only
                </button>
                <button onclick="leaveSession()" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm transition">
                    Leave
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 gap-6">
            {{-- Main Video Area --}}
            <div class="lg:col-span-3 space-y-4">
                {{-- Active Speaker / Screen Share --}}
                <div class="video-container" style="aspect-ratio: 16/9; min-height: 400px;">
                    <video id="main-video" autoplay playsinline class="w-full h-full"></video>
                    <div class="participant-name" id="main-participant-name">You</div>
                    <div class="video-controls">
                        <button id="toggle-video" class="control-btn" title="Toggle Camera">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button id="toggle-audio" class="control-btn" title="Toggle Microphone">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </button>
                        <button id="share-screen" class="control-btn" title="Share Screen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <button id="toggle-fullscreen" class="control-btn" title="Fullscreen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Participant Grid --}}
                <div class="participant-grid" id="participants-grid">
                    {{-- Participants will be added dynamically --}}
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                {{-- Participants List --}}
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl border border-slate-800 p-4">
                    <h3 class="text-lg font-semibold mb-3 flex items-center justify-between">
                        <span>👥 Participants</span>
                        <span id="participant-count" class="text-sm text-slate-400">1</span>
                    </h3>
                    <div id="participants-list" class="space-y-2">
                        {{-- Will be populated dynamically --}}
                    </div>
                </div>

                {{-- Translation Transcript --}}
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl border border-slate-800 p-4">
                    <h3 class="text-lg font-semibold mb-3">📝 Translations</h3>
                    <div id="transcript-container" class="space-y-2 max-h-96 overflow-y-auto">
                        <p class="text-sm text-slate-500 text-center py-4">Waiting for translations...</p>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl border border-slate-800 p-4">
                    <h3 class="text-lg font-semibold mb-3">⚙️ Settings</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-slate-400 block mb-1">Camera</label>
                            <select id="camera-select" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                                <option>Loading...</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-slate-400 block mb-1">Microphone</label>
                            <select id="microphone-select" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                                <option>Loading...</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-slate-400 block mb-1">Speaker</label>
                            <select id="speaker-select" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                                <option>Loading...</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// WebRTC Configuration
const configuration = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' }
    ]
};

// Global variables
let localStream = null;
let screenStream = null;
let peerConnections = {};
let isVideoEnabled = true;
let isAudioEnabled = true;
let isScreenSharing = false;

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    await initializeMedia();
    await loadDevices();
    connectToSignalingServer();
    setupEventListeners();
});

// Initialize media devices
async function initializeMedia() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({
            video: { width: 1280, height: 720 },
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true
            }
        });
        
        document.getElementById('main-video').srcObject = localStream;
        updateConnectionStatus('connected', 'Connected');
    } catch (error) {
        console.error('Error accessing media devices:', error);
        alert('Could not access camera/microphone. Please check permissions.');
        updateConnectionStatus('disconnected', 'Failed to connect');
    }
}

// Load available devices
async function loadDevices() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        
        const cameras = devices.filter(d => d.kind === 'videoinput');
        const microphones = devices.filter(d => d.kind === 'audioinput');
        const speakers = devices.filter(d => d.kind === 'audiooutput');
        
        populateDeviceSelect('camera-select', cameras);
        populateDeviceSelect('microphone-select', microphones);
        populateDeviceSelect('speaker-select', speakers);
    } catch (error) {
        console.error('Error loading devices:', error);
    }
}

function populateDeviceSelect(selectId, devices) {
    const select = document.getElementById(selectId);
    select.innerHTML = '';
    
    devices.forEach((device, index) => {
        const option = document.createElement('option');
        option.value = device.deviceId;
        option.text = device.label || `Device ${index + 1}`;
        select.appendChild(option);
    });
}

// Setup event listeners
function setupEventListeners() {
    document.getElementById('toggle-video').addEventListener('click', toggleVideo);
    document.getElementById('toggle-audio').addEventListener('click', toggleAudio);
    document.getElementById('share-screen').addEventListener('click', toggleScreenShare);
    document.getElementById('toggle-fullscreen').addEventListener('click', toggleFullscreen);
    
    document.getElementById('camera-select').addEventListener('change', changeCamera);
    document.getElementById('microphone-select').addEventListener('change', changeMicrophone);
}

// Toggle video
function toggleVideo() {
    isVideoEnabled = !isVideoEnabled;
    localStream.getVideoTracks().forEach(track => {
        track.enabled = isVideoEnabled;
    });
    
    const btn = document.getElementById('toggle-video');
    btn.classList.toggle('active', !isVideoEnabled);
}

// Toggle audio
function toggleAudio() {
    isAudioEnabled = !isAudioEnabled;
    localStream.getAudioTracks().forEach(track => {
        track.enabled = isAudioEnabled;
    });
    
    const btn = document.getElementById('toggle-audio');
    btn.classList.toggle('active', !isAudioEnabled);
}

// Toggle screen share
async function toggleScreenShare() {
    if (!isScreenSharing) {
        try {
            screenStream = await navigator.mediaDevices.getDisplayMedia({
                video: { cursor: 'always' },
                audio: false
            });
            
            document.getElementById('main-video').srcObject = screenStream;
            document.getElementById('main-participant-name').textContent = 'Your Screen';
            isScreenSharing = true;
            
            document.getElementById('share-screen').classList.add('active');
            
            screenStream.getVideoTracks()[0].onended = () => {
                stopScreenShare();
            };
        } catch (error) {
            console.error('Error sharing screen:', error);
        }
    } else {
        stopScreenShare();
    }
}

function stopScreenShare() {
    if (screenStream) {
        screenStream.getTracks().forEach(track => track.stop());
        screenStream = null;
    }
    
    document.getElementById('main-video').srcObject = localStream;
    document.getElementById('main-participant-name').textContent = 'You';
    isScreenSharing = false;
    document.getElementById('share-screen').classList.remove('active');
}

// Toggle fullscreen
function toggleFullscreen() {
    const container = document.querySelector('.video-container');
    
    if (!document.fullscreenElement) {
        container.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

// Change camera
async function changeCamera() {
    const deviceId = document.getElementById('camera-select').value;
    
    try {
        const newStream = await navigator.mediaDevices.getUserMedia({
            video: { deviceId: { exact: deviceId } },
            audio: false
        });
        
        const videoTrack = newStream.getVideoTracks()[0];
        const sender = Object.values(peerConnections)[0]?.getSenders()
            .find(s => s.track?.kind === 'video');
        
        if (sender) {
            sender.replaceTrack(videoTrack);
        }
        
        localStream.getVideoTracks().forEach(track => track.stop());
        localStream.removeTrack(localStream.getVideoTracks()[0]);
        localStream.addTrack(videoTrack);
        
        document.getElementById('main-video').srcObject = localStream;
    } catch (error) {
        console.error('Error changing camera:', error);
    }
}

// Change microphone
async function changeMicrophone() {
    const deviceId = document.getElementById('microphone-select').value;
    
    try {
        const newStream = await navigator.mediaDevices.getUserMedia({
            video: false,
            audio: { deviceId: { exact: deviceId } }
        });
        
        const audioTrack = newStream.getAudioTracks()[0];
        const sender = Object.values(peerConnections)[0]?.getSenders()
            .find(s => s.track?.kind === 'audio');
        
        if (sender) {
            sender.replaceTrack(audioTrack);
        }
        
        localStream.getAudioTracks().forEach(track => track.stop());
        localStream.removeTrack(localStream.getAudioTracks()[0]);
        localStream.addTrack(audioTrack);
    } catch (error) {
        console.error('Error changing microphone:', error);
    }
}

// Connect to signaling server (WebSocket)
function connectToSignalingServer() {
    // This will use Laravel Reverb WebSockets
    const ws = new WebSocket('ws://{{ request()->getHost() }}:8080/app/{{ env('REVERB_APP_KEY') }}');
    
    ws.onopen = () => {
        console.log('Connected to signaling server');
        // Join room
        ws.send(JSON.stringify({
            event: 'join-room',
            data: {
                room: '{{ $session->public_id }}',
                userId: '{{ auth()->id() ?? "guest_" . uniqid() }}'
            }
        }));
    };
    
    ws.onmessage = async (event) => {
        const message = JSON.parse(event.data);
        await handleSignalingMessage(message);
    };
    
    ws.onerror = (error) => {
        console.error('WebSocket error:', error);
        updateConnectionStatus('disconnected', 'Connection error');
    };
    
    window.signalingWs = ws;
}

// Handle signaling messages
async function handleSignalingMessage(message) {
    switch (message.event) {
        case 'user-joined':
            await handleUserJoined(message.data);
            break;
        case 'offer':
            await handleOffer(message.data);
            break;
        case 'answer':
            await handleAnswer(message.data);
            break;
        case 'ice-candidate':
            await handleIceCandidate(message.data);
            break;
        case 'user-left':
            handleUserLeft(message.data);
            break;
    }
}

// Handle user joined
async function handleUserJoined(data) {
    const { userId } = data;
    
    // Create peer connection
    const pc = new RTCPeerConnection(configuration);
    peerConnections[userId] = pc;
    
    // Add local stream
    localStream.getTracks().forEach(track => {
        pc.addTrack(track, localStream);
    });
    
    // Handle ICE candidates
    pc.onicecandidate = (event) => {
        if (event.candidate) {
            window.signalingWs.send(JSON.stringify({
                event: 'ice-candidate',
                data: {
                    to: userId,
                    candidate: event.candidate
                }
            }));
        }
    };
    
    // Handle remote stream
    pc.ontrack = (event) => {
        addParticipantVideo(userId, event.streams[0]);
    };
    
    // Create and send offer
    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);
    
    window.signalingWs.send(JSON.stringify({
        event: 'offer',
        data: {
            to: userId,
            offer: offer
        }
    }));
    
    updateParticipantCount();
}

// Add participant video
function addParticipantVideo(userId, stream) {
    const grid = document.getElementById('participants-grid');
    
    const container = document.createElement('div');
    container.id = `participant-${userId}`;
    container.className = 'video-container';
    container.innerHTML = `
        <video autoplay playsinline class="w-full h-full"></video>
        <div class="participant-name">Participant ${userId.substring(0, 8)}</div>
    `;
    
    const video = container.querySelector('video');
    video.srcObject = stream;
    
    grid.appendChild(container);
    
    // Add to participants list
    const list = document.getElementById('participants-list');
    const item = document.createElement('div');
    item.id = `participant-list-${userId}`;
    item.className = 'flex items-center gap-2 p-2 bg-slate-800/50 rounded-lg';
    item.innerHTML = `
        <div class="w-2 h-2 rounded-full bg-green-500"></div>
        <span class="text-sm">Participant ${userId.substring(0, 8)}</span>
    `;
    list.appendChild(item);
}

// Update connection status
function updateConnectionStatus(status, text) {
    const statusEl = document.getElementById('connection-status');
    statusEl.className = `connection-status ${status}`;
    statusEl.innerHTML = `
        <span class="inline-block w-2 h-2 rounded-full bg-current ${status === 'connecting' ? 'animate-pulse' : ''}"></span>
        ${text}
    `;
}

// Update participant count
function updateParticipantCount() {
    const count = Object.keys(peerConnections).length + 1;
    document.getElementById('participant-count').textContent = count;
}

// Leave session
function leaveSession() {
    if (confirm('Are you sure you want to leave this meeting?')) {
        // Stop all tracks
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }
        if (screenStream) {
            screenStream.getTracks().forEach(track => track.stop());
        }
        
        // Close peer connections
        Object.values(peerConnections).forEach(pc => pc.close());
        
        // Close WebSocket
        if (window.signalingWs) {
            window.signalingWs.close();
        }
        
        // Redirect
        window.location.href = '/dashboard';
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    leaveSession();
});
</script>
@endpush
@endsection
