import 'package:flutter_webrtc/flutter_webrtc.dart';
import 'package:flutter/foundation.dart';
import 'package:socket_io_client/socket_io_client.dart' as io;
import '../config/app_config.dart';
import '../models/call_model.dart';
import '../services/api_service.dart';

class WebRTCService {
  static final WebRTCService _instance = WebRTCService._internal();
  factory WebRTCService() => _instance;
  WebRTCService._internal();

  io.Socket? _socket;
  RTCPeerConnection? _peerConnection;
  MediaStream? _localStream;
  MediaStream? _remoteStream;

  final Map<String, RTCSessionDescription> _pendingOffers = {};

  String? _currentCallId;
  String? _currentRealtimeSessionId;
  Function(CallModel)? onIncomingCall;
  Function(MediaStream)? onRemoteStream;
  Function()? onCallEnded;

  Future<void> init(String token) async {
    if (_socket != null && (_socket!.connected)) {
      return;
    }
    _socket = io.io(
        AppConfig.wsUrl,
        io.OptionBuilder()
            .setTransports(['websocket']).setAuth({'token': token}).build());

    _socket!.onConnect((_) {
      debugPrint('WebSocket Connected');
    });

    _socket!.on('incoming-call', (data) {
      if (onIncomingCall != null) {
        final payload = (data is Map)
            ? Map<String, dynamic>.from(data)
            : <String, dynamic>{};
        final call = CallModel.fromJson(payload);
        onIncomingCall!(call);
      }
    });

    // Incoming offer for callee
    _socket!.on('call', (data) {
      final payload =
          (data is Map) ? Map<String, dynamic>.from(data) : <String, dynamic>{};

      final rawCallId =
          payload['callId'] ?? payload['call_id'] ?? payload['id'];
      final callId =
          (rawCallId ?? DateTime.now().millisecondsSinceEpoch).toString();

      // Track realtime translation session id (if provided)
      final rawSessionId = payload['realtimeSessionId'] ??
          payload['realtime_session_id'] ??
          payload['session_id'];
      final sessionId = rawSessionId?.toString();
      if (sessionId != null && sessionId.isNotEmpty) {
        _currentRealtimeSessionId = sessionId;
      }

      // Store offer for answerCall
      final offer = payload['offer'];
      if (offer is Map) {
        final offerMap = Map<String, dynamic>.from(offer);
        final sdp = offerMap['sdp']?.toString();
        final type = offerMap['type']?.toString();
        if (sdp != null && type != null) {
          _pendingOffers[callId] = RTCSessionDescription(sdp, type);
        }
      }

      if (onIncomingCall != null) {
        final call = CallModel.fromJson({
          ...payload,
          'call_id': callId,
          if (sessionId != null) 'realtime_session_id': sessionId,
          'status': 'ringing',
          'start_time': DateTime.now().toIso8601String(),
        });
        onIncomingCall!(call);
      }
    });

    _socket!.on('call-ended', (_) {
      endCall();
    });

    _socket!.on('ice-candidate', (data) async {
      if (_peerConnection != null) {
        final payload = (data is Map)
            ? Map<String, dynamic>.from(data)
            : <String, dynamic>{};
        final cand = payload['candidate'];
        if (cand is Map) {
          final candMap = Map<String, dynamic>.from(cand);
          await _peerConnection!.addCandidate(
            RTCIceCandidate(
              candMap['candidate']?.toString(),
              candMap['sdpMid']?.toString(),
              candMap['sdpMLineIndex'] is int
                  ? candMap['sdpMLineIndex'] as int
                  : int.tryParse('${candMap['sdpMLineIndex']}'),
            ),
          );
        } else {
          await _peerConnection!.addCandidate(
            RTCIceCandidate(
              payload['candidate']?.toString(),
              payload['sdpMid']?.toString(),
              payload['sdpMLineIndex'] is int
                  ? payload['sdpMLineIndex'] as int
                  : int.tryParse('${payload['sdpMLineIndex']}'),
            ),
          );
        }
      }
    });

    _socket!.on('answer', (data) async {
      if (_peerConnection != null) {
        await _peerConnection!.setRemoteDescription(
          RTCSessionDescription(data['sdp'], data['type']),
        );
      }
    });

    _socket!.connect();
  }

  Future<MediaStream?> startLocalStream({bool video = true}) async {
    try {
      _localStream = await navigator.mediaDevices.getUserMedia({
        'audio': true,
        'video': video
            ? {
                'facingMode': 'user',
                'width': 1280,
                'height': 720,
              }
            : false,
      });

      return _localStream;
    } catch (e) {
      debugPrint('Error starting local stream: $e');
      return null;
    }
  }

