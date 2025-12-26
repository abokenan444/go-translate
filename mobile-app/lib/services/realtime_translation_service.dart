import 'dart:async';
import 'dart:io';

import 'package:audioplayers/audioplayers.dart';
import 'package:flutter_webrtc/flutter_webrtc.dart';
import 'package:path_provider/path_provider.dart';

import '../models/call_model.dart';
import '../services/api_service.dart';

class RealtimeTranslationService {
  static final RealtimeTranslationService _instance =
      RealtimeTranslationService._internal();
  factory RealtimeTranslationService() => _instance;
  RealtimeTranslationService._internal();

  final AudioPlayer _player = AudioPlayer();
  MediaRecorder? _recorder;
  MediaStream? _localStream;

  Timer? _recordTimer;
  Timer? _pollTimer;

  bool _running = false;
  String? _sessionId;
  int? _myUserId;
  int _lastSeenTurnId = 0;

  String? _sendLang;
  String? _receiveLang;
  void Function()? _onInsufficientMinutes;
  void Function(int remainingMinutes, int thresholdMinutes)? _onLowMinutes;
  bool _lowBalanceNotified = false;

  Future<void> startForCall({
    required CallModel call,
    required int myUserId,
    required String displayName,
    String? sendLanguageOverride,
    String? receiveLanguageOverride,
    void Function()? onInsufficientMinutes,
    void Function(int remainingMinutes, int thresholdMinutes)? onLowMinutes,
  }) async {
    final sessionId = call.realtimeSessionId;
    if (sessionId == null || sessionId.isEmpty) {
      return;
    }

    if (_running && _sessionId == sessionId) {
      return;
    }

    await stop();

    _running = true;
    _sessionId = sessionId;
    _myUserId = myUserId;
    _lastSeenTurnId = 0;
    _onInsufficientMinutes = onInsufficientMinutes;
    _onLowMinutes = onLowMinutes;
    _lowBalanceNotified = false;

    // Load user language prefs from mobile settings unless overridden for this call.
    final settings = await ApiService().getMobileSettings();
    final settingsSend =
        (settings['default_send_language']?.toString().trim().isNotEmpty ??
                false)
            ? settings['default_send_language'].toString()
            : 'auto';
    final settingsReceive =
        (settings['default_receive_language']?.toString().trim().isNotEmpty ??
                false)
            ? settings['default_receive_language'].toString()
            : 'en';

    _sendLang =
        (sendLanguageOverride != null && sendLanguageOverride.trim().isNotEmpty)
            ? sendLanguageOverride.trim()
            : settingsSend;
    _receiveLang = (receiveLanguageOverride != null &&
            receiveLanguageOverride.trim().isNotEmpty)
        ? receiveLanguageOverride.trim()
        : settingsReceive;

    // Join realtime session (required for language routing).
    await ApiService().joinRealtimeSession(
      publicId: sessionId,
      displayName: displayName,
      sendLanguage: _sendLang,
      receiveLanguage: _receiveLang,
    );

    // Initialize audio recording
    await _initRecorder();

    // Start loops
    _startRecordingLoop();
    _startPollingLoop();
  }

  Future<void> _initRecorder() async {
    try {
      _localStream = await navigator.mediaDevices.getUserMedia({
        'audio': true,
        'video': false,
      });
    } catch (e) {
      // Handle permission error
    }
  }

  Future<void> stop() async {
    _running = false;
    _recordTimer?.cancel();
    _pollTimer?.cancel();
    _recordTimer = null;
    _pollTimer = null;

    try {
      await _recorder?.stop();
      _recorder = null;
    } catch (_) {}

    try {
      _localStream?.getTracks().forEach((track) => track.stop());
      _localStream = null;
    } catch (_) {}

    try {
      await _player.stop();
    } catch (_) {}

    _sessionId = null;
    _myUserId = null;
    _lastSeenTurnId = 0;
    _sendLang = null;
    _receiveLang = null;
    _onInsufficientMinutes = null;
    _onLowMinutes = null;
    _lowBalanceNotified = false;
  }

