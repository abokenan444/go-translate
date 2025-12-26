import 'package:flutter/material.dart';
import 'package:permission_handler/permission_handler.dart';
import '../config/app_theme.dart';
import '../services/permissions_service.dart';

class PermissionsSettingsScreen extends StatefulWidget {
  const PermissionsSettingsScreen({super.key});

  @override
  State<PermissionsSettingsScreen> createState() =>
      _PermissionsSettingsScreenState();
}

class _PermissionsSettingsScreenState extends State<PermissionsSettingsScreen> {
  final _permissionsService = PermissionsService();
  Map<String, PermissionStatus> _permissionStatuses = {};
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadPermissionStatuses();
  }

  Future<void> _loadPermissionStatuses() async {
    setState(() {
      _isLoading = true;
    });

    try {
      final statuses = await Future.wait([
        Permission.camera.status,
        Permission.microphone.status,
        Permission.contacts.status,
        Permission.location.status,
        Permission.notification.status,
        Permission.storage.status,
      ]);

      if (mounted) {
        setState(() {
          _permissionStatuses = {
            'camera': statuses[0],
            'microphone': statuses[1],
            'contacts': statuses[2],
            'location': statuses[3],
            'notification': statuses[4],
            'storage': statuses[5],
          };
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  Future<void> _requestPermission(Permission permission, String key) async {
    final status = await permission.request();

    if (mounted) {
      setState(() {
        _permissionStatuses[key] = status;
      });

      if (status.isPermanentlyDenied) {
        _showOpenSettingsDialog(key);
      }
    }
  }

  void _showOpenSettingsDialog(String permissionKey) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('فتح الإعدادات'),
        content: Text(
          'تم رفض إذن ${_getPermissionTitle(permissionKey)} بشكل دائم. يرجى فتح إعدادات التطبيق لمنح الإذن.',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('إلغاء'),
          ),
          TextButton(
            onPressed: () {
              openAppSettings();
              Navigator.pop(context);
            },
            child: const Text('فتح الإعدادات'),
          ),
        ],
      ),
    );
  }

  String _getPermissionTitle(String key) {
    switch (key) {
      case 'camera':
        return 'الكاميرا';
      case 'microphone':
        return 'الميكروفون';
      case 'contacts':
        return 'جهات الاتصال';
      case 'location':
        return 'الموقع';
      case 'notification':
        return 'الإشعارات';
      case 'storage':
        return 'التخزين';
      default:
        return key;
    }
  }

  IconData _getPermissionIcon(String key) {
    switch (key) {
      case 'camera':
        return Icons.videocam;
      case 'microphone':
        return Icons.mic;
      case 'contacts':
        return Icons.contacts;
      case 'location':
        return Icons.location_on;
      case 'notification':
        return Icons.notifications;
      case 'storage':
        return Icons.storage;
      default:
        return Icons.help_outline;
    }
  }

  Color _getStatusColor(PermissionStatus status) {
    switch (status) {
      case PermissionStatus.granted:
      case PermissionStatus.limited:
        return AppTheme.successColor;
      case PermissionStatus.denied:
        return AppTheme.warningColor;
      case PermissionStatus.permanentlyDenied:
      case PermissionStatus.restricted:
        return AppTheme.errorColor;
      default:
        return Colors.grey;
    }
  }

  String _getStatusText(PermissionStatus status) {
    switch (status) {
      case PermissionStatus.granted:
        return 'ممنوح';
      case PermissionStatus.denied:
        return 'مرفوض';
      case PermissionStatus.permanentlyDenied:
        return 'مرفوض نهائياً';
      case PermissionStatus.restricted:
        return 'محظور';
      case PermissionStatus.limited:
        return 'محدود';
      default:
        return 'غير معروف';
    }
  }

  Permission _getPermission(String key) {
    switch (key) {
      case 'camera':
        return Permission.camera;
      case 'microphone':
        return Permission.microphone;
      case 'contacts':
        return Permission.contacts;
      case 'location':
        return Permission.location;
      case 'notification':
        return Permission.notification;
      case 'storage':
        return Permission.storage;
      default:
        return Permission.camera;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('إدارة الأذونات'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadPermissionStatuses,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: _loadPermissionStatuses,
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: const [
                              Icon(Icons.info_outline,
                                  color: AppTheme.infoColor),
                              SizedBox(width: 8),
                              Text(
                                'حول الأذونات',
                                style: TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 12),
                          const Text(
                            'تحتاج بعض ميزات التطبيق إلى أذونات معينة للعمل بشكل صحيح. يمكنك منح أو إلغاء الأذونات في أي وقت.',
                            style: TextStyle(fontSize: 14),
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  ..._permissionStatuses.entries.map((entry) {
                    final status = entry.value;
                    final color = _getStatusColor(status);

                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      child: ListTile(
                        leading: CircleAvatar(
                          backgroundColor: color.withValues(alpha: 0.1),
                          child: Icon(
                            _getPermissionIcon(entry.key),
                            color: color,
                          ),
                        ),
                        title: Text(
                          _getPermissionTitle(entry.key),
                          style: const TextStyle(
                            fontWeight: FontWeight.w600,
                            fontSize: 16,
                          ),
                        ),
                        subtitle: Text(
                          _permissionsService.getPermissionRationale(entry.key),
                          style: const TextStyle(fontSize: 13),
                        ),
                        trailing: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 12,
                                vertical: 6,
                              ),
                              decoration: BoxDecoration(
                                color: color.withValues(alpha: 0.1),
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: Text(
                                _getStatusText(status),
                                style: TextStyle(
                                  color: color,
                                  fontSize: 12,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ),
                            const SizedBox(width: 8),
                            IconButton(
                              icon: Icon(
                                status.isGranted
                                    ? Icons.check_circle
                                    : Icons.error_outline,
                                color: color,
                              ),
                              onPressed: () {
                                if (!status.isGranted) {
                                  _requestPermission(
                                      _getPermission(entry.key), entry.key);
                                }
                              },
                            ),
                          ],
                        ),
                      ),
                    );
                  }).toList(),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton.icon(
                      onPressed: () async {
                        await _permissionsService.requestAllPermissions();
                        _loadPermissionStatuses();
                      },
                      icon: const Icon(Icons.settings),
                      label: const Text('طلب جميع الأذونات'),
                    ),
                  ),
                  const SizedBox(height: 12),
                  OutlinedButton.icon(
                    onPressed: openAppSettings,
                    icon: const Icon(Icons.open_in_new),
                    label: const Text('فتح إعدادات النظام'),
                  ),
                ],
              ),
            ),
    );
  }
}
