import 'package:flutter/material.dart';
import '../l10n/app_localizations.dart';

class PrivacySecurityScreen extends StatelessWidget {
  const PrivacySecurityScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.translate('privacy_security')),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          _Section(
            title: l10n.translate('privacy_summary'),
            body: l10n.translate('privacy_summary_text'),
          ),
          const SizedBox(height: 12),
          _Section(
            title: l10n.translate('what_we_collect'),
            body: l10n.translate('what_we_collect_text'),
          ),
          const SizedBox(height: 12),
          _Section(
            title: l10n.translate('calls_translation'),
            body: l10n.translate('calls_translation_text'),
          ),
          const SizedBox(height: 12),
          _Section(
            title: l10n.translate('legal_basis'),
            body: l10n.translate('legal_basis_text'),
          ),
          const SizedBox(height: 12),
          _Section(
            title: l10n.translate('your_rights'),
            body: l10n.translate('your_rights_text'),
          ),
          const SizedBox(height: 12),
          _Section(
            title: l10n.translate('security'),
            body: l10n.translate('security_text'),
          ),
          const SizedBox(height: 12),
          _Section(
            title: l10n.translate('data_retention'),
            body: l10n.translate('data_retention_text'),
          ),
        ],
      ),
    );
  }
}

class _Section extends StatelessWidget {
  final String title;
  final String body;

  const _Section({required this.title, required this.body});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: theme.textTheme.titleMedium?.copyWith(
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 10),
            Text(
              body,
              style: theme.textTheme.bodyMedium,
            ),
          ],
        ),
      ),
    );
  }
}
