import 'package:flutter/material.dart';
import '../config/app_theme.dart';
import '../services/support_chat_service.dart';
import '../l10n/app_localizations.dart';

class LiveChatScreen extends StatefulWidget {
  const LiveChatScreen({super.key});

  @override
  State<LiveChatScreen> createState() => _LiveChatScreenState();
}

class _LiveChatScreenState extends State<LiveChatScreen> {
  final TextEditingController _messageController = TextEditingController();
  final ScrollController _scrollController = ScrollController();
  final SupportChatService _chatService = SupportChatService();

  final List<Map<String, dynamic>> _messages = [];
  bool _isLoading = true;
  bool _isSending = false;
  bool _isAgentAvailable = false;
  int _queuePosition = 0;
  String? _agentName;
  bool _showRating = false;

  @override
  void initState() {
    super.initState();
    _setupChatService();
    _checkAvailability();
  }

  void _setupChatService() {
    _chatService.onNewMessage = (message) {
      if (mounted) {
        setState(() {
          // Check if message already exists
          final exists = _messages.any((m) => m['id'] == message['id']);
          if (!exists) {
            _messages.add(message);
          }
        });
        _scrollToBottom();
      }
    };

    _chatService.onStatusChange = (status) {
      if (mounted) {
        if (status == 'active') {
          setState(() {
            _queuePosition = 0;
          });
        } else if (status == 'closed') {
          setState(() {
            _showRating = true;
          });
        }
      }
    };

    _chatService.onSessionEnded = () {
      if (mounted) {
        setState(() {
          _showRating = true;
        });
      }
    };
  }

  Future<void> _checkAvailability() async {
    final result = await _chatService.checkAvailability();
    if (mounted) {
      setState(() {
        _isLoading = false;
        _isAgentAvailable = result['data']?['agents_available'] ?? false;
      });
    }
  }