  void _startRecordingLoop() {
    // Record short chunks; server charges per second based on duration_ms.
    // Optimized for low latency while maintaining translation quality
    const chunkMs = 1200; // Reduced from 1800ms for faster response

    _recordTimer =
        Timer.periodic(const Duration(milliseconds: chunkMs + 100), (_) async {
      if (!_running) return;
      final sessionId = _sessionId;
      if (sessionId == null) return;

      try {
        if (_localStream == null) {
          await _initRecorder();
          if (_localStream == null) return;
        }

        final dir = await getTemporaryDirectory();
        final filePath =
            '${dir.path}/rt_chunk_${DateTime.now().millisecondsSinceEpoch}.webm';

        // Start recording using WebRTC MediaRecorder
        _recorder = MediaRecorder();
        await _recorder!.start(filePath);

        await Future<void>.delayed(const Duration(milliseconds: chunkMs));

        await _recorder!.stop();
        if (!_running) return;

        final f = File(filePath);
        if (!await f.exists()) return;
        if (await f.length() < 512) {
          await f.delete().catchError((_) => f);
          return;
        }

        // Upload chunk (this produces translated audio for the OTHER participant).
        final res = await ApiService().sendRealtimeAudioChunk(
          publicId: sessionId,
          filePath: filePath,
          durationMs: chunkMs,
          direction: 'mobile',
        );

        if (res['ok'] == true) {
          final remaining = (res['wallet_balance_minutes'] is num)
              ? (res['wallet_balance_minutes'] as num).toInt()
              : int.tryParse(res['wallet_balance_minutes']?.toString() ?? '');
          final threshold = (res['low_balance_warning_minutes'] is num)
              ? (res['low_balance_warning_minutes'] as num).toInt()
              : int.tryParse(
                  res['low_balance_warning_minutes']?.toString() ?? '');

          if (remaining != null && threshold != null && threshold > 0) {
            if (remaining <= threshold) {
              if (!_lowBalanceNotified) {
                _lowBalanceNotified = true;
                _onLowMinutes?.call(remaining, threshold);
              }
            } else {
              _lowBalanceNotified = false;
            }
          }
        }

        // We intentionally do NOT play our own translated audio.
        // Other side will poll and play it.
        if (res['ok'] != true) {
          // If minutes exhausted or server rejects, stop loops.
          if (res['message']?.toString().contains('No minutes') == true) {
            final cb = _onInsufficientMinutes;
            _onInsufficientMinutes = null;
            cb?.call();
            await stop();
          }
        }

        await f.delete().catchError((_) => f);
      } catch (_) {
        // Keep running; transient errors are expected on some devices.
      }
    });
  }

  void _startPollingLoop() {
    // Poll for translated audio from the other participant
    // Reduced from 900ms to 500ms for faster response
    _pollTimer = Timer.periodic(const Duration(milliseconds: 500), (_) async {
      if (!_running) return;
      final sessionId = _sessionId;
      final myUserId = _myUserId;
      if (sessionId == null || myUserId == null) return;

      try {
        final turns = await ApiService().pollRealtimeTurns(publicId: sessionId);
        // turns are ordered latest first; process in ascending id order.
        final newTurns = turns
            .where((t) =>
                (t['id'] is int
                    ? (t['id'] as int)
                    : int.tryParse('${t['id']}') ?? 0) >
                _lastSeenTurnId)
            .toList();

        newTurns.sort((a, b) {
          final ai =
              a['id'] is int ? a['id'] as int : int.tryParse('${a['id']}') ?? 0;
          final bi =
              b['id'] is int ? b['id'] as int : int.tryParse('${b['id']}') ?? 0;
          return ai.compareTo(bi);
        });

        for (final turn in newTurns) {
          final turnId = turn['id'] is int
              ? turn['id'] as int
              : int.tryParse('${turn['id']}') ?? 0;
          _lastSeenTurnId = turnId;

          final speakerId = turn['user_id'] is int
              ? turn['user_id'] as int
              : int.tryParse('${turn['user_id']}');
          // We only play translations generated from the OTHER participant.
          if (speakerId == null || speakerId == myUserId) continue;

          final audioUrl = turn['translated_audio_url']?.toString();
          if (audioUrl == null || audioUrl.isEmpty) continue;

          // Play translated audio sequentially.
          await _player.play(UrlSource(audioUrl));
        }
      } catch (_) {
        // ignore
      }
    });
  }
}
