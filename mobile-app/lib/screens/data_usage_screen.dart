import 'package:flutter/material.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';

class DataUsageScreen extends StatefulWidget {
  const DataUsageScreen({super.key});

  @override
  State<DataUsageScreen> createState() => _DataUsageScreenState();
}

class _DataUsageScreenState extends State<DataUsageScreen> {
  bool _wifiOnly = false;
  bool _autoDownload = true;
  bool _reducedQualityMobile = true;

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);

    return Scaffold(
      appBar: AppBar(
        title: Text(loc.translate('data_usage')),
        backgroundColor: AppTheme.primaryColor,
        foregroundColor: Colors.white,
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // Data Usage Summary Card
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.data_usage,
                          color: AppTheme.primaryColor, size: 32),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              loc.translate('data_usage_summary'),
                              style: const TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              loc.translate('this_month'),
                              style: TextStyle(
                                color: Colors.grey[600],
                                fontSize: 14,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 20),
                  _buildUsageBar(
                      loc.translate('calls'), 0.6, '1.2 GB', Colors.blue),
                  const SizedBox(height: 12),
                  _buildUsageBar(loc.translate('translation'), 0.3, '600 MB',
                      Colors.green),
                  const SizedBox(height: 12),
                  _buildUsageBar(
                      loc.translate('other'), 0.1, '200 MB', Colors.orange),
                  const Divider(height: 32),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        loc.translate('total_usage'),
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      const Text(
                        '2.0 GB',
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          color: AppTheme.primaryColor,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),

          const SizedBox(height: 24),

          // Data Saving Options
          Text(
            loc.translate('data_saving_options'),
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: AppTheme.primaryColor,
            ),
          ),
          const SizedBox(height: 12),

          Card(
            child: Column(
              children: [
                SwitchListTile(
                  secondary:
                      const Icon(Icons.wifi, color: AppTheme.primaryColor),
                  title: Text(loc.translate('wifi_only_calls')),
                  subtitle: Text(loc.translate('wifi_only_calls_desc')),
                  value: _wifiOnly,
                  onChanged: (value) {
                    setState(() {
                      _wifiOnly = value;
                    });
                  },
                  activeColor: AppTheme.primaryColor,
                ),
                const Divider(height: 1),
                SwitchListTile(
                  secondary:
                      const Icon(Icons.download, color: AppTheme.primaryColor),
                  title: Text(loc.translate('auto_download')),
                  subtitle: Text(loc.translate('auto_download_desc')),
                  value: _autoDownload,
                  onChanged: (value) {
                    setState(() {
                      _autoDownload = value;
                    });
                  },
                  activeColor: AppTheme.primaryColor,
                ),
                const Divider(height: 1),
                SwitchListTile(
                  secondary: const Icon(Icons.mobile_friendly,
                      color: AppTheme.primaryColor),
                  title: Text(loc.translate('reduced_quality_mobile')),
                  subtitle: Text(loc.translate('reduced_quality_mobile_desc')),
                  value: _reducedQualityMobile,
                  onChanged: (value) {
                    setState(() {
                      _reducedQualityMobile = value;
                    });
                  },
                  activeColor: AppTheme.primaryColor,
                ),
              ],
            ),
          ),

          const SizedBox(height: 24),

          // Clear Cache
          Card(
            child: ListTile(
              leading: const Icon(Icons.cleaning_services,
                  color: AppTheme.primaryColor),
              title: Text(loc.translate('clear_cache')),
              subtitle: Text(loc.translate('clear_cache_desc')),
              trailing: const Icon(Icons.chevron_right),
              onTap: () {
                _showClearCacheDialog();
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildUsageBar(String label, double value, String size, Color color) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(label),
            Text(size, style: TextStyle(color: Colors.grey[600])),
          ],
        ),
        const SizedBox(height: 4),
        ClipRRect(
          borderRadius: BorderRadius.circular(4),
          child: LinearProgressIndicator(
            value: value,
            backgroundColor: Colors.grey[200],
            valueColor: AlwaysStoppedAnimation<Color>(color),
            minHeight: 8,
          ),
        ),
      ],
    );
  }

  void _showClearCacheDialog() {
    final loc = AppLocalizations.of(context);
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(loc.translate('clear_cache')),
        content: Text(loc.translate('clear_cache_confirm')),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text(loc.translate('cancel')),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(content: Text(loc.translate('cache_cleared'))),
              );
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.primaryColor,
              foregroundColor: Colors.white,
            ),
            child: Text(loc.translate('clear')),
          ),
        ],
      ),
    );
  }
}
