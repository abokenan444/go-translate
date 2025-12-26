import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_webrtc/flutter_webrtc.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';
import '../providers/call_provider.dart';
import '../models/call_model.dart';
import '../services/api_service.dart';
import '../services/webrtc_service.dart';
import '../services/realtime_translation_service.dart';
import '../providers/auth_provider.dart';

class CallScreen extends StatefulWidget {
  const CallScreen({super.key});

  @override
  State<CallScreen> createState() => _CallScreenState();
}

class _CallScreenState extends State<CallScreen> with WidgetsBindingObserver {
  final RTCVideoRenderer _localRenderer = RTCVideoRenderer();
  final RTCVideoRenderer _remoteRenderer = RTCVideoRenderer();
  bool _isInitialized = false;
  bool _renderersReady = false;
  bool _startingLocalStream = false;
  bool _translationStarted = false;
  String? _callType;
  String? _contactName;
  DateTime? _callStartTime;

  String? _overrideSendLang;
  String? _overrideReceiveLang;
  bool _insufficientMinutes = false;
  int? _lowMinutesRemaining;
  int? _lowMinutesThreshold;

  int _effectiveLowThresholdMinutes() {
    final t = _lowMinutesThreshold;
    if (t != null && t > 0) return t;
    return 5;
  }

  String _lowBalanceBannerText(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final remaining = _lowMinutesRemaining;
    final threshold = _effectiveLowThresholdMinutes();
    if (remaining == null) return '';
    return '${loc.translate('low_balance')}: $remaining ${loc.translate('minute')} (${loc.translate('warning_threshold')} $threshold)';
  }

