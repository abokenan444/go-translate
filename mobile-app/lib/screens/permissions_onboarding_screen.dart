import 'package:flutter/material.dart';
import '../config/app_theme.dart';
import '../services/permissions_service.dart';

class PermissionsOnboardingScreen extends StatefulWidget {
  const PermissionsOnboardingScreen({super.key});

  @override
  State<PermissionsOnboardingScreen> createState() =>
      _PermissionsOnboardingScreenState();
}

class _PermissionsOnboardingScreenState
    extends State<PermissionsOnboardingScreen> {
  final _permissionsService = PermissionsService();
  bool _isLoading = false;

  final List<Map<String, dynamic>> _permissions = [
    {
      'icon': Icons.mic,
      'title': 'الميكروفون',
      'description': 'لإجراء المكالمات الصوتية والترجمة الفورية',
      'color': AppTheme.primaryColor,
    },
    {
      'icon': Icons.videocam,
      'title': 'الكاميرا',
      'description': 'لإجراء مكالمات الفيديو المترجمة',
      'color': AppTheme.secondaryColor,
    },
    {
      'icon': Icons.contacts,
      'title': 'جهات الاتصال',
      'description': 'للاتصال بأصدقائك وعائلتك بسهولة',
      'color': AppTheme.successColor,
    },
    {
      'icon': Icons.location_on,
      'title': 'الموقع',
      'description': 'لتحسين الخدمة وتوفير إحصائيات دقيقة',
      'color': AppTheme.infoColor,
    },
    {
      'icon': Icons.notifications,
      'title': 'الإشعارات',
      'description': 'لإعلامك بالمكالمات الواردة والرسائل',
      'color': AppTheme.warningColor,
    },
  ];

  Future<void> _requestPermissions() async {
    setState(() {
      _isLoading = true;
    });

    try {
      await _permissionsService.requestAllPermissions();

      if (!mounted) return;

      // الانتقال للشاشة الرئيسية بعد منح الأذونات
      Navigator.pushReplacementNamed(context, '/home');
    } catch (e) {
      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('حدث خطأ أثناء طلب الأذونات: $e'),
          backgroundColor: AppTheme.errorColor,
        ),
      );
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  Future<void> _skipForNow() async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('تخطي الأذونات؟'),
        content: const Text(
          'قد لا تعمل بعض ميزات التطبيق بشكل صحيح بدون هذه الأذونات. يمكنك منح الأذونات لاحقاً من الإعدادات.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('إلغاء'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('تخطي'),
          ),
        ],
      ),
    );

    if (confirmed == true && mounted) {
      Navigator.pushReplacementNamed(context, '/home');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: AppTheme.primaryGradient,
        ),
        child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.all(24.0),
            child: Column(
              children: [
                const SizedBox(height: 40),
                const Icon(
                  Icons.security,
                  size: 80,
                  color: Colors.white,
                ),
                const SizedBox(height: 24),
                const Text(
                  'الأذونات المطلوبة',
                  style: TextStyle(
                    fontSize: 32,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 12),
                const Text(
                  'نحتاج إلى بعض الأذونات لتوفير أفضل تجربة لك',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 16,
                    color: Colors.white70,
                  ),
                ),
                const SizedBox(height: 40),
                Expanded(
                  child: ListView.builder(
                    itemCount: _permissions.length,
                    itemBuilder: (context, index) {
                      final permission = _permissions[index];
                      return Card(
                        margin: const EdgeInsets.only(bottom: 16),
                        child: ListTile(
                          leading: CircleAvatar(
                            backgroundColor:
                                permission['color'].withOpacity(0.1),
                            child: Icon(
                              permission['icon'],
                              color: permission['color'],
                            ),
                          ),
                          title: Text(
                            permission['title'],
                            style: const TextStyle(
                              fontWeight: FontWeight.w600,
                              fontSize: 16,
                            ),
                          ),
                          subtitle: Text(
                            permission['description'],
                            style: const TextStyle(fontSize: 14),
                          ),
                        ),
                      );
                    },
                  ),
                ),
                const SizedBox(height: 24),
                SizedBox(
                  width: double.infinity,
                  height: 56,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _requestPermissions,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.white,
                      foregroundColor: AppTheme.primaryColor,
                    ),
                    child: _isLoading
                        ? const SizedBox(
                            width: 24,
                            height: 24,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: AppTheme.primaryColor,
                            ),
                          )
                        : const Text(
                            'منح الأذونات',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                  ),
                ),
                const SizedBox(height: 12),
                TextButton(
                  onPressed: _isLoading ? null : _skipForNow,
                  child: const Text(
                    'تخطي الآن',
                    style: TextStyle(
                      color: Colors.white70,
                      fontSize: 16,
                    ),
                  ),
                ),
                const SizedBox(height: 20),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
