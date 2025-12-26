import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:flutter_callkit_incoming/flutter_callkit_incoming.dart';
import 'package:flutter_callkit_incoming/entities/entities.dart';
import 'package:uuid/uuid.dart';

/// معالج الإشعارات في الخلفية (يجب أن يكون في المستوى الأعلى)
@pragma('vm:entry-point')
Future<void> firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  debugPrint('معالجة إشعار في الخلفية: ${message.messageId}');

  if (message.data['type'] == 'incoming_call') {
    await NotificationService().showIncomingCallUI(
      callId: message.data['call_id'] ?? const Uuid().v4(),
      callerName: message.data['caller_name'] ?? 'مستخدم',
      callerAvatar: message.data['caller_avatar'],
      callType: message.data['call_type'] ?? 'video',
    );
  }
}

class NotificationService {
  static final NotificationService _instance = NotificationService._internal();
  factory NotificationService() => _instance;
  NotificationService._internal();

  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();
  final FirebaseMessaging _firebaseMessaging = FirebaseMessaging.instance;

  String? _fcmToken;
  String? get fcmToken => _fcmToken;

  /// تهيئة خدمة الإشعارات
  Future<void> init() async {
    try {
      // طلب أذونات الإشعارات
      final settings = await _firebaseMessaging.requestPermission(
        alert: true,
        announcement: true,
        badge: true,
        carPlay: false,
        criticalAlert: true,
        provisional: false,
        sound: true,
      );

      debugPrint('حالة أذونات الإشعارات: ${settings.authorizationStatus}');

      if (settings.authorizationStatus == AuthorizationStatus.authorized ||
          settings.authorizationStatus == AuthorizationStatus.provisional) {
        // الحصول على FCM Token
        _fcmToken = await _firebaseMessaging.getToken();
        debugPrint('FCM Token: $_fcmToken');

        // الاستماع لتحديثات التوكن
        _firebaseMessaging.onTokenRefresh.listen((token) {
          _fcmToken = token;
          debugPrint('FCM Token محدث: $token');
          // يمكن إرسال التوكن للسيرفر هنا
        });

        // تهيئة الإشعارات المحلية
        await _initLocalNotifications();

        // معالجة الإشعارات في المقدمة
        FirebaseMessaging.onMessage.listen(_handleForegroundMessage);

        // معالجة الإشعارات عند النقر عليها (التطبيق في الخلفية)
        FirebaseMessaging.onMessageOpenedApp.listen(_handleMessageOpenedApp);

        // معالجة الإشعارات عند فتح التطبيق من إشعار (التطبيق مغلق)
        final initialMessage = await _firebaseMessaging.getInitialMessage();
        if (initialMessage != null) {
          _handleMessageOpenedApp(initialMessage);
        }
      }
    } catch (e) {
      debugPrint('خطأ في تهيئة خدمة الإشعارات: $e');
    }
  }

  /// تهيئة الإشعارات المحلية
  Future<void> _initLocalNotifications() async {
    const androidSettings =
        AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );

