import 'dart:async';
import 'package:flutter/material.dart';
import 'api_service.dart';

/// Service to manage live support chat
class SupportChatService {
  static final SupportChatService _instance = SupportChatService._internal();
  factory SupportChatService() => _instance;
  SupportChatService._internal();

  String? _currentSessionId;
  String? _sessionStatus;
  Timer? _pollTimer;
  int _lastMessageId = 0;

  // Callbacks
  Function(Map<String, dynamic>)? onNewMessage;
  Function(String)? onStatusChange;
  Function(Map<String, dynamic>)? onAgentJoined;
  Function()? onSessionEnded;

  String? get currentSessionId => _currentSessionId;
  String? get sessionStatus => _sessionStatus;
  bool get hasActiveSession =>
      _currentSessionId != null &&
      (_sessionStatus == 'waiting' || _sessionStatus == 'active');

  /// Check if support agents are available
  Future<Map<String, dynamic>> checkAvailability() async {
    return await ApiService().getSupportAvailability();
  }

  /// Start a new chat session
  Future<Map<String, dynamic>> startChat({
    required String message,
    String department = 'general',
  }) async {
    final result = await ApiService().startSupportChat(
      message: message,
      department: department,
    );

    if (result['success'] == true && result['data'] != null) {
      final data = result['data'];
      _currentSessionId = data['session']?['session_id'];
      _sessionStatus = data['session']?['status'] ?? 'waiting';
      _lastMessageId = 0;

      // Start polling for new messages
      _startPolling();
    }

    return result;
  }

  /// Send a message in the current session
  Future<Map<String, dynamic>> sendMessage(String message) async {
    if (_currentSessionId == null) {
      return {'success': false, 'message': 'No active session'};
    }

    return await ApiService().sendSupportMessage(
      sessionId: _currentSessionId!,
      message: message,
    );
  }

  /// Get messages for current session
  Future<List<Map<String, dynamic>>> getMessages() async {
    if (_currentSessionId == null) return [];

    final result = await ApiService().getSupportMessages(
      sessionId: _currentSessionId!,
      afterId: _lastMessageId,
    );

    if (result['success'] == true && result['data'] != null) {
      final messages = result['data']['messages'] as List? ?? [];
      final newStatus = result['data']['session_status'];

      if (newStatus != null && newStatus != _sessionStatus) {
        _sessionStatus = newStatus;
        onStatusChange?.call(newStatus);

        if (newStatus == 'closed') {
          _stopPolling();
          onSessionEnded?.call();
        }
      }

      // Update last message ID
      if (messages.isNotEmpty) {
        final lastMsg = messages.last;
        _lastMessageId = lastMsg['id'] ?? _lastMessageId;

        // Notify about new messages
        for (final msg in messages) {
          onNewMessage?.call(Map<String, dynamic>.from(msg));
        }
      }

      return messages.map((m) => Map<String, dynamic>.from(m)).toList();
    }

    return [];
  }

  /// End the current chat session
  Future<bool> endChat() async {
    if (_currentSessionId == null) return false;

    final result =
        await ApiService().endSupportChat(sessionId: _currentSessionId!);

    if (result['success'] == true) {
      _stopPolling();
      _currentSessionId = null;
      _sessionStatus = null;
      _lastMessageId = 0;
      onSessionEnded?.call();
      return true;
    }

    return false;
  }

  /// Rate the chat session
  Future<bool> rateChat(int rating, {String? feedback}) async {
    if (_currentSessionId == null) return false;

    final result = await ApiService().rateSupportChat(
      sessionId: _currentSessionId!,
      rating: rating,
      feedback: feedback,
    );

    return result['success'] == true;
  }

  /// Get chat history
  Future<List<Map<String, dynamic>>> getChatHistory() async {
    final result = await ApiService().getSupportChatHistory();

    if (result['success'] == true && result['data'] != null) {
      final sessions = result['data']['sessions'] as List? ?? [];
      return sessions.map((s) => Map<String, dynamic>.from(s)).toList();
    }

    return [];
  }

  void _startPolling() {
    _stopPolling();
    // Poll every 3 seconds for new messages
    _pollTimer = Timer.periodic(const Duration(seconds: 3), (_) {
      getMessages();
    });
  }

  void _stopPolling() {
    _pollTimer?.cancel();
    _pollTimer = null;
  }

  void dispose() {
    _stopPolling();
    onNewMessage = null;
    onStatusChange = null;
    onAgentJoined = null;
    onSessionEnded = null;
  }
}
