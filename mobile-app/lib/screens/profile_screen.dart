import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import '../config/app_theme.dart';
import '../providers/auth_provider.dart';
import '../widgets/user_avatar.dart';
import '../widgets/custom_button.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.currentUser;

    if (user == null) {
      return const Scaffold(
        body: Center(
          child: CircularProgressIndicator(),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('الملف الشخصي'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit),
            onPressed: () {
              // TODO: Navigate to edit profile
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // Profile Header
            Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: AppTheme.primaryGradient,
                borderRadius: BorderRadius.circular(16),
              ),
              child: Column(
                children: [
                  UserAvatar(
                    imageUrl: user.avatar,
                    name: user.name,
                    size: 100,
                  ),
                  const SizedBox(height: 16),
                  Text(
                    user.name,
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    user.email,
                    style: TextStyle(
                      fontSize: 14,
                      color: Colors.white.withAlpha(230),
                    ),
                  ),
                  if (user.phone != null) ...[
                    const SizedBox(height: 4),
                    Text(
                      user.phone!,
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.white.withAlpha(230),
                      ),
                    ),
                  ],
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Stats Cards
            Row(
              children: [
                Expanded(
                  child: _buildStatCard(
                    icon: FontAwesomeIcons.phone,
                    label: 'إجمالي المكالمات',
                    value: '142',
                    color: AppTheme.primaryColor,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _buildStatCard(
                    icon: FontAwesomeIcons.clock,
                    label: 'مدة المكالمات',
                    value: '28س',
                    color: AppTheme.successColor,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // Account Info
            _buildSectionTitle('معلومات الحساب'),
            _buildInfoCard(
              icon: Icons.person_outline,
              title: 'نوع الحساب',
              value: _getAccountTypeLabel(user.accountType),
            ),
            _buildInfoCard(
              icon: Icons.verified_user_outlined,
              title: 'حالة الحساب',
              value: _getStatusLabel(user.status),
              valueColor: user.status == 'active'
                  ? AppTheme.successColor
                  : AppTheme.warningColor,
            ),
            _buildInfoCard(
              icon: Icons.shield_outlined,
              title: 'الدور',
              value: _getRoleLabel(user.role),
            ),
            _buildInfoCard(
              icon: Icons.calendar_today_outlined,
              title: 'تاريخ التسجيل',
              value: _formatDate(user.createdAt),
            ),
            const SizedBox(height: 24),

            // Actions
            _buildSectionTitle('الإجراءات'),
            CustomButton(
              text: 'تعديل الملف الشخصي',
              icon: Icons.edit,
              onPressed: () {
                // TODO: Navigate to edit profile
              },
            ),
            const SizedBox(height: 12),
            CustomButton(
              text: 'تغيير كلمة المرور',
              icon: Icons.lock_outline,
              isOutlined: true,
              onPressed: () {
                // TODO: Navigate to change password
              },
            ),
            const SizedBox(height: 12),
            CustomButton(
              text: 'تسجيل الخروج',
              icon: Icons.logout,
              backgroundColor: AppTheme.errorColor,
              onPressed: () async {
                final navigator = Navigator.of(context);
                final confirm = await showDialog<bool>(
                  context: context,
                  builder: (context) => AlertDialog(
                    title: const Text('تسجيل الخروج'),
                    content: const Text('هل أنت متأكد من تسجيل الخروج؟'),
                    actions: [
                      TextButton(
                        onPressed: () => Navigator.pop(context, false),
                        child: const Text('إلغاء'),
                      ),
                      TextButton(
                        onPressed: () => Navigator.pop(context, true),
                        child: const Text('تسجيل الخروج'),
                      ),
                    ],
                  ),
                );

                if (!mounted) return;
                if (confirm != true) return;
                await authProvider.logout();
                if (!mounted) return;
                navigator.pushNamedAndRemoveUntil(
                  '/login',
                  (route) => false,
                );
              },
            ),
            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }

  Widget _buildStatCard({
    required IconData icon,
    required String label,
    required String value,
    required Color color,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: color.withAlpha(26),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: color.withAlpha(77)),
      ),
      child: Column(
        children: [
          Icon(icon, color: color, size: 28),
          const SizedBox(height: 8),
          Text(
            value,
            style: TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: color,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              color: Colors.grey[600],
            ),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        children: [
          Text(
            title,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoCard({
    required IconData icon,
    required String title,
    required String value,
    Color? valueColor,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: AppTheme.primaryColor.withAlpha(26),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Icon(icon, color: AppTheme.primaryColor),
        ),
        title: Text(
          title,
          style: TextStyle(
            fontSize: 14,
            color: Colors.grey[600],
          ),
        ),
        subtitle: Text(
          value,
          style: TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.w600,
            color: valueColor ?? Colors.black87,
          ),
        ),
      ),
    );
  }

  String _getAccountTypeLabel(String type) {
    switch (type) {
      case 'individual':
        return 'فردي';
      case 'business':
        return 'أعمال';
      case 'enterprise':
        return 'مؤسسة';
      default:
        return type;
    }
  }

  String _getStatusLabel(String status) {
    switch (status) {
      case 'active':
        return 'نشط';
      case 'inactive':
        return 'غير نشط';
      case 'suspended':
        return 'معلق';
      default:
        return status;
    }
  }

  String _getRoleLabel(String role) {
    switch (role) {
      case 'user':
        return 'مستخدم';
      case 'translator':
        return 'مترجم';
      case 'admin':
        return 'مدير';
      default:
        return role;
    }
  }

  String _formatDate(DateTime? date) {
    if (date == null) return 'غير متوفر';
    return '${date.day}/${date.month}/${date.year}';
  }
}