  Future<void> _startChat(String message) async {
    setState(() {
      _isLoading = true;
    });

    final result = await _chatService.startChat(message: message);

    if (mounted) {
      setState(() {
        _isLoading = false;
        if (result['success'] == true) {
          _queuePosition = result['data']?['queue_position'] ?? 0;
          // Add initial message to list
          _messages.add({
            'id': 0,
            'message': message,
            'sender_type': 'user',
            'created_at': DateTime.now().toIso8601String(),
          });
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(result['message'] ?? 'فشل بدء المحادثة'),
              backgroundColor: AppTheme.errorColor,
            ),
          );
        }
      });
    }
  }

  Future<void> _sendMessage() async {
    final message = _messageController.text.trim();
    if (message.isEmpty || _isSending) return;

    // If no active session, start one
    if (!_chatService.hasActiveSession) {
      await _startChat(message);
      _messageController.clear();
      return;
    }

    setState(() {
      _isSending = true;
    });

    // Optimistically add message
    final tempMessage = {
      'id': DateTime.now().millisecondsSinceEpoch,
      'message': message,
      'sender_type': 'user',
      'created_at': DateTime.now().toIso8601String(),
      'pending': true,
    };

    setState(() {
      _messages.add(tempMessage);
    });
    _messageController.clear();
    _scrollToBottom();

    final result = await _chatService.sendMessage(message);

    if (mounted) {
      setState(() {
        _isSending = false;
        // Update the temp message with real data or mark as failed
        final index = _messages.indexWhere((m) => m['id'] == tempMessage['id']);
        if (index != -1) {
          if (result['success'] == true && result['data'] != null) {
            _messages[index] = result['data']['message'];
          } else {
            _messages[index]['failed'] = true;
            _messages[index]['pending'] = false;
          }
        }
      });
    }
  }

  Future<void> _endChat() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(AppLocalizations.of(context).translate('end_chat')),
        content:
            Text(AppLocalizations.of(context).translate('end_chat_confirm')),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text(AppLocalizations.of(context).translate('cancel')),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style:
                ElevatedButton.styleFrom(backgroundColor: AppTheme.errorColor),
            child: Text(AppLocalizations.of(context).translate('end')),
          ),
        ],
      ),
    );

    if (confirm == true) {
      await _chatService.endChat();
    }
  }

  Future<void> _submitRating(int rating, String? feedback) async {
    await _chatService.rateChat(rating, feedback: feedback);
    if (mounted) {
      Navigator.pop(context);
    }
  }

  void _scrollToBottom() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (_scrollController.hasClients) {
        _scrollController.animateTo(
          _scrollController.position.maxScrollExtent,
          duration: const Duration(milliseconds: 300),
          curve: Curves.easeOut,
        );
      }
    });
  }

  @override
  void dispose() {
    _messageController.dispose();
    _scrollController.dispose();
    _chatService.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);

    return Scaffold(
      appBar: AppBar(
        title: Text(loc.translate('live_chat')),
        actions: [
          if (_chatService.hasActiveSession)
            IconButton(
              icon: const Icon(Icons.close),
              onPressed: _endChat,
              tooltip: loc.translate('end_chat'),
            ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _showRating
              ? _buildRatingView()
              : Column(
                  children: [
                    // Queue status banner
                    if (_queuePosition > 0)
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(12),
                        color: Colors.orange.shade100,
                        child: Row(
                          children: [
                            const Icon(Icons.access_time, color: Colors.orange),
                            const SizedBox(width: 8),
                            Expanded(
                              child: Text(
                                '${loc.translate('queue_position')}: $_queuePosition',
                                style: const TextStyle(color: Colors.orange),
                              ),
                            ),
                          ],
                        ),
                      ),

                    // Agent info
                    if (_agentName != null)
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(12),
                        color: Colors.green.shade100,
                        child: Row(
                          children: [
                            const CircleAvatar(
                              radius: 16,
                              child: Icon(Icons.support_agent, size: 20),
                            ),
                            const SizedBox(width: 8),
                            Text(
                              '${loc.translate('chatting_with')}: $_agentName',
                              style: const TextStyle(color: Colors.green),
                            ),
                          ],
                        ),
                      ),

                    // Messages list
                    Expanded(
                      child: _messages.isEmpty && !_chatService.hasActiveSession
                          ? _buildWelcomeView()
                          : ListView.builder(
                              controller: _scrollController,
                              padding: const EdgeInsets.all(16),
                              itemCount: _messages.length,
                              itemBuilder: (context, index) {
                                final message = _messages[index];
                                return _buildMessageBubble(message);
                              },
                            ),
                    ),

                    // Input area
                    _buildInputArea(),
                  ],
                ),
    );
  }

  Widget _buildWelcomeView() {
    final loc = AppLocalizations.of(context);

    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.support_agent,
            size: 80,
            color: AppTheme.primaryColor.withOpacity(0.5),
          ),
          const SizedBox(height: 24),
          Text(
            loc.translate('welcome_to_support'),
            style: Theme.of(context).textTheme.headlineSmall,
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 12),
          Text(
            _isAgentAvailable
                ? loc.translate('agent_available_message')
                : loc.translate('agent_unavailable_message'),
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: Colors.grey,
                ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 24),

          // Quick actions
          Wrap(
            spacing: 8,
            runSpacing: 8,
            alignment: WrapAlignment.center,
            children: [
              _buildQuickAction(loc.translate('billing_issue'), Icons.payment),
              _buildQuickAction(loc.translate('technical_help'), Icons.build),
              _buildQuickAction(loc.translate('general_question'), Icons.help),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildQuickAction(String label, IconData icon) {
    return ActionChip(
      avatar: Icon(icon, size: 18),
      label: Text(label),
      onPressed: () {
        _messageController.text = label;
        _sendMessage();
      },
    );
  }

  Widget _buildMessageBubble(Map<String, dynamic> message) {
    final isUser = message['sender_type'] == 'user';
    final isSystem = message['sender_type'] == 'system';
    final isPending = message['pending'] == true;
    final isFailed = message['failed'] == true;

    if (isSystem) {
      return Padding(
        padding: const EdgeInsets.symmetric(vertical: 8),
        child: Center(
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            decoration: BoxDecoration(
              color: Colors.grey.shade200,
              borderRadius: BorderRadius.circular(16),
            ),
            child: Text(
              message['message'] ?? '',
              style: TextStyle(
                color: Colors.grey.shade600,
                fontSize: 12,
              ),
            ),
          ),
        ),
      );
    }

    return Align(
      alignment: isUser ? Alignment.centerRight : Alignment.centerLeft,
      child: Container(
        margin: const EdgeInsets.symmetric(vertical: 4),
        constraints: BoxConstraints(
          maxWidth: MediaQuery.of(context).size.width * 0.75,
        ),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
        decoration: BoxDecoration(
          color: isUser
              ? (isFailed ? Colors.red.shade100 : AppTheme.primaryColor)
              : Colors.grey.shade200,
          borderRadius: BorderRadius.only(
            topLeft: const Radius.circular(16),
            topRight: const Radius.circular(16),
            bottomLeft: Radius.circular(isUser ? 16 : 4),
            bottomRight: Radius.circular(isUser ? 4 : 16),
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              message['message'] ?? '',
              style: TextStyle(
                color: isUser ? Colors.white : Colors.black87,
              ),
            ),
            const SizedBox(height: 4),
            Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  _formatTime(message['created_at']),
                  style: TextStyle(
                    fontSize: 10,
                    color: isUser ? Colors.white70 : Colors.grey,
                  ),
                ),
                if (isPending) ...[
                  const SizedBox(width: 4),
                  SizedBox(
                    width: 12,
                    height: 12,
                    child: CircularProgressIndicator(
                      strokeWidth: 1,
                      color: isUser ? Colors.white70 : Colors.grey,
                    ),
                  ),
                ],
                if (isFailed) ...[
                  const SizedBox(width: 4),
                  const Icon(Icons.error_outline, size: 12, color: Colors.red),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInputArea() {
    final loc = AppLocalizations.of(context);

    return Container(
      padding: const EdgeInsets.all(8),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 4,
            offset: const Offset(0, -2),
          ),
        ],
      ),
      child: SafeArea(
        child: Row(
          children: [
            Expanded(
              child: TextField(
                controller: _messageController,
                decoration: InputDecoration(
                  hintText: loc.translate('type_message'),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(24),
                    borderSide: BorderSide.none,
                  ),
                  filled: true,
                  fillColor: Colors.grey.shade100,
                  contentPadding: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 8,
                  ),
                ),
                textInputAction: TextInputAction.send,
                onSubmitted: (_) => _sendMessage(),
                maxLines: null,
              ),
            ),
            const SizedBox(width: 8),
            CircleAvatar(
              backgroundColor: AppTheme.primaryColor,
              child: IconButton(
                icon: _isSending
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: Colors.white,
                        ),
                      )
                    : const Icon(Icons.send, color: Colors.white),
                onPressed: _isSending ? null : _sendMessage,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRatingView() {
    final loc = AppLocalizations.of(context);
    int selectedRating = 0;
    final feedbackController = TextEditingController();

    return StatefulBuilder(
      builder: (context, setRatingState) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(
              Icons.chat_bubble_outline,
              size: 64,
              color: AppTheme.primaryColor,
            ),
            const SizedBox(height: 24),
            Text(
              loc.translate('chat_ended'),
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 8),
            Text(
              loc.translate('rate_experience'),
              style: Theme.of(context).textTheme.bodyMedium,
            ),
            const SizedBox(height: 24),

            // Star rating
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(5, (index) {
                return IconButton(
                  icon: Icon(
                    index < selectedRating ? Icons.star : Icons.star_border,
                    color: Colors.amber,
                    size: 40,
                  ),
                  onPressed: () {
                    setRatingState(() {
                      selectedRating = index + 1;
                    });
                  },
                );
              }),
            ),

            const SizedBox(height: 16),

            // Feedback
            TextField(
              controller: feedbackController,
              decoration: InputDecoration(
                hintText: loc.translate('feedback_optional'),
                border: const OutlineInputBorder(),
              ),
              maxLines: 3,
            ),

            const SizedBox(height: 24),

            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: Text(loc.translate('skip')),
                ),
                const SizedBox(width: 16),
                ElevatedButton(
                  onPressed: selectedRating > 0
                      ? () => _submitRating(
                            selectedRating,
                            feedbackController.text.isNotEmpty
                                ? feedbackController.text
                                : null,
                          )
                      : null,
                  child: Text(loc.translate('submit')),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  String _formatTime(String? dateStr) {
    if (dateStr == null) return '';
    try {
      final date = DateTime.parse(dateStr);
      return '${date.hour.toString().padLeft(2, '0')}:${date.minute.toString().padLeft(2, '0')}';
    } catch (_) {
      return '';
    }
  }
}
