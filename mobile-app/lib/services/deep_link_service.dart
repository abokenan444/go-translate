import 'dart:async';
import 'package:app_links/app_links.dart';
import 'package:flutter/foundation.dart';

class DeepLinkService {
  static final DeepLinkService _instance = DeepLinkService._internal();
  factory DeepLinkService() => _instance;
  DeepLinkService._internal();

  final _appLinks = AppLinks();
  StreamSubscription<Uri>? _linkSubscription;

  // Callbacks للتعامل مع أنواع مختلفة من الروابط
  Function(String referralCode)? onReferralLink;
  Function(String inviteCode)? onInviteLink;
  Function(String paymentStatus)? onPaymentLink;
  Function(String callId)? onCallLink;

  /// تهيئة خدمة Deep Links
  Future<void> init() async {
    // التحقق من وجود رابط عند فتح التطبيق (cold start)
    try {
      final initialLink = await _appLinks.getInitialLink();
      if (initialLink != null) {
        _handleDeepLink(initialLink);
      }
    } catch (e) {
      debugPrint('Error getting initial link: $e');
    }

    // الاستماع للروابط أثناء تشغيل التطبيق
    _linkSubscription = _appLinks.uriLinkStream.listen(
      (uri) {
        _handleDeepLink(uri);
      },
      onError: (err) {
        debugPrint('Deep link error: $err');
      },
    );
  }

  /// معالجة الروابط العميقة
  void _handleDeepLink(Uri uri) {
    debugPrint('Received deep link: $uri');

    // استخراج البارامترات من الرابط
    final path = uri.path;
    final queryParams = uri.queryParameters;

    // التعامل مع روابط التسجيل والدعوات
    if (path.contains('/register') || uri.host == 'register') {
      final referralCode =
          queryParams['ref'] ?? queryParams['referral'] ?? queryParams['code'];
      if (referralCode != null && onReferralLink != null) {
        onReferralLink!(referralCode);
      }
    }
    // التعامل مع روابط الدعوة المباشرة
    else if (path.contains('/invite') || uri.host == 'invite') {
      final inviteCode =
          queryParams['code'] ?? queryParams['invite'] ?? queryParams['id'];
      if (inviteCode != null && onInviteLink != null) {
        onInviteLink!(inviteCode);
      }
    }
    // التعامل مع روابط الدفع
    else if (path.contains('/payment') || uri.host == 'payment') {
      final status =
          queryParams['status'] ?? queryParams['session_id'] ?? 'success';
      if (onPaymentLink != null) {
        onPaymentLink!(status);
      }
    }
    // التعامل مع روابط المكالمات
    else if (path.contains('/call') || uri.host == 'call') {
      final callId =
          queryParams['id'] ?? queryParams['call_id'] ?? queryParams['session'];
      if (callId != null && onCallLink != null) {
        onCallLink!(callId);
      }
    }
  }

  /// توليد رابط دعوة للتطبيق
  String generateReferralLink(String referralCode) {
    // استخدام custom scheme للتطبيق
    return 'culturaltranslate://register?ref=$referralCode';
  }

  /// توليد رابط دعوة عبر HTTPS (Universal Link)
  String generateUniversalReferralLink(String referralCode) {
    // استخدام app subdomain للتطبيق
    return 'https://app.culturaltranslate.com/register?ref=$referralCode';
  }

  /// توليد رابط دعوة قابل للمشاركة (يعمل على الويب والتطبيق)
  String generateShareableReferralLink(String referralCode) {
    // هذا الرابط سيفتح التطبيق إذا كان مثبتاً، وإلا سيفتح صفحة الويب
    return 'https://app.culturaltranslate.com/register?ref=$referralCode';
  }

  /// إنشاء نص رسالة دعوة
  String generateInviteMessage(String referralCode, {String? userName}) {
    final link = generateShareableReferralLink(referralCode);
    final name = userName ?? 'صديقك';

    return '''
مرحباً! 👋

يدعوك $name للانضمام إلى CulturalTranslate - تطبيق المكالمات المترجمة فورياً! 🌍📞

✨ احصل على 30 دقيقة مجانية عند التسجيل باستخدام هذا الرابط:
$link

🎁 ميزات التطبيق:
• ترجمة فورية أثناء المكالمات
• دعم أكثر من 15 لغة
• مكالمات صوتية وفيديو عالية الجودة
• خصوصية وأمان مضمون

حمّل التطبيق الآن وابدأ التواصل بدون حواجز لغوية!
''';
  }

  /// تنظيف الموارد
  void dispose() {
    _linkSubscription?.cancel();
  }
}
