import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/app_theme.dart';
import '../l10n/app_localizations.dart';
import '../providers/auth_provider.dart';
import '../providers/theme_provider.dart';
import '../services/api_service.dart';
import '../services/deep_link_service.dart';
import 'data_usage_screen.dart';
import 'help_center_screen.dart';
import 'live_chat_screen.dart';
import 'privacy_policy_screen.dart';
import 'terms_of_service_screen.dart';

class SettingsScreen extends StatefulWidget {
  const SettingsScreen({super.key});

  @override
  State<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> {
  bool _notifications = true;
  bool _soundEnabled = true;
  bool _vibrationEnabled = true;
  bool _loadingLang = true;
  String? _langError;
  List<Map<String, dynamic>> _supportedLanguages = const [];

  String _appLanguage = 'en';
  String _defaultSendLanguage = 'auto';
  String _defaultReceiveLanguage = 'en';
  String _quality = 'high';

  bool _loadingWallet = true;
  String? _walletError;
  int _walletBalanceMinutes = 0;
  List<Map<String, dynamic>> _minutePackages = const [];

  bool _loadingInvites = true;
  String? _invitesError;
  String? _referralCode;
  String? _referralLink;
  int _rewardMinutesPerInvite = 0;
  List<Map<String, dynamic>> _invites = const [];
  final _inviteEmailController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _loadLanguageSettings();
    _loadWallet();
    _loadInvites();
  }

  @override
  void dispose() {
    _inviteEmailController.dispose();
    super.dispose();
  }

