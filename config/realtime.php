<?php

return [
    // Simple limits
    'max_participants' => env('REALTIME_MAX_PARTICIPANTS', 8),
    'max_session_minutes' => env('REALTIME_MAX_SESSION_MINUTES', 120),

    // Cluster configuration for large-scale deployments
    'cluster' => [
        'enabled' => env('REALTIME_CLUSTER_ENABLED', true),
        'nodes' => env('REALTIME_CLUSTER_NODES', 3),
        'max_sessions_per_node' => env('REALTIME_MAX_SESSIONS_PER_NODE', 250),
        'autoscale' => [
            'enabled' => env('REALTIME_AUTOSCALE_ENABLED', false),
            'cpu_threshold' => env('REALTIME_AUTOSCALE_CPU', 75),
            'latency_ms_threshold' => env('REALTIME_AUTOSCALE_LATENCY', 1200),
            'scale_out_step' => env('REALTIME_AUTOSCALE_STEP', 1),
            'max_nodes' => env('REALTIME_AUTOSCALE_MAX_NODES', 12),
            'scale_out_threshold' => env('REALTIME_SCALE_OUT_THRESHOLD', 1000),
            'webhook_url' => env('REALTIME_AUTOSCALE_WEBHOOK'),
        ],
    ],

    // Audio pipeline
    'audio' => [
        'chunk_ms' => env('REALTIME_AUDIO_CHUNK_MS', 800),
        'codec' => env('REALTIME_AUDIO_CODEC', 'opus'),
        'max_concurrent_streams' => env('REALTIME_MAX_CONCURRENT_STREAMS', 5000),
    ],

    // Worker pools
    'workers' => [
        'ingest_workers' => env('REALTIME_INGEST_WORKERS', 4),
        'asr_workers' => env('REALTIME_ASR_WORKERS', 6),
        'translation_workers' => env('REALTIME_TRANSLATION_WORKERS', 4),
        'tts_workers' => env('REALTIME_TTS_WORKERS', 3),
    ],

    // Queue names for decoupled micro-pipeline
    'queues' => [
        'ingest' => env('REALTIME_QUEUE_INGEST', 'rt_ingest'),
        'asr' => env('REALTIME_QUEUE_ASR', 'rt_asr'),
        'translation' => env('REALTIME_QUEUE_TRANSLATION', 'rt_translation'),
        'tts' => env('REALTIME_QUEUE_TTS', 'rt_tts'),
    ],

    // Metrics sampling
    'metrics' => [
        'enabled' => env('REALTIME_METRICS_ENABLED', true),
        'sample_rate' => env('REALTIME_METRICS_SAMPLE_RATE', 1.0),
    ],

    // Local billing / subscription tokens
    'billing' => [
        // Token cost for live voice call translation (approximate; client sends duration_ms per chunk)
        'voice_tokens_per_second' => env('REALTIME_VOICE_TOKENS_PER_SECOND', 1),
    ],
];
