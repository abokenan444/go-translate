{{-- resources/views/realtime/meeting.blade.php --}}
@extends('layouts.app')

@section('title', $session->title ?? 'Real-Time Cultural Meeting')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-50 flex flex-col">
    <div class="max-w-5xl mx-auto w-full px-4 py-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">
                    🎧 Cultural Live Meeting
                </h1>
                <p class="text-sm text-slate-400">
                    Session: {{ $session->title ?? 'Untitled' }} –
                    ID: {{ $session->public_id }} –
                    {{ strtoupper($session->source_language) }} → {{ strtoupper($session->target_language) }}
                </p>
            </div>
            <div class="text-xs text-slate-400">
                Owner: #{{ $session->owner_id }}<br>
                Started: {{ optional($session->started_at)->format('Y-m-d H:i') ?? '—' }}
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            {{-- Transcript + last audio --}}
            <div class="md:col-span-2 space-y-4">
                <div class="bg-slate-900/70 rounded-xl border border-slate-800 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h2 class="font-semibold text-slate-100 text-sm">Live Transcript</h2>
                            <p class="text-xs text-slate-400">
                                سيتم عرض النص الأصلي والترجمة هنا، مع التحديث اللحظي إذا كان WebSocket مفعلاً.
                            </p>
                        </div>
                    </div>
                    <div id="transcript" class="h-72 overflow-y-auto text-sm space-y-2 pr-2">
                        {{-- سيتم حقن الرسائل هنا --}}
                    </div>
                </div>

                <div class="bg-slate-900/70 rounded-xl border border-slate-800 p-4">
                    <h2 class="font-semibold text-sm mb-2">آخر ترجمة صوتية</h2>
                    <audio id="translated-audio" controls class="w-full">
                        متصفحك لا يدعم الصوت.
                    </audio>
                </div>
            </div>

            {{-- Controls --}}
            <div class="space-y-4">
                <div class="bg-slate-900/70 rounded-xl border border-slate-800 p-4">
                    <h2 class="font-semibold text-sm mb-2">التحكم بالصوت</h2>
                    <div class="space-y-3">
                        <button id="btn-start"
                                class="w-full rounded-lg py-2 text-sm font-medium bg-emerald-600 hover:bg-emerald-500">
                            🎙️ بدء الترجمة الحية
                        </button>
                        <button id="btn-stop"
                                class="w-full rounded-lg py-2 text-sm font-medium bg-red-600 hover:bg-red-500"
                                disabled>
                            ⏹️ إيقاف
                        </button>

                        <div class="text-xs text-slate-400 pt-2">
                            سيتم إرسال صوتك كل ~2 ثانية إلى الخادم،
                            تحويله لنص، ترجمته ثقافيًا، ثم إرجاع صوت مترجم + نص في الأسفل.
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/70 rounded-xl border border-slate-800 p-4 text-xs text-slate-400 space-y-1">
                    <div>Type: {{ $session->type }}</div>
                    <div>Bi-directional: {{ $session->bi_directional ? 'Yes' : 'No' }}</div>
                    <div>Record transcript: {{ $session->record_transcript ? 'Yes' : 'No' }}</div>
                    <div>Max participants: {{ $session->max_participants }}</div>
                </div>

                <div class="bg-slate-900/70 rounded-xl border border-slate-800 p-4 text-xs text-slate-400">
                    <a href="{{ route('realtime.meeting.video', $session->public_id) }}"
                       class="inline-flex items-center gap-2 text-emerald-400 hover:text-emerald-300">
                        🎥 الانتقال إلى الاجتماع المرئي (تجريبي)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
    const sessionPublicId = @json($session->public_id);
    const audioEndpoint = @json(route('realtime.sessions.audio', $session->public_id));
    const pollEndpoint  = @json(route('realtime.sessions.poll', $session->public_id));

    let mediaRecorder = null;
    let chunks = [];
    let pollingTimer = null;

    const btnStart = document.getElementById('btn-start');
    const btnStop = document.getElementById('btn-stop');
    const transcriptEl = document.getElementById('transcript');
    const audioEl = document.getElementById('translated-audio');

    btnStart.addEventListener('click', startRecording);
    btnStop.addEventListener('click', stopRecording);

    async function startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });

            mediaRecorder.ondataavailable = (e) => {
                if (e.data.size > 0) {
                    chunks.push(e.data);
                }
            };

            mediaRecorder.onstop = async () => {
                if (!chunks.length) return;
                const blob = new Blob(chunks, { type: 'audio/webm' });
                chunks = [];
                await sendChunk(blob);
            };

            // إرسال كل ثانيتين
            mediaRecorder.start(2000);

            btnStart.disabled = true;
            btnStop.disabled = false;

            startPolling();

        } catch (error) {
            alert('خطأ في الوصول إلى الميكروفون');
            console.error(error);
        }
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }
        btnStart.disabled = false;
        btnStop.disabled = true;
        stopPolling();
    }

    async function sendChunk(blob) {
        const formData = new FormData();
        formData.append('audio', blob, 'chunk.webm');
        formData.append('direction', 'source_to_target');

        try {
            const response = await fetch(audioEndpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: formData
            });

            if (!response.ok) {
                console.error('خطأ في إرسال الصوت', await response.text());
                return;
            }

            const data = await response.json();
            if (data.ok) {
                appendTurnToTranscript(data);
                if (data.translated_audio_url) {
                    audioEl.src = data.translated_audio_url;
                    audioEl.play().catch(() => {});
                }
            }

        } catch (error) {
            console.error('خطأ شبكة', error);
        }
    }

    function appendTurnToTranscript(turnData) {
        const div = document.createElement('div');
        div.className = 'bg-slate-900/60 border border-slate-800 rounded-lg p-2';

        div.innerHTML = `
            <div class="text-[11px] text-slate-400 mb-1">
                Direction: ${turnData.direction || 'source_to_target'} –
                Latency: ${turnData.latency_ms ?? '—'} ms
            </div>
            <div class="text-xs text-slate-300 mb-1">
                <span class="text-slate-500">Source:</span>
                ${turnData.source_text || '—'}
            </div>
            <div class="text-xs text-emerald-300">
                <span class="text-emerald-500">Translated:</span>
                ${turnData.translated_text || '—'}
            </div>
        `;

        transcriptEl.appendChild(div);
        transcriptEl.scrollTop = transcriptEl.scrollHeight;
    }

    function startPolling() {
        if (pollingTimer) return;
        pollingTimer = setInterval(async () => {
            try {
                const res = await fetch(pollEndpoint);
                if (!res.ok) return;
                const data = await res.json();
                if (data.ok && Array.isArray(data.turns)) {
                    transcriptEl.innerHTML = '';
                    data.turns.slice().reverse().forEach(turn => {
                        appendTurnToTranscript({
                            direction: turn.direction,
                            latency_ms: turn.latency_ms,
                            source_text: turn.source_text,
                            translated_text: turn.translated_text,
                        });
                    });
                }
            } catch (e) {
                console.error('Polling error', e);
            }
        }, 5000);
    }

    function stopPolling() {
        if (pollingTimer) {
            clearInterval(pollingTimer);
            pollingTimer = null;
        }
    }

    // تكامل WebSocket (اختياري) - يلغي الحاجة لكثرة الـ polling
    // يتطلب إعداد Laravel Echo + Pusher أو Laravel WebSockets
    if (window.Echo) {
        window.Echo.channel('realtime.sessions.' + sessionPublicId)
            .listen('.realtime.turn.created', (e) => {
                appendTurnToTranscript({
                    direction: e.turn.direction,
                    latency_ms: e.turn.latency_ms,
                    source_text: e.turn.source_text,
                    translated_text: e.turn.translated_text,
                });
            });
    }
</script>
@endsection
