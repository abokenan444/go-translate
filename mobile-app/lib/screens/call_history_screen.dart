import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';
import '../models/call_model.dart';

class CallHistoryScreen extends StatefulWidget {
  const CallHistoryScreen({super.key});

  @override
  State<CallHistoryScreen> createState() => _CallHistoryScreenState();
}

class _CallHistoryScreenState extends State<CallHistoryScreen>
    with SingleTickerProviderStateMixin {
  late final TabController _tabController;

  final List<CallModel> _allCalls = [];
  final List<CallModel> _missedCalls = [];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    _load();
  }

  Future<void> _load() async {
    // TODO: replace with API call when endpoint is available
    setState(() {
      _allCalls.clear();
      _missedCalls.clear();
    });
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(AppLocalizations.of(context).translate('call_history')),
        bottom: TabBar(
          controller: _tabController,
          tabs: [
            Tab(
                icon: const Icon(Icons.call),
                text: AppLocalizations.of(context).translate('all_calls')),
            Tab(
                icon: const Icon(Icons.call_missed),
                text: AppLocalizations.of(context).translate('missed_calls')),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          _buildList(_allCalls),
          _buildList(_missedCalls),
        ],
      ),
    );
  }

  Widget _buildList(List<CallModel> calls) {
    if (calls.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.phone_disabled_outlined,
                size: 72, color: Colors.grey[400]),
            const SizedBox(height: 12),
            Text(
              AppLocalizations.of(context).translate('no_calls'),
              style: TextStyle(color: Colors.grey[600], fontSize: 16),
            ),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: _load,
      child: ListView.separated(
        padding: const EdgeInsets.all(16),
        itemCount: calls.length,
        separatorBuilder: (_, __) => const Divider(height: 1),
        itemBuilder: (context, index) {
          final call = calls[index];
          return ListTile(
            leading: CircleAvatar(
              backgroundColor: AppTheme.primaryColor,
              child: Icon(
                call.type == CallType.video
                    ? FontAwesomeIcons.video
                    : FontAwesomeIcons.phone,
                color: Colors.white,
                size: 18,
              ),
            ),
            title: Text(call.fromUserName),
            subtitle: Text(_formatDateTime(call.startTime)),
            trailing: const Icon(Icons.chevron_right),
          );
        },
      ),
    );
  }

  String _formatDateTime(DateTime? dt) {
    if (dt == null) return '';
    return '${dt.day.toString().padLeft(2, '0')}/${dt.month.toString().padLeft(2, '0')}/${dt.year} '
        '${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')}';
  }
}