  String _translationLabel(CallModel? call, BuildContext context) {
    final loc = AppLocalizations.of(context);
    if (call?.realtimeSessionId == null) return '';

    if (_insufficientMinutes) {
      return '⚠️ ${loc.translate('translation_stopped')}';
    }

    if (!_translationStarted) {
      return '⏳ ${loc.translate('translation_starting')}';
    }

    final send = _overrideSendLang;
    final receive = _overrideReceiveLang;
    if (send == null && receive == null) {
      return '🎙️ ${loc.translate('translation_active_both')}';
    }
    final sendText = send == 'auto'
        ? loc.translate('auto_detect')
        : (send ?? loc.translate('auto_detect'));
    final receiveText = receive ?? 'en';
    return '🎙️ $sendText → $receiveText';
  }

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _initializeRenderers();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed) {
      _refreshWalletStatusAfterResume();
    }
  }

  Future<void> _refreshWalletStatusAfterResume() async {
    if (!mounted) return;
    if (!_insufficientMinutes && _lowMinutesRemaining == null) return;

    try {
      final balance = await ApiService().getWalletBalance();
      final minutes = (balance['balance_minutes'] is num)
          ? (balance['balance_minutes'] as num).toInt()
          : int.tryParse(balance['balance_minutes']?.toString() ?? '') ?? 0;

      if (!mounted) return;
      final threshold = _effectiveLowThresholdMinutes();

      setState(() {
        _insufficientMinutes = minutes <= 0;
        if (minutes <= 0) {
          _lowMinutesRemaining = null;
          return;
        }

        if (minutes <= threshold) {
          _lowMinutesRemaining = minutes;
          _lowMinutesThreshold = threshold;
        } else {
          _lowMinutesRemaining = null;
        }
      });

      // If minutes are replenished, restart translation automatically.
      if (minutes > 0) {
        final callProvider = Provider.of<CallProvider>(context, listen: false);
        final authProvider = Provider.of<AuthProvider>(context, listen: false);
        final call = callProvider.currentCall;
        final me = authProvider.currentUser;
        if (call != null && me != null && call.realtimeSessionId != null) {
          _translationStarted = false;
          await _maybeStartTranslation(callProvider, authProvider);
        }
      }
    } catch (_) {
      // Ignore refresh failures.
    }
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_isInitialized) {
      final args =
          ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
      _callType = args?['callType'] ?? 'video';
      _contactName = args?['contactName'] ?? 'Unknown';
      _callStartTime = DateTime.now();
      _isInitialized = true;
    }
  }

  Future<void> _initializeRenderers() async {
    await _localRenderer.initialize();
    await _remoteRenderer.initialize();
    if (mounted) {
      setState(() {
        _renderersReady = true;
      });
    }
  }

  Future<void> _ensureLocalStream(bool isVideoCall) async {
    if (_startingLocalStream) return;
    final callProvider = Provider.of<CallProvider>(context, listen: false);
    if (callProvider.localStream != null) return;

    _startingLocalStream = true;
    try {
      final stream = await WebRTCService().startLocalStream(video: isVideoCall);
      if (stream != null) {
        callProvider.setLocalStream(stream);
      }
    } finally {
      _startingLocalStream = false;
    }
  }

  @override
  void dispose() {
    RealtimeTranslationService().stop();
    WidgetsBinding.instance.removeObserver(this);
    _localRenderer.dispose();
    _remoteRenderer.dispose();
    super.dispose();
  }

  Future<void> _openTranslationSettings(
      CallModel call, int myUserId, String myName) async {
    if (call.realtimeSessionId == null) {
      return;
    }

    final langs = await ApiService().getMobileLanguages();
    final settings = await ApiService().getMobileSettings();

    String sendLang = _overrideSendLang ??
        settings['default_send_language']?.toString() ??
        'auto';
    String receiveLang = _overrideReceiveLang ??
        settings['default_receive_language']?.toString() ??
        'en';

    if (!mounted) return;
    await showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (ctx) {
        return Padding(
          padding: EdgeInsets.only(
            left: 16,
            right: 16,
            top: 16,
            bottom: 16 + MediaQuery.of(ctx).viewInsets.bottom,
          ),
          child: StatefulBuilder(
            builder: (ctx, setSheetState) {
              List<DropdownMenuItem<String>> items({bool includeAuto = false}) {
                final out = <DropdownMenuItem<String>>[];
                if (includeAuto) {
                  out.add(DropdownMenuItem(
                      value: 'auto',
                      child: Text(
                          AppLocalizations.of(ctx).translate('auto_detect'))));
                }
                for (final l in langs) {
                  final code = l['code']?.toString();
                  if (code == null || code.isEmpty) continue;
                  final name = l['name']?.toString() ?? code;
                  out.add(DropdownMenuItem(value: code, child: Text(name)));
                }
                return out;
              }

              final loc = AppLocalizations.of(ctx);
              return Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Text(
                    loc.translate('translation_settings_for_call'),
                    style: const TextStyle(
                        fontSize: 16, fontWeight: FontWeight.w600),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 12),
                  DropdownButtonFormField<String>(
                    value: sendLang,
                    decoration: InputDecoration(
                      labelText: loc.translate('send_language'),
                      prefixIcon: const Icon(Icons.mic),
                    ),
                    items: items(includeAuto: true),
                    onChanged: (v) {
                      if (v == null) return;
                      setSheetState(() => sendLang = v);
                    },
                  ),
                  const SizedBox(height: 12),
                  DropdownButtonFormField<String>(
                    value: receiveLang,
                    decoration: InputDecoration(
                      labelText: loc.translate('receive_language'),
                      prefixIcon: const Icon(Icons.hearing),
                    ),
                    items: items(),
                    onChanged: (v) {
                      if (v == null) return;
                      setSheetState(() => receiveLang = v);
                    },
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    height: 44,
                    child: ElevatedButton.icon(
                      onPressed: () async {
                        Navigator.pop(ctx);
                        if (!mounted) return;
                        setState(() {
                          _overrideSendLang = sendLang;
                          _overrideReceiveLang = receiveLang;
                          _translationStarted = false;
                          _insufficientMinutes = false;
                          _lowMinutesRemaining = null;
                          _lowMinutesThreshold = null;
                        });
                        await RealtimeTranslationService().stop();
                        if (!mounted) return;
                        await RealtimeTranslationService().startForCall(
                          call: call,
                          myUserId: myUserId,
                          displayName: myName,
                          sendLanguageOverride: _overrideSendLang,
                          receiveLanguageOverride: _overrideReceiveLang,
                          onInsufficientMinutes: () {
                            if (!mounted) return;
                            setState(() {
                              _insufficientMinutes = true;
                              _translationStarted = false;
                            });
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(
                                content: Text(AppLocalizations.of(context)
                                    .translate(
                                        'minutes_ended_translation_stopped')),
                                backgroundColor: AppTheme.errorColor,
                              ),
                            );
                          },
                          onLowMinutes: (remaining, threshold) {
                            if (!mounted) return;
                            if (_insufficientMinutes) return;
                            setState(() {
                              _lowMinutesRemaining = remaining;
                              _lowMinutesThreshold = threshold;
                            });
                          },
                        );
                        if (!mounted) return;
                        setState(() {
                          _translationStarted = true;
                        });
                      },
                      icon: const Icon(Icons.save),
                      label: Text(loc.translate('apply')),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryColor,
                        foregroundColor: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(height: 8),
                ],
              );
            },
          ),
        );
      },
    );
  }

  Future<void> _maybeStartTranslation(
      CallProvider callProvider, AuthProvider authProvider) async {
    if (_translationStarted) return;
    final me = authProvider.currentUser;
    final call = callProvider.currentCall;
    if (me == null || call == null || call.realtimeSessionId == null) return;

    _translationStarted = true;
    await RealtimeTranslationService().startForCall(
      call: call,
      myUserId: me.id,
      displayName: me.name,
      sendLanguageOverride: _overrideSendLang,
      receiveLanguageOverride: _overrideReceiveLang,
      onInsufficientMinutes: () {
        if (!mounted) return;
        setState(() {
          _insufficientMinutes = true;
          _translationStarted = false;
        });
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('انتهت الدقائق المتاحة. تم إيقاف الترجمة الفورية.'),
            backgroundColor: AppTheme.errorColor,
          ),
        );
      },
      onLowMinutes: (remaining, threshold) {
        if (!mounted) return;
        if (_insufficientMinutes) return;
        setState(() {
          _lowMinutesRemaining = remaining;
          _lowMinutesThreshold = threshold;
        });
      },
    );
  }

  Future<void> _openBuyMinutesSheet() async {
    List<Map<String, dynamic>> packages = const [];
    String? err;
    try {
      packages = await ApiService().getMinutePackages();
    } catch (e) {
      err = e.toString();
    }

    if (!mounted) return;
    await showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (ctx) {
        return Padding(
          padding: EdgeInsets.only(
            left: 16,
            right: 16,
            top: 16,
            bottom: 16 + MediaQuery.of(ctx).viewInsets.bottom,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const Text(
                'شراء دقائق',
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 12),
              if (err != null)
                Padding(
                  padding: const EdgeInsets.only(bottom: 8),
                  child: Text(err,
                      style: const TextStyle(color: AppTheme.errorColor)),
                ),
              if (packages.isEmpty && err == null)
                const Text('لا توجد باقات متاحة حالياً.'),
              for (final pkg in packages)
                Padding(
                  padding: const EdgeInsets.only(bottom: 8),
                  child: SizedBox(
                    height: 44,
                    child: ElevatedButton(
                      onPressed: () async {
                        final id = pkg['id']?.toString();
                        if (id == null || id.isEmpty) return;
                        try {
                          final url = await ApiService()
                              .createMinutesCheckout(packageId: id);
                          final uri = Uri.tryParse(url);
                          if (uri == null)
                            throw Exception('Invalid checkout URL');
                          final ok = await launchUrl(uri,
                              mode: LaunchMode.externalApplication);
                          if (!ok) throw Exception('Could not open checkout');
                          if (!ctx.mounted) return;
                          Navigator.pop(ctx);
                          if (!mounted) return;
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text(
                                  'تم فتح صفحة الدفع. بعد الإكمال ارجع للتطبيق وسيتم تحديث الرصيد تلقائياً.'),
                              backgroundColor: AppTheme.primaryColor,
                            ),
                          );
                        } catch (e) {
                          if (!mounted) return;
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text('فشل فتح الدفع: $e'),
                              backgroundColor: AppTheme.errorColor,
                            ),
                          );
                        }
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryColor,
                        foregroundColor: Colors.white,
                      ),
                      child: Text(
                          'شراء ${pkg['minutes']} دقيقة - €${pkg['price_eur']}'),
                    ),
                  ),
                ),
              const SizedBox(height: 8),
            ],
          ),
        );
      },
    );
  }

  String _formatDuration() {
    if (_callStartTime == null) return '00:00';
    final duration = DateTime.now().difference(_callStartTime!);
    final minutes = duration.inMinutes.toString().padLeft(2, '0');
    final seconds = (duration.inSeconds % 60).toString().padLeft(2, '0');
    return '$minutes:$seconds';
  }

  @override
  Widget build(BuildContext context) {
    final callProvider = Provider.of<CallProvider>(context);
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final isVideoCall = _callType == 'video';

    // Keep renderers in sync with current streams
    if (_renderersReady) {
      _localRenderer.srcObject = callProvider.localStream;
      _remoteRenderer.srcObject = callProvider.remoteStream;
    }

    // Start local stream for this call type
    if (_isInitialized) {
      _ensureLocalStream(isVideoCall);
      // Wire remote stream callback once per screen instance
      WebRTCService().onRemoteStream ??= (stream) {
        if (!mounted) return;
        Provider.of<CallProvider>(context, listen: false)
            .setRemoteStream(stream);
      };

      // Start realtime voice translation (best-effort) once per call.
      _maybeStartTranslation(callProvider, authProvider);
    }

    return Scaffold(
      backgroundColor: Colors.black,
      body: SafeArea(
        child: Stack(
          children: [
            // Remote Video (Full Screen)
            if (isVideoCall && callProvider.remoteStream != null)
              Positioned.fill(
                child: RTCVideoView(
                  _remoteRenderer,
                  objectFit: RTCVideoViewObjectFit.RTCVideoViewObjectFitCover,
                ),
              )
            else
              _buildAudioCallUI(),

            // Local Video (Picture in Picture)
            if (isVideoCall && callProvider.localStream != null)
              Positioned(
                top: 50,
                right: 20,
                child: Container(
                  width: 120,
                  height: 160,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: Colors.white, width: 2),
                  ),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(10),
                    child: RTCVideoView(
                      _localRenderer,
                      mirror: true,
                      objectFit:
                          RTCVideoViewObjectFit.RTCVideoViewObjectFitCover,
                    ),
                  ),
                ),
              ),

            // Top Bar
            Positioned(
              top: 0,
              left: 0,
              right: 0,
              child: Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      Colors.black.withAlpha(179),
                      Colors.transparent,
                    ],
                  ),
                ),
                child: Stack(
                  children: [
                    Column(
                      children: [
                        Text(
                          _contactName ?? 'Unknown',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          callProvider.currentCall?.status ==
                                  CallStatus.connected
                              ? _formatDuration()
                              : _getStatusText(
                                  callProvider.currentCall?.status),
                          style: const TextStyle(
                            color: Colors.white70,
                            fontSize: 16,
                          ),
                        ),
                        Builder(
                          builder: (context) {
                            final label = _translationLabel(
                                callProvider.currentCall, context);
                            if (label.isEmpty) return const SizedBox.shrink();
                            return Padding(
                              padding: const EdgeInsets.only(top: 6),
                              child: Text(
                                label,
                                textAlign: TextAlign.center,
                                style: const TextStyle(
                                  color: Colors.white70,
                                  fontSize: 13,
                                ),
                              ),
                            );
                          },
                        ),
                      ],
                    ),
                    Align(
                      alignment: Alignment.centerRight,
                      child: IconButton(
                        tooltip: AppLocalizations.of(context)
                            .translate('translation_settings'),
                        onPressed: () {
                          final me = authProvider.currentUser;
                          final call = callProvider.currentCall;
                          if (me == null || call == null) return;
                          _openTranslationSettings(call, me.id, me.name);
                        },
                        icon: const Icon(Icons.translate, color: Colors.white),
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Bottom Controls
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.bottomCenter,
                    end: Alignment.topCenter,
                    colors: [
                      Colors.black.withAlpha(204),
                      Colors.transparent,
                    ],
                  ),
                ),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    if (!_insufficientMinutes && _lowMinutesRemaining != null)
                      Card(
                        margin: const EdgeInsets.only(bottom: 12),
                        child: Padding(
                          padding: const EdgeInsets.all(12),
                          child: Row(
                            children: [
                              const Icon(Icons.warning_amber,
                                  color: AppTheme.primaryColor),
                              const SizedBox(width: 10),
                              Expanded(
                                child: Text(
                                  _lowBalanceBannerText(context),
                                ),
                              ),
                              TextButton(
                                onPressed: _refreshWalletStatusAfterResume,
                                child: Text(AppLocalizations.of(context)
                                    .translate('check_now')),
                              ),
                              TextButton(
                                onPressed: _openBuyMinutesSheet,
                                child: Text(AppLocalizations.of(context)
                                    .translate('buy_minutes')),
                              ),
                            ],
                          ),
                        ),
                      ),
                    if (_insufficientMinutes)
                      Card(
                        margin: const EdgeInsets.only(bottom: 12),
                        child: Padding(
                          padding: const EdgeInsets.all(12),
                          child: Row(
                            children: [
                              const Icon(Icons.timer_off,
                                  color: AppTheme.errorColor),
                              const SizedBox(width: 10),
                              Expanded(
                                child: Text(AppLocalizations.of(context)
                                    .translate(
                                        'minutes_ended_translation_stopped')),
                              ),
                              TextButton(
                                onPressed: _refreshWalletStatusAfterResume,
                                child: Text(AppLocalizations.of(context)
                                    .translate('check_now')),
                              ),
                              TextButton(
                                onPressed: _openBuyMinutesSheet,
                                child: Text(AppLocalizations.of(context)
                                    .translate('buy_minutes')),
                              ),
                            ],
                          ),
                        ),
                      ),
                    _buildControlButtons(callProvider, isVideoCall),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAudioCallUI() {
    return Container(
      decoration: const BoxDecoration(
        gradient: AppTheme.primaryGradient,
      ),
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 150,
              height: 150,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: Colors.white.withAlpha(51),
              ),
              child: const Icon(
                Icons.person,
                size: 80,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 32),
            Text(
              _contactName ?? 'Unknown',
              style: const TextStyle(
                color: Colors.white,
                fontSize: 28,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            const Text(
              'مكالمة صوتية',
              style: TextStyle(
                color: Colors.white70,
                fontSize: 18,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildControlButtons(CallProvider callProvider, bool isVideoCall) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
      children: [
        // Microphone Toggle
        _buildControlButton(
          icon: callProvider.isMicMuted
              ? FontAwesomeIcons.microphoneSlash
              : FontAwesomeIcons.microphone,
          color: callProvider.isMicMuted ? AppTheme.errorColor : Colors.white,
          onPressed: () => callProvider.toggleMicrophone(),
        ),

        // Camera Toggle (Video Calls Only)
        if (isVideoCall)
          _buildControlButton(
            icon: callProvider.isCameraOff
                ? FontAwesomeIcons.videoSlash
                : FontAwesomeIcons.video,
            color:
                callProvider.isCameraOff ? AppTheme.errorColor : Colors.white,
            onPressed: () => callProvider.toggleCamera(),
          ),

        // Speaker Toggle
        _buildControlButton(
          icon: callProvider.isSpeakerOn
              ? FontAwesomeIcons.volumeHigh
              : FontAwesomeIcons.volumeLow,
          color: Colors.white,
          onPressed: () => callProvider.toggleSpeaker(),
        ),

        // Switch Camera (Video Calls Only)
        if (isVideoCall)
          _buildControlButton(
            icon: FontAwesomeIcons.cameraRotate,
            color: Colors.white,
            onPressed: () => callProvider.switchCamera(),
          ),

        // End Call
        _buildControlButton(
          icon: FontAwesomeIcons.phone,
          color: AppTheme.errorColor,
          size: 72,
          iconSize: 32,
          onPressed: () async {
            await callProvider.endCall();
            if (!mounted) return;
            Navigator.pop(context);
          },
        ),
      ],
    );
  }

  Widget _buildControlButton({
    required IconData icon,
    required Color color,
    required VoidCallback onPressed,
    double size = 60,
    double iconSize = 24,
  }) {
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        color: color.withAlpha(77),
        border: Border.all(color: color, width: 2),
      ),
      child: IconButton(
        icon: FaIcon(icon, size: iconSize),
        color: color,
        onPressed: onPressed,
      ),
    );
  }

  String _getStatusText(CallStatus? status) {
    switch (status) {
      case CallStatus.ringing:
        return 'جاري الاتصال...';
      case CallStatus.connecting:
        return 'جاري التوصيل...';
      case CallStatus.connected:
        return 'متصل';
      case CallStatus.disconnected:
        return 'انتهت المكالمة';
      case CallStatus.failed:
        return 'فشل الاتصال';
      default:
        return 'جاري الاتصال...';
    }
  }
}