  Future<void> _loadInvites() async {
    setState(() {
      _loadingInvites = true;
      _invitesError = null;
    });

    try {
      final root = await ApiService().getInvitesOverview();
      if (!mounted) return;

      if (root['success'] == true) {
        final invites = root['invites'];
        setState(() {
          _referralCode = root['invite_code']?.toString();
          _referralLink = root['referral_link']?.toString();
          _rewardMinutesPerInvite = (root['reward_minutes_per_invite'] is num)
              ? (root['reward_minutes_per_invite'] as num).toInt()
              : int.tryParse(
                      root['reward_minutes_per_invite']?.toString() ?? '') ??
                  0;
          _invites = invites is List
              ? invites
                  .whereType<Map>()
                  .map((e) => Map<String, dynamic>.from(e))
                  .toList()
              : const [];
        });
      } else {
        setState(() {
          _invitesError =
              root['message']?.toString() ?? 'Failed to load invites';
        });
      }
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _invitesError = e.toString();
      });
    } finally {
      if (mounted) {
        setState(() {
          _loadingInvites = false;
        });
      }
    }
  }

  Future<void> _copyText(String text, String label) async {
    await Clipboard.setData(ClipboardData(text: text));
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('تم نسخ $label'),
        backgroundColor: AppTheme.primaryColor,
      ),
    );
  }

  Future<void> _shareReferralLink() async {
    if (_referralCode == null || _referralCode!.isEmpty) return;

    final message = DeepLinkService().generateInviteMessage(_referralCode!);
    await Clipboard.setData(ClipboardData(text: message));

    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('تم نسخ رسالة الدعوة! يمكنك لصقها في أي تطبيق للمشاركة'),
        backgroundColor: AppTheme.successColor,
        duration: Duration(seconds: 3),
      ),
    );
  }

  Future<void> _sendInviteEmail() async {
    final email = _inviteEmailController.text.trim();
    if (email.isEmpty) return;

    try {
      final root = await ApiService().createInvite(email: email);
      if (!mounted) return;

      if (root['success'] == true) {
        _inviteEmailController.clear();
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('تم إرسال الدعوة بنجاح'),
            backgroundColor: AppTheme.successColor,
          ),
        );
        await _loadInvites();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(root['message']?.toString() ?? 'فشل إرسال الدعوة'),
            backgroundColor: AppTheme.errorColor,
          ),
        );
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('فشل إرسال الدعوة: $e'),
          backgroundColor: AppTheme.errorColor,
        ),
      );
    }
  }

  Future<void> _loadWallet() async {
    setState(() {
      _loadingWallet = true;
      _walletError = null;
    });

    try {
      final balance = await ApiService().getWalletBalance();
      final packages = await ApiService().getMinutePackages();
      final minutes = (balance['balance_minutes'] is num)
          ? (balance['balance_minutes'] as num).toInt()
          : int.tryParse(balance['balance_minutes']?.toString() ?? '') ?? 0;

      if (!mounted) return;
      setState(() {
        _walletBalanceMinutes = minutes;
        // Always ensure packages are available (API returns defaults if needed)
        _minutePackages =
            packages.isNotEmpty ? packages : _getLocalDefaultPackages();
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        // Load default packages even on error so users can still see options
        _minutePackages = _getLocalDefaultPackages();
        // Don't show error if we have packages to display
        _walletError = null;
      });
    } finally {
      if (mounted) {
        setState(() {
          _loadingWallet = false;
        });
      }
    }
  }

  List<Map<String, dynamic>> _getLocalDefaultPackages() {
    return [
      {'id': 'm30', 'minutes': 30, 'price_eur': 19.00},
      {'id': 'm120', 'minutes': 120, 'price_eur': 69.00},
      {'id': 'm300', 'minutes': 300, 'price_eur': 159.00},
      {'id': 'm1000', 'minutes': 1000, 'price_eur': 449.00},
    ];
  }

  Future<void> _buyPackage(Map<String, dynamic> pkg) async {
    final id = pkg['id']?.toString();
    if (id == null || id.isEmpty) return;

    // Show loading indicator
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => const Center(
        child: CircularProgressIndicator(),
      ),
    );

    try {
      final url = await ApiService().createMinutesCheckout(packageId: id);

      // Close loading dialog
      if (mounted) Navigator.of(context).pop();

      final uri = Uri.tryParse(url);
      if (uri == null) throw Exception('Invalid checkout URL');

      final ok = await launchUrl(uri, mode: LaunchMode.externalApplication);
      if (!ok) throw Exception('Could not open checkout');

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
              AppLocalizations.of(context).translate('payment_page_opened')),
          backgroundColor: AppTheme.primaryColor,
          duration: const Duration(seconds: 5),
        ),
      );
    } catch (e) {
      // Close loading dialog if still showing
      if (mounted) Navigator.of(context, rootNavigator: true).pop();

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content:
              Text(AppLocalizations.of(context).translate('payment_error')),
          backgroundColor: AppTheme.errorColor,
          duration: const Duration(seconds: 4),
        ),
      );
    }
  }

  Future<void> _addTestMinutes() async {
    try {
      final response = await ApiService().addTestMinutes(30);
      if (response['success'] == true) {
        await _loadWallet(); // Refresh balance
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
                AppLocalizations.of(context).translate('test_minutes_added')),
            backgroundColor: Colors.green,
          ),
        );
      } else {
        throw Exception(response['message'] ?? 'Failed');
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
              AppLocalizations.of(context).translate('test_minutes_error')),
          backgroundColor: AppTheme.errorColor,
        ),
      );
    }
  }

  Future<void> _loadLanguageSettings() async {
    setState(() {
      _loadingLang = true;
      _langError = null;
    });

    try {
      final langs = await ApiService().getMobileLanguages();

      // تحميل الإعدادات بشكل منفصل لضمان عدم تأثر اللغات
      Map<String, dynamic> settings = {};
      try {
        settings = await ApiService().getMobileSettings();
      } catch (_) {
        // استخدام القيم الافتراضية إذا فشل تحميل الإعدادات
      }

      final appLang = settings['app_language']?.toString() ?? 'ar';
      final sendLang = settings['default_send_language']?.toString() ?? 'auto';
      final recvLang = settings['default_receive_language']?.toString() ?? 'en';

      if (!mounted) return;
      setState(() {
        _supportedLanguages = langs;
        _appLanguage = appLang;
        _defaultSendLanguage = sendLang;
        _defaultReceiveLanguage = recvLang;
      });
    } catch (e) {
      if (!mounted) return;
      // حتى في حالة الخطأ، نحمّل اللغات الافتراضية
      setState(() {
        _supportedLanguages = _getDefaultLanguages();
        _langError = null; // لا نعرض خطأ إذا نجحنا في تحميل اللغات الافتراضية
      });
    } finally {
      if (mounted) {
        setState(() {
          _loadingLang = false;
        });
      }
    }
  }

  List<Map<String, dynamic>> _getDefaultLanguages() {
    return [
      {'code': 'ar', 'name': 'العربية', 'native_name': 'العربية'},
      {'code': 'en', 'name': 'English', 'native_name': 'English'},
      {'code': 'fr', 'name': 'Français', 'native_name': 'Français'},
      {'code': 'de', 'name': 'Deutsch', 'native_name': 'Deutsch'},
      {'code': 'es', 'name': 'Español', 'native_name': 'Español'},
      {'code': 'it', 'name': 'Italiano', 'native_name': 'Italiano'},
      {'code': 'pt', 'name': 'Português', 'native_name': 'Português'},
      {'code': 'ru', 'name': 'Русский', 'native_name': 'Русский'},
      {'code': 'zh', 'name': '中文', 'native_name': '中文'},
      {'code': 'ja', 'name': '日本語', 'native_name': '日本語'},
      {'code': 'ko', 'name': '한국어', 'native_name': '한국어'},
      {'code': 'tr', 'name': 'Türkçe', 'native_name': 'Türkçe'},
      {'code': 'hi', 'name': 'हिन्दी', 'native_name': 'हिन्दी'},
      {'code': 'ur', 'name': 'اردو', 'native_name': 'اردو'},
      {'code': 'nl', 'name': 'Nederlands', 'native_name': 'Nederlands'},
    ];
  }

  Future<void> _saveLanguageSettings() async {
    setState(() {
      _loadingLang = true;
      _langError = null;
    });

    try {
      final updated = await ApiService().updateMobileSettings({
        'app_language': _appLanguage,
        'default_send_language': _defaultSendLanguage,
        'default_receive_language': _defaultReceiveLanguage,
      });

      if (!mounted) return;
      setState(() {
        _appLanguage = updated['app_language']?.toString() ?? _appLanguage;
        _defaultSendLanguage = updated['default_send_language']?.toString() ??
            _defaultSendLanguage;
        _defaultReceiveLanguage =
            updated['default_receive_language']?.toString() ??
                _defaultReceiveLanguage;
      });

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('تم حفظ إعدادات اللغة بنجاح'),
          backgroundColor: AppTheme.successColor,
        ),
      );
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _langError = e.toString();
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('فشل حفظ الإعدادات: $e'),
          backgroundColor: AppTheme.errorColor,
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          _loadingLang = false;
        });
      }
    }
  }

  String _langName(String code, BuildContext context) {
    if (code == 'auto')
      return AppLocalizations.of(context).translate('auto_detect');
    final found = _supportedLanguages
        .where((l) => l['code']?.toString() == code)
        .toList();
    if (found.isEmpty) return code;
    return found.first['name']?.toString() ?? code;
  }

  List<DropdownMenuItem<String>> _buildLanguageItems(BuildContext context,
      {bool includeAuto = false}) {
    final items = <DropdownMenuItem<String>>[];
    if (includeAuto) {
      items.add(DropdownMenuItem(
        value: 'auto',
        child: Text(AppLocalizations.of(context).translate('auto_detect')),
      ));
    }
    for (final l in _supportedLanguages) {
      final code = l['code']?.toString();
      if (code == null || code.isEmpty) continue;
      final name = l['name']?.toString() ?? code;
      items.add(DropdownMenuItem(value: code, child: Text(name)));
    }
    return items;
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final themeProvider = Provider.of<ThemeProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: Text(AppLocalizations.of(context).translate('settings')),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // Account Section
          _buildSectionHeader(
              AppLocalizations.of(context).translate('account')),
          _buildSettingCard(
            icon: Icons.person,
            title: AppLocalizations.of(context).translate('profile'),
            subtitle: AppLocalizations.of(context).translate('edit_account'),
            onTap: () {
              Navigator.pushNamed(context, '/profile');
            },
          ),
          _buildSettingCard(
            icon: Icons.history,
            title: AppLocalizations.of(context).translate('call_history'),
            subtitle: AppLocalizations.of(context).translate('view_calls'),
            onTap: () {
              Navigator.pushNamed(context, '/call-history');
            },
          ),
          _buildSettingCard(
            icon: Icons.security,
            title: AppLocalizations.of(context).translate('privacy_security'),
            subtitle:
                AppLocalizations.of(context).translate('privacy_subtitle'),
            onTap: () {
              Navigator.pushNamed(context, '/privacy-security');
            },
          ),

          const SizedBox(height: 24),

          // Appearance Section
          _buildSectionHeader(
              AppLocalizations.of(context).translate('appearance')),
          _buildSwitchCard(
            icon: Icons.dark_mode,
            title: AppLocalizations.of(context).translate('dark_mode'),
            subtitle:
                AppLocalizations.of(context).translate('enable_dark_mode'),
            value: themeProvider.isDark,
            onChanged: (value) {
              themeProvider.setDarkMode(value);
            },
          ),

          const SizedBox(height: 24),

          _buildSectionHeader(
              AppLocalizations.of(context).translate('minutes_section')),
          Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.timer, color: AppTheme.primaryColor),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Text(
                          AppLocalizations.of(context)
                              .translate('minutes_balance'),
                          style: const TextStyle(
                              fontSize: 16, fontWeight: FontWeight.w600),
                        ),
                      ),
                      IconButton(
                        tooltip:
                            AppLocalizations.of(context).translate('refresh'),
                        onPressed: _loadingWallet ? null : _loadWallet,
                        icon: _loadingWallet
                            ? const SizedBox(
                                width: 18,
                                height: 18,
                                child:
                                    CircularProgressIndicator(strokeWidth: 2))
                            : const Icon(Icons.refresh),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  if (_walletError != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: Text(_walletError!,
                          style: const TextStyle(color: AppTheme.errorColor)),
                    ),
                  Text(
                    '${AppLocalizations.of(context).translate('current_balance')}: $_walletBalanceMinutes ${AppLocalizations.of(context).translate('minute')}',
                    style: const TextStyle(fontSize: 14),
                  ),
                  const SizedBox(height: 12),
                  if (_minutePackages.isEmpty && !_loadingWallet)
                    Text(AppLocalizations.of(context)
                        .translate('no_packages_available')),
                  for (final pkg in _minutePackages)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: SizedBox(
                        height: 44,
                        child: ElevatedButton(
                          onPressed: () => _buyPackage(pkg),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.primaryColor,
                            foregroundColor: Colors.white,
                          ),
                          child: Text(
                            '${AppLocalizations.of(context).translate('buy_x_minutes')} ${pkg['minutes']} ${AppLocalizations.of(context).translate('minute')} - €${pkg['price_eur']}',
                            textAlign: TextAlign.center,
                          ),
                        ),
                      ),
                    ),
                  // زر إضافة دقائق مجانية للاختبار (متاح في وضع التطوير فقط)
                  const SizedBox(height: 8),
                  SizedBox(
                    height: 44,
                    child: OutlinedButton.icon(
                      onPressed: () => _addTestMinutes(),
                      icon: const Icon(Icons.add_circle_outline),
                      label: Text(AppLocalizations.of(context)
                          .translate('add_test_minutes')),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: Colors.green,
                        side: const BorderSide(color: Colors.green),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),

          const SizedBox(height: 24),

          _buildSectionHeader(
              AppLocalizations.of(context).translate('invite_section')),
          Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.card_giftcard,
                          color: AppTheme.primaryColor),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Text(
                          AppLocalizations.of(context)
                              .translate('invite_friends_subscribe'),
                          style: const TextStyle(
                              fontSize: 16, fontWeight: FontWeight.w600),
                        ),
                      ),
                      IconButton(
                        tooltip:
                            AppLocalizations.of(context).translate('refresh'),
                        onPressed: _loadingInvites ? null : _loadInvites,
                        icon: _loadingInvites
                            ? const SizedBox(
                                width: 18,
                                height: 18,
                                child:
                                    CircularProgressIndicator(strokeWidth: 2))
                            : const Icon(Icons.refresh),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  if (_invitesError != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: Text(_invitesError!,
                          style: const TextStyle(color: AppTheme.errorColor)),
                    ),
                  if (_referralCode != null && _referralCode!.isNotEmpty) ...[
                    Text(
                        '${AppLocalizations.of(context).translate('your_referral_code')} ${_referralCode!}'),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        Expanded(
                          child: SizedBox(
                            height: 40,
                            child: OutlinedButton(
                              onPressed: () => _copyText(
                                  _referralCode!,
                                  AppLocalizations.of(context)
                                      .translate('referral_code')),
                              child: Text(AppLocalizations.of(context)
                                  .translate('copy_code')),
                            ),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: SizedBox(
                            height: 40,
                            child: OutlinedButton(
                              onPressed: (_referralLink == null ||
                                      _referralLink!.isEmpty)
                                  ? null
                                  : () => _copyText(
                                      _referralLink!,
                                      AppLocalizations.of(context)
                                          .translate('referral_link')),
                              child: Text(AppLocalizations.of(context)
                                  .translate('copy_link')),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    SizedBox(
                      height: 40,
                      child: ElevatedButton.icon(
                        onPressed: () => _shareReferralLink(),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppTheme.primaryColor,
                          foregroundColor: Colors.white,
                        ),
                        icon: const Icon(Icons.share),
                        label: Text(AppLocalizations.of(context)
                            .translate('share_app_link')),
                      ),
                    ),
                    const SizedBox(height: 8),
                    if (_rewardMinutesPerInvite > 0)
                      Text(
                          '${AppLocalizations.of(context).translate('reward_per_invite')} $_rewardMinutesPerInvite ${AppLocalizations.of(context).translate('minutes')}'),
                    const SizedBox(height: 6),
                    Text(
                        '${AppLocalizations.of(context).translate('invites_sent')}: ${_invites.length}'),
                  ] else
                    Text(AppLocalizations.of(context)
                        .translate('referral_not_loaded')),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _inviteEmailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: InputDecoration(
                      labelText: AppLocalizations.of(context)
                          .translate('friend_email'),
                      prefixIcon: const Icon(Icons.email_outlined),
                    ),
                  ),
                  const SizedBox(height: 10),
                  SizedBox(
                    height: 44,
                    child: ElevatedButton(
                      onPressed: _sendInviteEmail,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryColor,
                        foregroundColor: Colors.white,
                      ),
                      child: Text(AppLocalizations.of(context)
                          .translate('send_invite')),
                    ),
                  ),
                ],
              ),
            ),
          ),

          _buildSectionHeader(
              AppLocalizations.of(context).translate('realtime_translation')),
          Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.translate, color: AppTheme.primaryColor),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Text(
                          AppLocalizations.of(context)
                              .translate('call_translation_languages'),
                          style: const TextStyle(
                              fontSize: 16, fontWeight: FontWeight.w600),
                        ),
                      ),
                      if (_loadingLang)
                        const SizedBox(
                            width: 18,
                            height: 18,
                            child: CircularProgressIndicator(strokeWidth: 2)),
                    ],
                  ),
                  const SizedBox(height: 12),
                  if (_langError != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: Text(
                        _langError!,
                        style: const TextStyle(color: AppTheme.errorColor),
                      ),
                    ),
                  if (!_loadingLang && _supportedLanguages.isEmpty)
                    Text(AppLocalizations.of(context)
                        .translate('languages_not_loaded')),
                  const SizedBox(height: 8),
                  DropdownButtonFormField<String>(
                    value: _appLanguage,
                    decoration: InputDecoration(
                      labelText: AppLocalizations.of(context)
                          .translate('app_language'),
                      prefixIcon: const Icon(Icons.language),
                    ),
                    items: _buildLanguageItems(context),
                    onChanged: _loadingLang
                        ? null
                        : (v) {
                            if (v == null) return;
                            setState(() {
                              _appLanguage = v;
                            });
                          },
                  ),
                  const SizedBox(height: 12),
                  DropdownButtonFormField<String>(
                    value: _defaultSendLanguage,
                    decoration: InputDecoration(
                      labelText: AppLocalizations.of(context)
                          .translate('send_language'),
                      prefixIcon: const Icon(Icons.mic),
                    ),
                    items: _buildLanguageItems(context, includeAuto: true),
                    onChanged: _loadingLang
                        ? null
                        : (v) {
                            if (v == null) return;
                            setState(() {
                              _defaultSendLanguage = v;
                            });
                          },
                  ),
                  const SizedBox(height: 12),
                  DropdownButtonFormField<String>(
                    value: _defaultReceiveLanguage,
                    decoration: InputDecoration(
                      labelText: AppLocalizations.of(context)
                          .translate('receive_language'),
                      prefixIcon: const Icon(Icons.hearing),
                    ),
                    items: _buildLanguageItems(context),
                    onChanged: _loadingLang
                        ? null
                        : (v) {
                            if (v == null) return;
                            setState(() {
                              _defaultReceiveLanguage = v;
                            });
                          },
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    height: 44,
                    child: ElevatedButton.icon(
                      onPressed: _loadingLang ? null : _saveLanguageSettings,
                      icon: const Icon(Icons.save),
                      label: Text(_loadingLang
                          ? AppLocalizations.of(context).translate('saving')
                          : AppLocalizations.of(context).translate('save')),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryColor,
                        foregroundColor: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    '${AppLocalizations.of(context).translate('current_settings')} ${_langName(_defaultSendLanguage, context)} → ${_langName(_defaultReceiveLanguage, context)}',
                    style: TextStyle(color: Colors.grey[600], fontSize: 12),
                  ),
                ],
              ),
            ),
          ),

          const SizedBox(height: 24),

          // Notifications Section
          _buildSectionHeader(
              AppLocalizations.of(context).translate('notifications_section')),
          _buildSwitchCard(
            icon: Icons.notifications,
            title: AppLocalizations.of(context).translate('notifications'),
            subtitle:
                AppLocalizations.of(context).translate('enable_notifications'),
            value: _notifications,
            onChanged: (value) {
              setState(() {
                _notifications = value;
              });
            },
          ),
          _buildSwitchCard(
            icon: Icons.volume_up,
            title: AppLocalizations.of(context).translate('sound'),
            subtitle: AppLocalizations.of(context).translate('enable_sound'),
            value: _soundEnabled,
            onChanged: (value) {
              setState(() {
                _soundEnabled = value;
              });
            },
          ),
          _buildSwitchCard(
            icon: Icons.vibration,
            title: AppLocalizations.of(context).translate('vibration'),
            subtitle:
                AppLocalizations.of(context).translate('enable_vibration'),
            value: _vibrationEnabled,
            onChanged: (value) {
              setState(() {
                _vibrationEnabled = value;
              });
            },
          ),

          const SizedBox(height: 24),

          // Call Settings Section
          _buildSectionHeader(
              AppLocalizations.of(context).translate('call_settings_section')),
          _buildSettingCard(
            icon: Icons.high_quality,
            title: AppLocalizations.of(context).translate('call_quality'),
            subtitle: _getQualityText(_quality),
            trailing: DropdownButton<String>(
              value: _quality,
              underline: const SizedBox(),
              items: [
                DropdownMenuItem(
                    value: 'low',
                    child: Text(
                        AppLocalizations.of(context).translate('quality_low'))),
                DropdownMenuItem(
                    value: 'medium',
                    child: Text(AppLocalizations.of(context)
                        .translate('quality_medium'))),
                DropdownMenuItem(
                    value: 'high',
                    child: Text(AppLocalizations.of(context)
                        .translate('quality_high'))),
              ],
              onChanged: (value) {
                setState(() {
                  _quality = value!;
                });
              },
            ),
          ),
          _buildSettingCard(
            icon: Icons.data_usage,
            title: AppLocalizations.of(context).translate('data_usage'),
            subtitle: AppLocalizations.of(context).translate('data_usage_desc'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                    builder: (context) => const DataUsageScreen()),
              );
            },
          ),

          const SizedBox(height: 24),

          // Support Section
          _buildSectionHeader(
              AppLocalizations.of(context).translate('support_section')),
          _buildSettingCard(
            icon: Icons.help_outline,
            title: AppLocalizations.of(context).translate('help_center'),
            subtitle:
                AppLocalizations.of(context).translate('help_center_desc'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                    builder: (context) => const HelpCenterScreen()),
              );
            },
          ),
          _buildSettingCard(
            icon: Icons.chat_bubble_outline,
            title: AppLocalizations.of(context).translate('live_chat'),
            subtitle:
                AppLocalizations.of(context).translate('live_chat_desc'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                    builder: (context) => const LiveChatScreen()),
              );
            },
          ),
          _buildSettingCard(
            icon: Icons.info_outline,
            title: AppLocalizations.of(context).translate('about_app'),
            subtitle:
                '${AppLocalizations.of(context).translate('version')} 1.0.0',
            onTap: () {
              _showAboutDialog();
            },
          ),
          _buildSettingCard(
            icon: Icons.policy,
            title: AppLocalizations.of(context).translate('privacy_policy'),
            subtitle:
                AppLocalizations.of(context).translate('privacy_policy_desc'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                    builder: (context) => const PrivacyPolicyScreen()),
              );
            },
          ),
          _buildSettingCard(
            icon: Icons.description,
            title: AppLocalizations.of(context).translate('terms_of_service'),
            subtitle:
                AppLocalizations.of(context).translate('terms_of_service_desc'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                    builder: (context) => const TermsOfServiceScreen()),
              );
            },
          ),

          const SizedBox(height: 24),

          // Logout Button
          SizedBox(
            height: 50,
            child: ElevatedButton.icon(
              onPressed: () async {
                final navigator = Navigator.of(context);
                final confirmed = await _showLogoutConfirmDialog();
                if (!mounted) return;
                if (!confirmed) return;
                await authProvider.logout();
                if (!mounted) return;
                navigator.pushNamedAndRemoveUntil(
                  '/login',
                  (route) => false,
                );
              },
              icon: const Icon(Icons.logout),
              label: Text(AppLocalizations.of(context).translate('logout')),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.errorColor,
                foregroundColor: Colors.white,
              ),
            ),
          ),
          const SizedBox(height: 40),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12, top: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.bold,
          color: AppTheme.primaryColor,
        ),
      ),
    );
  }

  Widget _buildSettingCard({
    required IconData icon,
    required String title,
    required String subtitle,
    VoidCallback? onTap,
    Widget? trailing,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Icon(icon, color: AppTheme.primaryColor),
        title: Text(title),
        subtitle: Text(subtitle),
        trailing: trailing ?? const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }

  Widget _buildSwitchCard({
    required IconData icon,
    required String title,
    required String subtitle,
    required bool value,
    required ValueChanged<bool> onChanged,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: SwitchListTile(
        secondary: Icon(icon, color: AppTheme.primaryColor),
        title: Text(title),
        subtitle: Text(subtitle),
        value: value,
        onChanged: onChanged,
        activeColor: AppTheme.primaryColor,
      ),
    );
  }

  String _getQualityText(String quality) {
    final loc = AppLocalizations.of(context);
    switch (quality) {
      case 'low':
        return loc.translate('quality_low_desc');
      case 'medium':
        return loc.translate('quality_medium_desc');
      case 'high':
        return loc.translate('quality_high_desc');
      default:
        return loc.translate('quality_medium_desc');
    }
  }

  void _showAboutDialog() {
    final loc = AppLocalizations.of(context);
    showAboutDialog(
      context: context,
      applicationName: 'CulturalTranslate',
      applicationVersion: '1.0.0',
      applicationIcon: const Icon(
        Icons.translate,
        size: 48,
        color: AppTheme.primaryColor,
      ),
      children: [
        Text(
          loc.translate('about_dialog_text'),
        ),
        const SizedBox(height: 16),
        Text(
          loc.translate('all_rights_reserved'),
        ),
      ],
    );
  }

  Future<bool> _showLogoutConfirmDialog() async {
    final loc = AppLocalizations.of(context);
    return await showDialog<bool>(
          context: context,
          builder: (context) => AlertDialog(
            title: Text(loc.translate('logout')),
            content: Text(loc.translate('logout_confirm')),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context, false),
                child: Text(loc.translate('cancel')),
              ),
              ElevatedButton(
                onPressed: () => Navigator.pop(context, true),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.errorColor,
                  foregroundColor: Colors.white,
                ),
                child: Text(loc.translate('logout')),
              ),
            ],
          ),
        ) ??
        false;
  }
}
