import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';
import '../providers/call_provider.dart';
import '../models/call_model.dart';
import '../services/webrtc_service.dart';

class IncomingCallScreen extends StatefulWidget {
  const IncomingCallScreen({super.key});

  @override
  State<IncomingCallScreen> createState() => _IncomingCallScreenState();
}

class _IncomingCallScreenState extends State<IncomingCallScreen>
    with SingleTickerProviderStateMixin {
  late AnimationController _animationController;
  late Animation<double> _scaleAnimation;
  CallModel? _incomingCall;

  @override
  void initState() {
    super.initState();
    _animationController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1000),
    )..repeat(reverse: true);

    _scaleAnimation = Tween<double>(begin: 0.9, end: 1.1).animate(
      CurvedAnimation(
        parent: _animationController,
        curve: Curves.easeInOut,
      ),
    );
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    final args =
        ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
    if (args != null && args['call'] != null) {
      _incomingCall = args['call'] as CallModel;
    }
  }

  @override
  void dispose() {
    _animationController.dispose();
    super.dispose();
  }

  Future<void> _acceptCall() async {
    if (_incomingCall == null) return;

    final callProvider = Provider.of<CallProvider>(context, listen: false);
    callProvider.setCurrentCall(_incomingCall!);

    // Answer the call (uses stored offer if provided by signaling)
    await WebRTCService()
        .answerCall(_incomingCall!.callId, _incomingCall!.type);

    final local = WebRTCService().localStream;
    if (local != null) {
      callProvider.setLocalStream(local);
    }

    if (!mounted) return;
    Navigator.pushReplacementNamed(
      context,
      '/call',
      arguments: {
        'callType': _incomingCall?.type == CallType.video ? 'video' : 'audio',
        'contactName': _incomingCall?.fromUserName ?? 'Unknown',
      },
    );
  }

  Future<void> _rejectCall() async {
    final callProvider = Provider.of<CallProvider>(context, listen: false);

    // TODO: Implement actual call rejection logic
    await callProvider.endCall();

    if (!mounted) return;
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    final callerName = _incomingCall?.fromUserName ?? 'Unknown Caller';
    final callType = _incomingCall?.type ?? CallType.audio;
    final isVideoCall = callType == CallType.video;

    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: AppTheme.primaryGradient,
        ),
        child: SafeArea(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              // Top Section
              Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  children: [
                    const SizedBox(height: 40),
                    Text(
                      isVideoCall
                          ? AppLocalizations.of(context)
                              .translate('incoming_video_call')
                          : AppLocalizations.of(context)
                              .translate('incoming_audio_call'),
                      style: const TextStyle(
                        color: Colors.white70,
                        fontSize: 18,
                      ),
                    ),
                    const SizedBox(height: 20),
                    Text(
                      callerName,
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 32,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),

              // Avatar Section
              AnimatedBuilder(
                animation: _scaleAnimation,
                builder: (context, child) {
                  return Transform.scale(
                    scale: _scaleAnimation.value,
                    child: Container(
                      width: 180,
                      height: 180,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: Colors.white.withAlpha(51),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.white.withAlpha(77),
                            blurRadius: 40,
                            spreadRadius: 10,
                          ),
                        ],
                      ),
                      child: Container(
                        margin: const EdgeInsets.all(20),
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: Colors.white.withAlpha(77),
                        ),
                        child: const Icon(
                          Icons.person,
                          size: 80,
                          color: Colors.white,
                        ),
                      ),
                    ),
                  );
                },
              ),

              // Call Info
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 40),
                child: Column(
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          isVideoCall ? Icons.videocam : Icons.phone,
                          color: Colors.white70,
                          size: 20,
                        ),
                        const SizedBox(width: 8),
                        Text(
                          isVideoCall
                              ? AppLocalizations.of(context)
                                  .translate('video_call')
                              : AppLocalizations.of(context)
                                  .translate('audio_call'),
                          style: const TextStyle(
                            color: Colors.white70,
                            fontSize: 16,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    const Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.access_time,
                          color: Colors.white70,
                          size: 20,
                        ),
                        SizedBox(width: 8),
                        Text(
                          'جاري الاتصال...',
                          style: TextStyle(
                            color: Colors.white70,
                            fontSize: 16,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              // Action Buttons
              Padding(
                padding: const EdgeInsets.all(40.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: [
                    // Reject Button
                    Column(
                      children: [
                        GestureDetector(
                          onTap: _rejectCall,
                          child: Container(
                            width: 70,
                            height: 70,
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              color: AppTheme.errorColor,
                              boxShadow: [
                                BoxShadow(
                                  color: AppTheme.errorColor.withAlpha(128),
                                  blurRadius: 20,
                                  spreadRadius: 5,
                                ),
                              ],
                            ),
                            child: const Icon(
                              Icons.call_end,
                              color: Colors.white,
                              size: 36,
                            ),
                          ),
                        ),
                        const SizedBox(height: 12),
                        const Text(
                          'رفض',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                          ),
                        ),
                      ],
                    ),

                    // Accept Button
                    Column(
                      children: [
                        GestureDetector(
                          onTap: _acceptCall,
                          child: Container(
                            width: 70,
                            height: 70,
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              color: AppTheme.successColor,
                              boxShadow: [
                                BoxShadow(
                                  color: AppTheme.successColor.withAlpha(128),
                                  blurRadius: 20,
                                  spreadRadius: 5,
                                ),
                              ],
                            ),
                            child: const Icon(
                              Icons.call,
                              color: Colors.white,
                              size: 36,
                            ),
                          ),
                        ),
                        const SizedBox(height: 12),
                        const Text(
                          'قبول',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              // Quick Actions (Optional)
              Padding(
                padding: const EdgeInsets.only(bottom: 20),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    _buildQuickAction(
                      icon: Icons.message,
                      label: 'رسالة',
                      onTap: () {
                        // TODO: Send quick message
                      },
                    ),
                    const SizedBox(width: 40),
                    _buildQuickAction(
                      icon: Icons.alarm,
                      label: 'تذكير',
                      onTap: () {
                        // TODO: Set reminder
                      },
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildQuickAction({
    required IconData icon,
    required String label,
    required VoidCallback onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        children: [
          Container(
            width: 50,
            height: 50,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: Colors.white.withAlpha(51),
            ),
            child: Icon(
              icon,
              color: Colors.white,
              size: 24,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            label,
            style: const TextStyle(
              color: Colors.white70,
              fontSize: 12,
            ),
          ),
        ],
      ),
    );
  }
}
