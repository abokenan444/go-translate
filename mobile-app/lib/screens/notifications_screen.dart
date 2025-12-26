import 'package:flutter/material.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';
import '../services/api_service.dart';

class NotificationsScreen extends StatefulWidget {
  const NotificationsScreen({super.key});

  @override
  State<NotificationsScreen> createState() => _NotificationsScreenState();
}

class _NotificationsScreenState extends State<NotificationsScreen> {
  bool _loading = true;
  String? _error;
  List<Map<String, dynamic>> _items = const [];

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });

    try {
      final list = await ApiService().getMobileNotifications();
      if (!mounted) return;
      setState(() {
        _items = list;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e.toString();
      });
    } finally {
      if (mounted) {
        setState(() {
          _loading = false;
        });
      }
    }
  }

  Future<void> _markRead(Map<String, dynamic> n) async {
    final id = int.tryParse(n['id']?.toString() ?? '');
    if (id == null) return;

    try {
      await ApiService().markMobileNotificationRead(id);
      await _load();
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
              '${AppLocalizations.of(context).translate('failed_update_notification')}: $e'),
          backgroundColor: AppTheme.errorColor,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(AppLocalizations.of(context).translate('notifications')),
        actions: [
          IconButton(
            tooltip: AppLocalizations.of(context).translate('refresh'),
            icon: const Icon(Icons.refresh),
            onPressed: _loading ? null : _load,
          ),
        ],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Text(_error!,
                        style: const TextStyle(color: AppTheme.errorColor)),
                  ),
                )
              : _items.isEmpty
                  ? Center(
                      child: Text(AppLocalizations.of(context)
                          .translate('no_notifications')))
                  : ListView.separated(
                      itemCount: _items.length,
                      separatorBuilder: (_, __) => const Divider(height: 1),
                      itemBuilder: (context, index) {
                        final n = _items[index];
                        final title = n['title']?.toString() ?? '';
                        final body = n['body']?.toString() ?? '';
                        final read = n['read'] == true;

                        return ListTile(
                          title: Text(
                            title.isEmpty ? 'إشعار' : title,
                            style: TextStyle(
                              fontWeight:
                                  read ? FontWeight.w400 : FontWeight.w700,
                            ),
                          ),
                          subtitle: body.isEmpty ? null : Text(body),
                          trailing: read
                              ? const Icon(Icons.check, size: 18)
                              : const Icon(Icons.circle,
                                  size: 10, color: AppTheme.primaryColor),
                          onTap: read ? null : () => _markRead(n),
                        );
                      },
                    ),
    );
  }
}