  Future<String?> makeCall(String userId, CallType type,
      {String? callId}) async {
    try {
      final actualCallId =
          callId ?? DateTime.now().millisecondsSinceEpoch.toString();
      _currentCallId = actualCallId;

      // Create realtime translation session for this call and share its public id via signaling.
      String? realtimeSessionId;
      try {
        final settings = await ApiService().getMobileSettings();
        final sourceLang =
            (settings['default_send_language']?.toString().trim().isNotEmpty ??
                    false)
                ? settings['default_send_language'].toString()
                : 'auto';
        final targetLang = (settings['default_receive_language']
                    ?.toString()
                    .trim()
                    .isNotEmpty ??
                false)
            ? settings['default_receive_language'].toString()
            : 'en';

        realtimeSessionId = await ApiService().createRealtimeSession(
          sourceLanguage: sourceLang,
          targetLanguage: targetLang,
          type: 'call',
        );
        _currentRealtimeSessionId = realtimeSessionId;
      } catch (_) {
        // Realtime translation is best-effort; call should still proceed.
      }

      // Get local stream
      await startLocalStream(video: type == CallType.video);

      // Create peer connection
      _peerConnection = await createPeerConnection(
        AppConfig.iceServers,
        {},
      );

      // Add local stream
      _localStream?.getTracks().forEach((track) {
        _peerConnection!.addTrack(track, _localStream!);
      });

      // Handle ICE candidates
      _peerConnection!.onIceCandidate = (candidate) {
        _socket!.emit('ice-candidate', {
          'callId': actualCallId,
          'to': userId,
          'candidate': candidate.toMap(),
        });
      };

      // Handle remote stream
      _peerConnection!.onTrack = (event) {
        if (event.streams.isNotEmpty) {
          _remoteStream = event.streams[0];
          if (onRemoteStream != null) {
            onRemoteStream!(_remoteStream!);
          }
        }
      };

      // Create offer
      final offer = await _peerConnection!.createOffer();
      await _peerConnection!.setLocalDescription(offer);

      // Send offer via socket
      _socket!.emit('call', {
        'callId': actualCallId,
        if (realtimeSessionId != null) 'realtimeSessionId': realtimeSessionId,
        'to': userId,
        'type': type == CallType.video ? 'video' : 'audio',
        'offer': {
          'sdp': offer.sdp,
          'type': offer.type,
        },
      });

      return realtimeSessionId;
    } catch (e) {
      debugPrint('Error making call: $e');
      endCall();
      return null;
    }
  }

  Future<void> answerCall(String callId, CallType type) async {
    try {
      _currentCallId = callId;

      await startLocalStream(video: type == CallType.video);

      _peerConnection = await createPeerConnection(
        AppConfig.iceServers,
        {},
      );

      _localStream?.getTracks().forEach((track) {
        _peerConnection!.addTrack(track, _localStream!);
      });

      _peerConnection!.onIceCandidate = (candidate) {
        _socket!.emit('ice-candidate', {
          'callId': callId,
          'candidate': candidate.toMap(),
        });
      };

      _peerConnection!.onTrack = (event) {
        if (event.streams.isNotEmpty) {
          _remoteStream = event.streams[0];
          if (onRemoteStream != null) {
            onRemoteStream!(_remoteStream!);
          }
        }
      };

      // If we received an offer via signaling, answer it.
      final pendingOffer = _pendingOffers.remove(callId);
      if (pendingOffer != null) {
        await _peerConnection!.setRemoteDescription(pendingOffer);
        final answer = await _peerConnection!.createAnswer();
        await _peerConnection!.setLocalDescription(answer);

        // Send answer (server should forward to caller)
        _socket!.emit('answer', {
          'callId': callId,
          'sdp': answer.sdp,
          'type': answer.type,
        });
        _socket!.emit('answer-call', {
          'callId': callId,
          'sdp': answer.sdp,
          'type': answer.type,
        });
      } else {
        // Fallback: notify server we accepted; server may handle SDP exchange separately
        _socket!.emit('answer-call', {'callId': callId});
      }
    } catch (e) {
      debugPrint('Error answering call: $e');
      endCall();
    }
  }

  Future<void> endCall() async {
    _socket!.emit('end-call', {'callId': _currentCallId});

    await _localStream?.dispose();
    await _remoteStream?.dispose();
    await _peerConnection?.close();

    _localStream = null;
    _remoteStream = null;
    _peerConnection = null;
    _currentCallId = null;

    if (onCallEnded != null) {
      onCallEnded!();
    }
  }

  void toggleMicrophone() {
    _localStream?.getAudioTracks().forEach((track) {
      track.enabled = !track.enabled;
    });
  }

  void toggleCamera() {
    _localStream?.getVideoTracks().forEach((track) {
      track.enabled = !track.enabled;
    });
  }

  void switchCamera() {
    final tracks = _localStream?.getVideoTracks();
    if (tracks == null || tracks.isEmpty) return;
    final track = tracks[0];
    Helper.switchCamera(track);
  }

  void dispose() {
    endCall();
    _socket?.disconnect();
    _socket?.dispose();
  }

  MediaStream? get localStream => _localStream;
  MediaStream? get remoteStream => _remoteStream;
  String? get currentRealtimeSessionId => _currentRealtimeSessionId;
}
