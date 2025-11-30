{{-- resources/views/realtime/premium-meeting.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premium Meeting – {{ $session->title ?? 'Cultural Translate' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #0a0a0a;
            color: #fff;
            overflow: hidden;
        }
        
        .meeting-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            height: 100vh;
        }
        
        .video-area {
            position: relative;
            background: #1a1a1a;
            display: flex;
            flex-direction: column;
        }
        
        .video-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 8px;
            padding: 16px;
            overflow-y: auto;
        }
        
        .video-tile {
            position: relative;
            background: #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 16/9;
        }
        
        .video-tile video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .video-tile-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .participant-name {
            font-size: 14px;
            font-weight: 600;
        }
        
        .participant-status {
            display: flex;
            gap: 8px;
        }
        
        .status-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .controls-bar {
            background: #1a1a1a;
            padding: 16px;
            display: flex;
            justify-content: center;
            gap: 12px;
            border-top: 1px solid #2a2a2a;
        }
        
        .control-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: none;
            background: #2a2a2a;
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .control-btn:hover {
            background: #3a3a3a;
            transform: scale(1.05);
        }
        
        .control-btn.active {
            background: #ef4444;
        }
        
        .control-btn.end-call {
            background: #ef4444;
        }
        
        .control-btn.end-call:hover {
            background: #dc2626;
        }
        
        .sidebar {
            background: #1a1a1a;
            border-left: 1px solid #2a2a2a;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-tabs {
            display: flex;
            border-bottom: 1px solid #2a2a2a;
        }
        
        .sidebar-tab {
            flex: 1;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }
        
        .sidebar-tab.active {
            border-bottom-color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }
        
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }
        
        .chat-message {
            margin-bottom: 16px;
            padding: 12px;
            background: #2a2a2a;
            border-radius: 8px;
        }
        
        .chat-sender {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 12px;
            color: #6366f1;
        }
        
        .chat-text {
            font-size: 14px;
            line-height: 1.5;
        }
        
        .chat-translation {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #3a3a3a;
            font-size: 13px;
            color: #9ca3af;
        }
        
        .chat-input-area {
            padding: 16px;
            border-top: 1px solid #2a2a2a;
        }
        
        .chat-input {
            width: 100%;
            padding: 12px;
            background: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
        }
        
        .participant-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #2a2a2a;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        
        .participant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .recording-indicator {
            position: absolute;
            top: 16px;
            left: 16px;
            background: #ef4444;
            padding: 8px 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .recording-dot {
            width: 8px;
            height: 8px;
            background: #fff;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        .meeting-header {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .meeting-time {
            font-size: 14px;
            font-weight: 600;
        }
        
        .quality-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
        }
        
        .quality-bars {
            display: flex;
            gap: 2px;
        }
        
        .quality-bar {
            width: 3px;
            height: 12px;
            background: #22c55e;
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <div class="meeting-container">
        {{-- Video Area --}}
        <div class="video-area">
            {{-- Recording Indicator --}}
            <div class="recording-indicator" id="recording-indicator" style="display: none;">
                <div class="recording-dot"></div>
                <span>Recording</span>
            </div>
            
            {{-- Meeting Header --}}
            <div class="meeting-header">
                <div class="meeting-time" id="meeting-time">00:00</div>
                <div class="quality-indicator">
                    <div class="quality-bars">
                        <div class="quality-bar"></div>
                        <div class="quality-bar"></div>
                        <div class="quality-bar"></div>
                        <div class="quality-bar"></div>
                    </div>
                    <span>HD</span>
                </div>
            </div>
            
            {{-- Video Grid --}}
            <div class="video-grid" id="video-grid">
                {{-- Local Video --}}
                <div class="video-tile">
                    <video id="local-video" autoplay playsinline muted></video>
                    <div class="video-tile-overlay">
                        <span class="participant-name">You</span>
                        <div class="participant-status">
                            <div class="status-icon"><i class="fas fa-microphone"></i></div>
                            <div class="status-icon"><i class="fas fa-video"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Controls Bar --}}
            <div class="controls-bar">
                <button class="control-btn" id="toggle-audio" title="Microphone">
                    <i class="fas fa-microphone"></i>
                </button>
                <button class="control-btn" id="toggle-video" title="Camera">
                    <i class="fas fa-video"></i>
                </button>
                <button class="control-btn" id="share-screen" title="Share Screen">
                    <i class="fas fa-desktop"></i>
                </button>
                <button class="control-btn" id="toggle-recording" title="Record">
                    <i class="fas fa-circle"></i>
                </button>
                <button class="control-btn" id="toggle-subtitles" title="Subtitles">
                    <i class="fas fa-closed-captioning"></i>
                </button>
                <button class="control-btn" id="settings" title="Settings">
                    <i class="fas fa-cog"></i>
                </button>
                <button class="control-btn end-call" id="end-call" title="End Call">
                    <i class="fas fa-phone-slash"></i>
                </button>
            </div>
        </div>
        
        {{-- Sidebar --}}
        <div class="sidebar">
            {{-- Tabs --}}
            <div class="sidebar-tabs">
                <div class="sidebar-tab active" data-tab="chat">
                    <i class="fas fa-comments"></i> Chat
                </div>
                <div class="sidebar-tab" data-tab="participants">
                    <i class="fas fa-users"></i> People
                </div>
            </div>
            
            {{-- Chat Content --}}
            <div class="sidebar-content" id="chat-content">
                <div class="chat-message">
                    <div class="chat-sender">System</div>
                    <div class="chat-text">Welcome to the meeting! Real-time translation is enabled.</div>
                </div>
            </div>
            
            {{-- Participants Content --}}
            <div class="sidebar-content" id="participants-content" style="display: none;">
                <div class="participant-item">
                    <div class="participant-avatar">Y</div>
                    <div>
                        <div style="font-weight: 600;">You</div>
                        <div style="font-size: 12px; color: #9ca3af;">Host</div>
                    </div>
                </div>
            </div>
            
            {{-- Chat Input --}}
            <div class="chat-input-area">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="chat-input" 
                    placeholder="Type a message..."
                >
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.sidebar-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.sidebar-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                const tabName = tab.dataset.tab;
                document.getElementById('chat-content').style.display = tabName === 'chat' ? 'block' : 'none';
                document.getElementById('participants-content').style.display = tabName === 'participants' ? 'block' : 'none';
            });
        });
        
        // Meeting timer
        let startTime = Date.now();
        setInterval(() => {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
            const seconds = (elapsed % 60).toString().padStart(2, '0');
            document.getElementById('meeting-time').textContent = `${minutes}:${seconds}`;
        }, 1000);
        
        // Controls
        let isAudioEnabled = true;
        let isVideoEnabled = true;
        let isRecording = false;
        let isScreenSharing = false;
        
        document.getElementById('toggle-audio').addEventListener('click', function() {
            isAudioEnabled = !isAudioEnabled;
            this.classList.toggle('active', !isAudioEnabled);
            this.querySelector('i').className = isAudioEnabled ? 'fas fa-microphone' : 'fas fa-microphone-slash';
        });
        
        document.getElementById('toggle-video').addEventListener('click', function() {
            isVideoEnabled = !isVideoEnabled;
            this.classList.toggle('active', !isVideoEnabled);
            this.querySelector('i').className = isVideoEnabled ? 'fas fa-video' : 'fas fa-video-slash';
        });
        
        document.getElementById('share-screen').addEventListener('click', function() {
            isScreenSharing = !isScreenSharing;
            this.classList.toggle('active', isScreenSharing);
        });
        
        document.getElementById('toggle-recording').addEventListener('click', function() {
            isRecording = !isRecording;
            this.classList.toggle('active', isRecording);
            document.getElementById('recording-indicator').style.display = isRecording ? 'flex' : 'none';
        });
        
        document.getElementById('end-call').addEventListener('click', () => {
            if (confirm('End this meeting?')) {
                window.location.href = '/dashboard';
            }
        });
        
        // Chat
        document.getElementById('chat-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && e.target.value.trim()) {
                const message = e.target.value.trim();
                addChatMessage('You', message);
                e.target.value = '';
                
                // Simulate translation
                setTimeout(() => {
                    addChatMessage('You', message, 'أنت: ' + message);
                }, 500);
            }
        });
        
        function addChatMessage(sender, text, translation = null) {
            const chatContent = document.getElementById('chat-content');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message';
            messageDiv.innerHTML = `
                <div class="chat-sender">${sender}</div>
                <div class="chat-text">${text}</div>
                ${translation ? `<div class="chat-translation">Translation: ${translation}</div>` : ''}
            `;
            chatContent.appendChild(messageDiv);
            chatContent.scrollTop = chatContent.scrollHeight;
        }
        
        // Initialize media
        async function initMedia() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                });
                document.getElementById('local-video').srcObject = stream;
            } catch (error) {
                console.error('Error accessing media:', error);
            }
        }
        
        initMedia();
    </script>
</body>
</html>
