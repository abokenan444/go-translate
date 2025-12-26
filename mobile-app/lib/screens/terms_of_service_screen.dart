import 'package:flutter/material.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';

class TermsOfServiceScreen extends StatelessWidget {
  const TermsOfServiceScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);

    return Scaffold(
      appBar: AppBar(
        title: Text(loc.translate('terms_of_service')),
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
                      Icons.description,
                      size: 48,
                      color: AppTheme.primaryColor,
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            loc.translate('terms_of_service'),
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

            // Terms Sections
            _buildSection(
              number: '1',
              title: loc.translate('tos_acceptance'),
              content: loc.translate('tos_acceptance_text'),
            ),

            _buildSection(
              number: '2',
              title: loc.translate('tos_services'),
              content: loc.translate('tos_services_text'),
            ),

            _buildSection(
              number: '3',
              title: loc.translate('tos_account'),
              content: loc.translate('tos_account_text'),
            ),

            _buildSection(
              number: '4',
              title: loc.translate('tos_usage'),
              content: loc.translate('tos_usage_text'),
            ),

            _buildSection(
              number: '5',
              title: loc.translate('tos_payment'),
              content: loc.translate('tos_payment_text'),
            ),

            _buildSection(
              number: '6',
              title: loc.translate('tos_intellectual'),
              content: loc.translate('tos_intellectual_text'),
            ),

            _buildSection(
              number: '7',
              title: loc.translate('tos_termination'),
              content: loc.translate('tos_termination_text'),
            ),

            _buildSection(
              number: '8',
              title: loc.translate('tos_liability'),
              content: loc.translate('tos_liability_text'),
            ),

            _buildSection(
              number: '9',
              title: loc.translate('tos_changes'),
              content: loc.translate('tos_changes_text'),
            ),

            _buildSection(
              number: '10',
              title: loc.translate('tos_contact'),
              content: loc.translate('tos_contact_text'),
            ),

            const SizedBox(height: 24),

            // Accept Terms Button
            Card(
              color: Colors.green.withOpacity(0.1),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Row(
                  children: [
                    const Icon(Icons.check_circle,
                        color: Colors.green, size: 32),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        loc.translate('terms_accepted'),
                        style: const TextStyle(
                          fontWeight: FontWeight.w500,
                        ),
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
    required String number,
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
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: 28,
                  height: 28,
                  decoration: const BoxDecoration(
                    color: AppTheme.primaryColor,
                    shape: BoxShape.circle,
                  ),
                  child: Center(
                    child: Text(
                      number,
                      style: const TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 14,
                      ),
                    ),
                  ),
                ),
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
            Padding(
              padding: const EdgeInsets.only(left: 40),
              child: Text(
                content,
                style: TextStyle(
                  color: Colors.grey[700],
                  height: 1.5,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
