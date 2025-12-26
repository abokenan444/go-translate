import 'package:flutter/foundation.dart';
import 'package:flutter_webrtc/flutter_webrtc.dart';
import '../models/call_model.dart';
import '../services/webrtc_service.dart';
import '../services/realtime_translation_service.dart';

class CallProvider with ChangeNotifier {
  CallModel? _currentCall;
  MediaStream? _localStream;
  MediaStream? _remoteStream;
  bool _isMicMuted = false;
  bool _isCameraOff = false;
  bool _isSpeakerOn = true;

  CallModel? get currentCall => _currentCall;
  MediaStream? get localStream => _localStream;
  MediaStream? get remoteStream => _remoteStream;
  bool get isMicMuted => _isMicMuted;
  bool get isCameraOff => _isCameraOff;
  bool get isSpeakerOn => _isSpeakerOn;
  bool get isInCall => _currentCall != null;

  void setCurrentCall(CallModel call) {
    _currentCall = call;
    notifyListeners();
  }

  void setLocalStream(MediaStream stream) {
    _localStream = stream;
    notifyListeners();
  }

  void setRemoteStream(MediaStream stream) {
    _remoteStream = stream;
    notifyListeners();
  }

  void toggleMicrophone() {
    _isMicMuted = !_isMicMuted;
    WebRTCService().toggleMicrophone();
    notifyListeners();
  }

  void toggleCamera() {
    _isCameraOff = !_isCameraOff;
    WebRTCService().toggleCamera();
    notifyListeners();
  }

  void toggleSpeaker() {
    _isSpeakerOn = !_isSpeakerOn;
    // Implement speaker toggle
    notifyListeners();
  }

  void switchCamera() {
    WebRTCService().switchCamera();
    notifyListeners();
  }

  Future<void> endCall() async {
    await RealtimeTranslationService().stop();
    await WebRTCService().endCall();
    _currentCall = null;
    _localStream = null;
    _remoteStream = null;
    _isMicMuted = false;
    _isCameraOff = false;
    notifyListeners();
  }
}
