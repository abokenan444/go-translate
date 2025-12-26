import 'package:flutter/material.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';

class PrivacyPolicyScreen extends StatelessWidget {
  const PrivacyPolicyScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);

    return Scaffold(
      appBar: AppBar(
        title: Text(loc.translate('privacy_policy')),
        backgroundColor: AppTheme.primaryColor,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header
            Card(
              color: AppTheme.primaryColor.withOpacity(0.1),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Row(
                  children: [
                    const Icon(
                      Icons.security,
                      size: 48,
                      color: AppTheme.primaryColor,
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            loc.translate('privacy_policy'),
                            style: const TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            loc.translate('last_updated') + ': 25/12/2025',
                            style: TextStyle(
                              color: Colors.grey[600],
                              fontSize: 12,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 24),

            // Privacy Summary
            _buildSection(
              icon: Icons.summarize,
              title: loc.translate('privacy_summary'),
              content: loc.translate('privacy_summary_text'),
            ),

            // What We Collect
            _buildSection(
              icon: Icons.folder_open,
              title: loc.translate('what_we_collect'),
              content: loc.translate('what_we_collect_text'),
            ),

            // Calls & Translation
            _buildSection(
              icon: Icons.call,
              title: loc.translate('calls_translation'),
              content: loc.translate('calls_translation_text'),
            ),

            // Legal Basis
            _buildSection(
              icon: Icons.gavel,
              title: loc.translate('legal_basis'),
              content: loc.translate('legal_basis_text'),
            ),

            // Your Rights
            _buildSection(
              icon: Icons.verified_user,
              title: loc.translate('your_rights'),
              content: loc.translate('your_rights_text'),
            ),

            // Security
            _buildSection(
              icon: Icons.lock,
              title: loc.translate('security'),
              content: loc.translate('security_text'),
            ),

            // Data Retention
            _buildSection(
              icon: Icons.access_time,
              title: loc.translate('data_retention'),
              content: loc.translate('data_retention_text'),
            ),

            const SizedBox(height: 24),

            // Contact Section
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.contact_mail,
                            color: AppTheme.primaryColor),
                        const SizedBox(width: 12),
                        Text(
                          loc.translate('contact_us'),
                          style: const TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Text(
                      loc.translate('privacy_contact_text'),
                      style: TextStyle(color: Colors.grey[700]),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'privacy@culturaltranslate.com',
                      style: const TextStyle(
                        color: AppTheme.primaryColor,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 16),

            // Footer
            Center(
              child: Text(
                '© 2025 CulturalTranslate',
                style: TextStyle(
                  color: Colors.grey[500],
                  fontSize: 12,
                ),
              ),
            ),
            const SizedBox(height: 16),
          ],
        ),
      ),
    );
  }

  Widget _buildSection({
    required IconData icon,
    required String title,
    required String content,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, color: AppTheme.primaryColor, size: 24),
                const SizedBox(width: 12),
                Expanded(
                  child: Text(
                    title,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Text(
              content,
              style: TextStyle(
                color: Colors.grey[700],
                height: 1.5,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
