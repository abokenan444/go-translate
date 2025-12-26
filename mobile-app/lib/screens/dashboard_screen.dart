import 'package:flutter/material.dart';
import '../l10n/app_localizations.dart';
import '../services/api_service.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final _apiService = ApiService();
  Map<String, dynamic>? _stats;
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadStats();
  }

  Future<void> _loadStats() async {
    final result = await _apiService.getDashboardStats();
    if (mounted) {
      setState(() {
        _isLoading = false;
        if (result['success']) {
          _stats = result['data'];
        } else {
          _error = result['message'];
        }
      });
    }
  }

  Future<void> _logout() async {
    await _apiService.logout();
    if (mounted) {
      Navigator.pushReplacementNamed(context, '/login');
    }
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.translate('dashboard')),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: _logout,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Text('Error: $_error'))
              : RefreshIndicator(
                  onRefresh: _loadStats,
                  child: ListView(
                    padding: const EdgeInsets.all(16.0),
                    children: [
                      _buildStatCard(
                        'Active Projects',
                        _stats?['active_projects']?.toString() ?? '0',
                        Icons.work,
                        Colors.blue,
                      ),
                      const SizedBox(height: 16),
                      _buildStatCard(
                        'Completed Translations',
                        _stats?['completed_translations']?.toString() ?? '0',
                        Icons.check_circle,
                        Colors.green,
                      ),
                      const SizedBox(height: 16),
                      _buildStatCard(
                        'Words Translated',
                        _stats?['total_words']?.toString() ?? '0',
                        Icons.translate,
                        Colors.orange,
                      ),
                      const SizedBox(height: 24),
                      const Text(
                        'Recent Activity',
                        style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8),
                      // Placeholder for recent activity list
                      const Card(
                        child: ListTile(
                          leading: Icon(Icons.history),
                          title: Text('Project "Website V2" updated'),
                          subtitle: Text('2 hours ago'),
                        ),
                      ),
                    ],
                  ),
                ),
    );
  }

  Widget _buildStatCard(
      String title, String value, IconData icon, Color color) {
    return Card(
      elevation: 4,
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: color.withAlpha(26),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(icon, color: color, size: 32),
            ),
            const SizedBox(width: 16),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: TextStyle(
                    fontSize: 14,
                    color: Colors.grey[600],
                  ),
                ),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
