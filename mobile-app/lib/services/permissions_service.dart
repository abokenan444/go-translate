import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';
import 'package:permission_handler/permission_handler.dart';

class PermissionsService {
  static final PermissionsService _instance = PermissionsService._internal();
  factory PermissionsService() => _instance;
  PermissionsService._internal();

  static const platform =
      MethodChannel('com.culturaltranslate.app/permissions');

  /// طلب جميع الأذونات المطلوبة للتطبيق
  Future<bool> requestAllPermissions() async {
    try {
      // طلب الأذونات الأساسية
      final statuses = await [
        Permission.camera,
        Permission.microphone,
        Permission.contacts,
        Permission.location,
        Permission.locationWhenInUse,
        Permission.notification,
        Permission.storage,
        Permission.photos,
        Permission.mediaLibrary,
      ].request();

      // التحقق من جميع الأذونات
      bool allGranted = statuses.values
          .every((status) => status.isGranted || status.isLimited);

      return allGranted;
    } catch (e) {
      debugPrint('خطأ في طلب الأذونات: $e');
      return false;
    }
  }

  /// التحقق من حالة أذونات معينة
  Future<Map<String, bool>> checkPermissionsStatus() async {
    return {
      'camera': await Permission.camera.isGranted,
      'microphone': await Permission.microphone.isGranted,
      'contacts': await Permission.contacts.isGranted,
      'location': await Permission.location.isGranted,
      'notifications': await Permission.notification.isGranted,
      'storage': await Permission.storage.isGranted,
    };
  }

  /// طلب إذن الكاميرا
  Future<bool> requestCameraPermission() async {
    final status = await Permission.camera.request();
    return status.isGranted;
  }

  /// طلب إذن الميكروفون
  Future<bool> requestMicrophonePermission() async {
    final status = await Permission.microphone.request();
    return status.isGranted;
  }

  /// طلب إذن جهات الاتصال
  Future<bool> requestContactsPermission() async {
    final status = await Permission.contacts.request();
    return status.isGranted;
  }

  /// طلب إذن الموقع
  Future<bool> requestLocationPermission() async {
    final status = await Permission.location.request();
    if (!status.isGranted) {
      final whenInUse = await Permission.locationWhenInUse.request();
      return whenInUse.isGranted;
    }
    return status.isGranted;
  }

  /// طلب إذن الإشعارات
  Future<bool> requestNotificationPermission() async {
    final status = await Permission.notification.request();
    return status.isGranted;
  }

  /// التحقق من أذونات المكالمات (كاميرا + ميكروفون)
  Future<bool> checkCallPermissions() async {
    final camera = await Permission.camera.status;
    final microphone = await Permission.microphone.status;
    return camera.isGranted && microphone.isGranted;
  }

  /// طلب أذونات المكالمات
  Future<bool> requestCallPermissions() async {
    final statuses = await [
      Permission.camera,
      Permission.microphone,
    ].request();

    return statuses.values.every((status) => status.isGranted);
  }

  /// فتح إعدادات التطبيق لتغيير الأذونات يدوياً
  Future<bool> openSettings() async {
    return await openAppSettings();
  }

  /// عرض حوار توضيحي للأذونات
  String getPermissionRationale(String permissionName) {
    switch (permissionName) {
      case 'camera':
        return 'يحتاج التطبيق للوصول إلى الكاميرا لإجراء مكالمات الفيديو المترجمة.';
      case 'microphone':
        return 'يحتاج التطبيق للوصول إلى الميكروفون لإجراء المكالمات الصوتية والترجمة الفورية.';
      case 'contacts':
        return 'يحتاج التطبيق للوصول إلى جهات الاتصال لمساعدتك في الاتصال بأصدقائك.';
      case 'location':
        return 'يحتاج التطبيق لمعرفة موقعك لتحسين الخدمة وتوفير إحصائيات دقيقة.';
      case 'notifications':
        return 'يحتاج التطبيق لإرسال إشعارات لإعلامك بالمكالمات الواردة والرسائل المهمة.';
      case 'storage':
        return 'يحتاج التطبيق للوصول إلى الملفات لحفظ سجلات المكالمات والترجمات.';
      default:
        return 'يحتاج التطبيق لهذا الإذن لتوفير أفضل تجربة استخدام.';
    }
  }
}