    const settings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );

    await _localNotifications.initialize(
      settings,
      onDidReceiveNotificationResponse: _onNotificationTapped,
    );

    // إنشاء قناة إشعارات المكالمات (Android)
    const callsChannel = AndroidNotificationChannel(
      'calls_channel',
      'المكالمات',
      description: 'إشعارات المكالمات الواردة',
      importance: Importance.max,
      playSound: true,
      enableVibration: true,
      sound: RawResourceAndroidNotificationSound('ringtone'),
    );

    await _localNotifications
        .resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(callsChannel);
  }

  /// معالجة الإشعارات في المقدمة
  void _handleForegroundMessage(RemoteMessage message) {
    debugPrint('إشعار وارد في المقدمة: ${message.notification?.title}');

    if (message.data['type'] == 'incoming_call') {
      showIncomingCallUI(
        callId: message.data['call_id'] ?? const Uuid().v4(),
        callerName: message.data['caller_name'] ?? 'مستخدم',
        callerAvatar: message.data['caller_avatar'],
        callType: message.data['call_type'] ?? 'video',
      );
    } else {
      // إشعار عادي
      _showLocalNotification(
        title: message.notification?.title ?? 'إشعار جديد',
        body: message.notification?.body ?? '',
        payload: jsonEncode(message.data),
      );
    }
  }

  /// معالجة فتح الإشعار
  void _handleMessageOpenedApp(RemoteMessage message) {
    debugPrint('تم فتح التطبيق من إشعار: ${message.data}');

    // يمكن التنقل إلى الشاشة المناسبة هنا
    if (message.data['type'] == 'incoming_call') {
      // فتح شاشة المكالمة
    } else if (message.data['type'] == 'message') {
      // فتح شاشة الرسائل
    }
  }

  /// عرض إشعار محلي
  Future<void> _showLocalNotification({
    required String title,
    required String body,
    String? payload,
  }) async {
    const androidDetails = AndroidNotificationDetails(
      'general_channel',
      'عام',
      channelDescription: 'إشعارات عامة',
      importance: Importance.high,
      priority: Priority.high,
      icon: '@mipmap/ic_launcher',
    );

    const iosDetails = DarwinNotificationDetails(
      presentAlert: true,
      presentBadge: true,
      presentSound: true,
    );

    const details = NotificationDetails(
      android: androidDetails,
      iOS: iosDetails,
    );

    await _localNotifications.show(
      DateTime.now().millisecondsSinceEpoch ~/ 1000,
      title,
      body,
      details,
      payload: payload,
    );
  }

  /// عرض شاشة المكالمة الواردة (Full Screen)
  Future<void> showIncomingCallUI({
    required String callId,
    required String callerName,
    String? callerAvatar,
    String callType = 'video',
  }) async {
    try {
      final params = CallKitParams(
        id: callId,
        nameCaller: callerName,
        appName: 'Cultural Translate',
        avatar: callerAvatar,
        handle: callId,
        type: callType == 'video' ? 1 : 0, // 0: audio, 1: video
        duration: 30000, // 30 ثانية قبل إلغاء المكالمة تلقائياً
        textAccept: 'قبول',
        textDecline: 'رفض',
        missedCallNotification: NotificationParams(
          showNotification: true,
          isShowCallback: false,
          subtitle: 'مكالمة فائتة',
        ),
        extra: <String, dynamic>{
          'call_id': callId,
          'call_type': callType,
        },
        headers: <String, dynamic>{'platform': 'flutter'},
        android: AndroidParams(
          isCustomNotification: true,
          isShowLogo: true,
          ringtonePath: 'ringtone',
          backgroundColor: '#1a73e8',
          backgroundUrl: callerAvatar,
          actionColor: '#4CAF50',
          incomingCallNotificationChannelName: 'مكالمة واردة',
        ),
        ios: IOSParams(
          iconName: 'CallKitLogo',
          handleType: callType == 'video' ? 'generic' : 'number',
          supportsVideo: callType == 'video',
          maximumCallGroups: 1,
          maximumCallsPerCallGroup: 1,
          audioSessionMode: 'videoChat',
          audioSessionActive: true,
          audioSessionPreferredSampleRate: 44100.0,
          audioSessionPreferredIOBufferDuration: 0.005,
          supportsDTMF: true,
          supportsHolding: false,
          supportsGrouping: false,
          supportsUngrouping: false,
          ringtonePath: 'ringtone.caf',
        ),
      );

      await FlutterCallkitIncoming.showCallkitIncoming(params);

      // الاستماع لأحداث المكالمة
      FlutterCallkitIncoming.onEvent.listen((CallEvent? event) {
        if (event != null) {
          _handleCallEvent(event);
        }
      });
    } catch (e) {
      debugPrint('خطأ في عرض شاشة المكالمة الواردة: $e');
    }
  }

  /// معالجة أحداث المكالمة
  void _handleCallEvent(CallEvent event) {
    switch (event.event) {
      case Event.actionCallAccept:
        debugPrint('تم قبول المكالمة: ${event.body['id']}');
        // الانتقال إلى شاشة المكالمة
        break;
      case Event.actionCallDecline:
        debugPrint('تم رفض المكالمة: ${event.body['id']}');
        break;
      case Event.actionCallEnded:
        debugPrint('انتهت المكالمة: ${event.body['id']}');
        break;
      case Event.actionCallTimeout:
        debugPrint('انتهت مهلة المكالمة: ${event.body['id']}');
        break;
      case Event.actionCallCallback:
        debugPrint('معاودة الاتصال: ${event.body['id']}');
        break;
      default:
        debugPrint('حدث مكالمة: ${event.event}');
    }
  }

  /// معالجة النقر على الإشعار
  void _onNotificationTapped(NotificationResponse response) {
    debugPrint('تم النقر على الإشعار: ${response.payload}');

    if (response.payload != null) {
      final data = jsonDecode(response.payload!);
      // التنقل إلى الشاشة المناسبة حسب نوع الإشعار
    }
  }

  /// إرسال FCM Token للسيرفر
  Future<void> updateFcmToken(String userId) async {
    if (_fcmToken != null) {
      // يمكن إرسال التوكن للسيرفر هنا
      debugPrint('إرسال FCM Token للمستخدم $userId: $_fcmToken');
      // await ApiService().updateFcmToken(userId, _fcmToken!);
    }
  }

  /// إلغاء جميع الإشعارات
  Future<void> cancelAllNotifications() async {
    await _localNotifications.cancelAll();
  }

  /// إنهاء جميع المكالمات النشطة
  Future<void> endAllCalls() async {
    await FlutterCallkitIncoming.endAllCalls();
  }
}
