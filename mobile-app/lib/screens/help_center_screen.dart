import 'package:flutter/material.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';

class HelpCenterScreen extends StatefulWidget {
  const HelpCenterScreen({super.key});

  @override
  State<HelpCenterScreen> createState() => _HelpCenterScreenState();
}

class _HelpCenterScreenState extends State<HelpCenterScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _searchQuery = '';

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);

    return Scaffold(
      appBar: AppBar(
        title: Text(loc.translate('help_center')),
        backgroundColor: AppTheme.primaryColor,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Search Bar
            TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: loc.translate('search_help'),
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
                filled: true,
                fillColor: Colors.grey[100],
              ),
              onChanged: (value) => setState(() => _searchQuery = value),
            ),

            const SizedBox(height: 24),

            // Quick Actions
            Text(
              loc.translate('quick_actions'),
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppTheme.primaryColor,
              ),
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _buildQuickAction(
                    icon: Icons.email_outlined,
                    title: loc.translate('contact_support'),
                    onTap: () => _showContactDialog(context),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _buildQuickAction(
                    icon: Icons.chat_bubble_outline,
                    title: loc.translate('live_chat'),
                    onTap: () => _showComingSoon(context),
                  ),
                ),
              ],
            ),

            const SizedBox(height: 24),

            // FAQ Section
            Text(
              loc.translate('faq'),
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppTheme.primaryColor,
              ),
            ),
            const SizedBox(height: 12),

            _buildFAQItem(
              question: loc.translate('faq_q1'),
              answer: loc.translate('faq_a1'),
            ),
            _buildFAQItem(
              question: loc.translate('faq_q2'),
              answer: loc.translate('faq_a2'),
            ),
            _buildFAQItem(
              question: loc.translate('faq_q3'),
              answer: loc.translate('faq_a3'),
            ),
            _buildFAQItem(
              question: loc.translate('faq_q4'),
              answer: loc.translate('faq_a4'),
            ),
            _buildFAQItem(
              question: loc.translate('faq_q5'),
              answer: loc.translate('faq_a5'),
            ),

            const SizedBox(height: 24),

            // Help Topics
            Text(
              loc.translate('help_topics'),
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppTheme.primaryColor,
              ),
            ),
            const SizedBox(height: 12),

            _buildHelpTopic(
              icon: Icons.phone_in_talk,
              title: loc.translate('calls_help'),
              subtitle: loc.translate('calls_help_desc'),
            ),
            _buildHelpTopic(
              icon: Icons.translate,
              title: loc.translate('translation_help'),
              subtitle: loc.translate('translation_help_desc'),
            ),
            _buildHelpTopic(
              icon: Icons.account_circle,
              title: loc.translate('account_help'),
              subtitle: loc.translate('account_help_desc'),
            ),
            _buildHelpTopic(
              icon: Icons.payment,
              title: loc.translate('billing_help'),
              subtitle: loc.translate('billing_help_desc'),
            ),

            const SizedBox(height: 24),

            // Contact Info
            Card(
              color: AppTheme.primaryColor.withOpacity(0.1),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    const Icon(
                      Icons.support_agent,
                      size: 48,
                      color: AppTheme.primaryColor,
                    ),
                    const SizedBox(height: 12),
                    Text(
                      loc.translate('need_more_help'),
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'support@culturaltranslate.com',
                      style: TextStyle(
                        color: Colors.grey[700],
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      loc.translate('support_hours'),
                      style: TextStyle(
                        color: Colors.grey[600],
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildQuickAction({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
  }) {
    return Card(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              Icon(icon, size: 32, color: AppTheme.primaryColor),
              const SizedBox(height: 8),
              Text(
                title,
                textAlign: TextAlign.center,
                style: const TextStyle(fontWeight: FontWeight.w500),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildFAQItem({
    required String question,
    required String answer,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ExpansionTile(
        title: Text(
          question,
          style: const TextStyle(fontWeight: FontWeight.w500),
        ),
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
            child: Text(
              answer,
              style: TextStyle(color: Colors.grey[700]),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHelpTopic({
    required IconData icon,
    required String title,
    required String subtitle,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Icon(icon, color: AppTheme.primaryColor),
        title: Text(title),
        subtitle: Text(subtitle),
        trailing: const Icon(Icons.chevron_right),
        onTap: () => _showComingSoon(context),
      ),
    );
  }

  void _showContactDialog(BuildContext context) {
    final loc = AppLocalizations.of(context);
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(loc.translate('contact_support')),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ListTile(
              leading: const Icon(Icons.email),
              title: const Text('support@culturaltranslate.com'),
              onTap: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text(loc.translate('close')),
          ),
        ],
      ),
    );
  }

  void _showComingSoon(BuildContext context) {
    final loc = AppLocalizations.of(context);
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(loc.translate('coming_soon')),
        backgroundColor: AppTheme.primaryColor,
      ),
    );
  }
}
