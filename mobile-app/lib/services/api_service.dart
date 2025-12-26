import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../config/app_config.dart';
import '../models/user.dart';

class ApiService {
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  late Dio _dio;
  late Dio _dioMobile;
  String? _token;

  String? get token => _token;

  Future<void> init() async {
    _dio = Dio(BaseOptions(
      baseUrl: AppConfig.apiUrl,
      connectTimeout: AppConfig.apiTimeout,
      receiveTimeout: AppConfig.apiTimeout,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));

    _dioMobile = Dio(BaseOptions(
      baseUrl: AppConfig.mobileApiUrl,
      connectTimeout: AppConfig.apiTimeout,
      receiveTimeout: AppConfig.apiTimeout,
      headers: {
        'Accept': 'application/json',
      },
    ));

    // Add interceptors
    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        if (_token != null) {
          options.headers['Authorization'] = 'Bearer $_token';
        }
        return handler.next(options);
      },
      onError: (error, handler) {
        if (error.response?.statusCode == 401) {
          logout();
        }
        return handler.next(error);
      },
    ));

    _dioMobile.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        if (_token != null) {
          options.headers['Authorization'] = 'Bearer $_token';
        }
        return handler.next(options);
      },
      onError: (error, handler) {
        if (error.response?.statusCode == 401) {
          logout();
        }
        return handler.next(error);
      },
    ));

    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString(AppConfig.keyToken);
  }

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });

      if (response.statusCode == 200) {
        final root = response.data;
        final data = (root is Map<String, dynamic>) ? root['data'] : null;
        final token = (data is Map) ? data['token']?.toString() : null;
        final user = (data is Map) ? data['user'] : null;
        _token = token;

        final prefs = await SharedPreferences.getInstance();
        if (token != null && token.isNotEmpty) {
          await prefs.setString(AppConfig.keyToken, token);
        }
        if (user != null) {
          await prefs.setString(AppConfig.keyUserData, jsonEncode(user));
        }

        return {'success': true, 'data': data};
      }

      return {'success': false, 'message': 'Login failed'};
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
    String accountType = 'individual',
    String? referralCode,
    String? inviteCode,
  }) async {
    try {
      final response = await _dio.post('/register', data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        if (phone != null && phone.trim().isNotEmpty) 'phone': phone.trim(),
        if (referralCode != null && referralCode.trim().isNotEmpty)
          'referral_code': referralCode.trim(),
        if (inviteCode != null && inviteCode.trim().isNotEmpty)
          'invite_code': inviteCode.trim(),
      });

      if (response.statusCode == 200 || response.statusCode == 201) {
        final root = response.data;
        final data = (root is Map<String, dynamic>) ? root['data'] : null;
        final token = (data is Map) ? data['token']?.toString() : null;
        final user = (data is Map) ? data['user'] : null;

        if (token != null && token.isNotEmpty) {
          _token = token;
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString(AppConfig.keyToken, token);
          if (user != null) {
            await prefs.setString(AppConfig.keyUserData, jsonEncode(user));
          }
        }

        return {'success': true, 'data': data};
      }

      return {'success': false, 'message': 'Register failed'};
    } on DioException catch (e) {
      final message = e.response?.data is Map
          ? (e.response?.data['message']?.toString() ?? e.message)
          : e.message;
      return {'success': false, 'message': message ?? 'Register failed'};
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  // -------------------------
  // Mobile notifications (api/mobile/notifications)
  // -------------------------
  Future<List<Map<String, dynamic>>> getMobileNotifications() async {
    try {
      final response = await _dioMobile.get('/notifications');
      final root = response.data;
      final list =
          (root is Map<String, dynamic>) ? root['notifications'] : null;
      if (list is List && list.isNotEmpty) {
        return list
            .whereType<Map>()
            .map((e) => Map<String, dynamic>.from(e))
            .toList();
      }
    } catch (e) {
      // Return empty if API fails (better UX than error)
    }
    return [];
  }

  Future<void> markMobileNotificationRead(int id) async {
    await _dioMobile.post('/notifications/$id/read');
  }

  Future<int> getMobileUnreadCount() async {
    final response = await _dioMobile.get('/notifications/unread-count');
    final root = response.data;
    final count = (root is Map<String, dynamic>) ? root['count'] : null;
    if (count is num) return count.toInt();
    return int.tryParse(count?.toString() ?? '') ?? 0;
  }

  // -------------------------
  // Mobile invites / referral (api/mobile/invites)
  // -------------------------
  Future<Map<String, dynamic>> getInvitesOverview() async {
    try {
      final response = await _dioMobile.get('/invites');
      final root = response.data;
      if (root is Map<String, dynamic> && root['success'] != false) {
        // استبدال رابط الموقع برابط التطبيق
        if (root['referral_link'] != null) {
          final code = root['invite_code']?.toString() ?? '';
          if (code.isNotEmpty) {
            root['referral_link'] =
                'https://app.culturaltranslate.com/register?ref=$code';
            root['app_link'] = 'culturaltranslate://register?ref=$code';
          }
        }
        return root;
      }
    } catch (e) {
      // Return default structure if API fails
      final timestamp = DateTime.now().millisecondsSinceEpoch % 10000;
      return {
        'success': true,
        'invite_code': 'DEMO$timestamp',
        'referral_link':
            'https://app.culturaltranslate.com/register?ref=DEMO$timestamp',
        'app_link': 'culturaltranslate://register?ref=DEMO$timestamp',
        'reward_minutes_per_invite': 30,
        'invites': [],
      };
    }
    return {'success': false};
  }

  Future<Map<String, dynamic>> createInvite(
      {String? email, String? phone, String? name}) async {
    final response = await _dioMobile.post('/invites', data: {
      if (email != null && email.trim().isNotEmpty) 'email': email.trim(),
      if (phone != null && phone.trim().isNotEmpty) 'phone': phone.trim(),
      if (name != null && name.trim().isNotEmpty) 'name': name.trim(),
    });
    final root = response.data;
    if (root is Map<String, dynamic>) {
      return root;
    }
    return {'success': false};
  }

  Future<void> logout() async {
    try {
      if (_token != null) {
        await _dio.post('/logout');
      }
    } catch (e) {
      // Ignore
    } finally {
      _token = null;
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove(AppConfig.keyToken);
      await prefs.remove(AppConfig.keyUserData);
    }
  }

  Future<User?> getCurrentUser() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final userData = prefs.getString(AppConfig.keyUserData);

      if (userData != null) {
        return User.fromJson(jsonDecode(userData));
      }

      final response = await _dio.get('/me');
      if (response.statusCode == 200) {
        final root = response.data;
        final data = (root is Map<String, dynamic>) ? root['data'] : null;
        if (data is Map<String, dynamic>) {
          final user = User.fromJson(data);
          await prefs.setString(AppConfig.keyUserData, jsonEncode(data));
          return user;
        }
        return null;
      }
    } catch (e) {
      // Ignore
    }
    return null;
  }

  Future<Map<String, dynamic>> getDashboardStats() async {
    try {
      final response = await _dio.get('/stats');
      if (response.statusCode == 200) {
        return {'success': true, 'data': response.data};
      }
      return {'success': false, 'message': 'Failed to load dashboard stats'};
    } on DioException catch (e) {
      return {
        'success': false,
        'message': e.response?.data is Map
            ? (e.response?.data['message']?.toString() ?? e.message)
            : (e.message ?? 'Failed to load dashboard stats'),
      };
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  Future<List<User>> getUsers() async {
    try {
      final response = await _dio.get('/users');
      final root = response.data;
      final data = (root is Map<String, dynamic>) ? root['data'] : null;
      if (data is List) {
        return data
            .whereType<Map>()
            .map((e) => User.fromJson(Map<String, dynamic>.from(e)))
            .toList();
      }
    } catch (e) {
      // Return empty list if API fails (user can try refresh)
    }
    return [];
  }

  // -------------------------
  // Mobile settings (api/mobile)
  // -------------------------
  Future<Map<String, dynamic>> getMobileSettings() async {
    final response = await _dioMobile.get('/settings');
    final root = response.data;
    if (root is Map<String, dynamic> && root['success'] == true) {
      final settings = root['settings'];
      if (settings is Map) {
        return Map<String, dynamic>.from(settings);
      }
    }
    return {};
  }

  Future<Map<String, dynamic>> updateMobileSettings(
      Map<String, dynamic> patch) async {
    final response = await _dioMobile.post('/settings', data: patch);
    final root = response.data;
    if (root is Map<String, dynamic> && root['success'] == true) {
      final settings = root['settings'];
      if (settings is Map) {
        return Map<String, dynamic>.from(settings);
      }
    }
    throw Exception('Failed to update settings');
  }

  Future<List<Map<String, dynamic>>> getMobileLanguages() async {
    try {
      final response = await _dioMobile.get('/settings/languages');
      final root = response.data;
      final list = (root is Map<String, dynamic>) ? root['languages'] : null;
      if (list is List && list.isNotEmpty) {
        return list
            .whereType<Map>()
            .map((e) => Map<String, dynamic>.from(e))
            .toList();
      }
    } catch (e) {
      // Fallback to default languages if API fails
    }
    return _getDefaultLanguages();
  }

  List<Map<String, dynamic>> _getDefaultLanguages() {
    return [
      {'code': 'ar', 'name': 'العربية', 'native_name': 'العربية'},
      {'code': 'en', 'name': 'English', 'native_name': 'English'},
      {'code': 'fr', 'name': 'French', 'native_name': 'Français'},
      {'code': 'de', 'name': 'German', 'native_name': 'Deutsch'},
      {'code': 'es', 'name': 'Spanish', 'native_name': 'Español'},
      {'code': 'it', 'name': 'Italian', 'native_name': 'Italiano'},
      {'code': 'pt', 'name': 'Portuguese', 'native_name': 'Português'},
      {'code': 'ru', 'name': 'Russian', 'native_name': 'Русский'},
      {'code': 'zh', 'name': 'Chinese', 'native_name': '中文'},
      {'code': 'ja', 'name': 'Japanese', 'native_name': '日本語'},
      {'code': 'ko', 'name': 'Korean', 'native_name': '한국어'},
      {'code': 'tr', 'name': 'Turkish', 'native_name': 'Türkçe'},
      {'code': 'hi', 'name': 'Hindi', 'native_name': 'हिन्दी'},
      {'code': 'ur', 'name': 'Urdu', 'native_name': 'اردو'},
      {'code': 'fa', 'name': 'Persian', 'native_name': 'فارسی'},
    ];
  }

  // -------------------------
  // Mobile realtime voice (api/mobile/realtime)
  // -------------------------
  Future<String> createRealtimeSession({
    required String sourceLanguage,
    required String targetLanguage,
    String type = 'call',
    String? title,
    bool biDirectional = true,
  }) async {
    final response = await _dioMobile.post(
      '/realtime/sessions',
      data: {
        'type': type,
        'title': title,
        'source_language': sourceLanguage,
        'target_language': targetLanguage,
        'bi_directional': biDirectional,
        'record_transcript': true,
      },
    );

    final root = response.data;
    if (root is Map<String, dynamic> && root['ok'] == true) {
      final session = root['session'];
      if (session is Map && session['public_id'] != null) {
        return session['public_id'].toString();
      }
    }
    throw Exception('Failed to create realtime session');
  }

  Future<void> joinRealtimeSession({
    required String publicId,
    required String displayName,
    String role = 'speaker',
    String? sendLanguage,
    String? receiveLanguage,
  }) async {
    await _dioMobile.post(
      '/realtime/sessions/$publicId/participants/join',
      data: {
        'display_name': displayName,
        'role': role,
        'send_language': sendLanguage,
        'receive_language': receiveLanguage,
      },
    );
  }

  Future<Map<String, dynamic>> sendRealtimeAudioChunk({
    required String publicId,
    required String filePath,
    required int durationMs,
    String direction = 'mobile',
  }) async {
    final fileName = filePath.split(RegExp(r'[/\\]')).last;
    final form = FormData.fromMap({
      'audio': await MultipartFile.fromFile(filePath, filename: fileName),
      'duration_ms': durationMs,
      'direction': direction,
    });

    try {
      final response = await _dioMobile.post(
        '/realtime/sessions/$publicId/audio',
        data: form,
        options: Options(contentType: 'multipart/form-data'),
      );

      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
      return {'ok': false};
    } on DioException catch (e) {
      final data = e.response?.data;
      if (data is Map<String, dynamic>) {
        return data;
      }
      return {
        'ok': false,
        'message': e.message ?? 'Request failed',
        'status_code': e.response?.statusCode,
      };
    }
  }

  Future<List<Map<String, dynamic>>> pollRealtimeTurns({
    required String publicId,
  }) async {
    final response = await _dioMobile.get('/realtime/sessions/$publicId/poll');
    final root = response.data;
    if (root is Map<String, dynamic> &&
        root['ok'] == true &&
        root['turns'] is List) {
      return (root['turns'] as List)
          .whereType<Map>()
          .map((e) => Map<String, dynamic>.from(e))
          .toList();
    }
    return [];
  }

  // -------------------------
  // Mobile wallet (minutes)
  // -------------------------
  Future<Map<String, dynamic>> getWalletBalance() async {
    try {
      final response = await _dioMobile.get('/wallet/balance');
      final root = response.data;
      if (root is Map<String, dynamic> && root['success'] == true) {
        final data = root['data'];
        if (data is Map) {
          return Map<String, dynamic>.from(data);
        }
      }
    } catch (e) {
      // Return zero balance if API fails
      return {'balance_minutes': 0, 'balance_seconds': 0};
    }
    return {'balance_minutes': 0, 'balance_seconds': 0};
  }

  Future<List<Map<String, dynamic>>> getMinutePackages() async {
    try {
      final response = await _dioMobile.get('/wallet/packages');
      final root = response.data;
      if (root is List && root.isNotEmpty) {
        return root
            .whereType<Map>()
            .map((e) => Map<String, dynamic>.from(e))
            .toList();
      }
    } catch (e) {
      // Fallback to default packages if API fails
    }
    return _getDefaultPackages();
  }

  List<Map<String, dynamic>> _getDefaultPackages() {
    // Match server-side packages from config/livecall.php
    return [
      {'id': 'm30', 'minutes': 30, 'price_eur': 19.00, 'price_usd': 21.00},
      {'id': 'm120', 'minutes': 120, 'price_eur': 69.00, 'price_usd': 76.00},
      {'id': 'm300', 'minutes': 300, 'price_eur': 159.00, 'price_usd': 175.00},
      {
        'id': 'm1000',
        'minutes': 1000,
        'price_eur': 449.00,
        'price_usd': 495.00
      },
    ];
  }

  Future<String> createMinutesCheckout({required String packageId}) async {
    try {
      final response = await _dioMobile.post('/wallet/checkout', data: {
        'package_id': packageId,
      });
      final root = response.data;
      if (root is Map<String, dynamic> && root['checkout_url'] != null) {
        return root['checkout_url'].toString();
      }
    } catch (e) {
      // If checkout fails, throw with a user-friendly message
      throw Exception(
          'نظام الدفع غير متاح حالياً. الرجاء المحاولة لاحقاً أو التواصل مع الدعم.');
    }
    throw Exception('فشل إنشاء رابط الدفع');
  }

  // Add test minutes (for development/testing)
  Future<Map<String, dynamic>> addTestMinutes(int minutes) async {
    try {
      final response = await _dioMobile.post('/wallet/topup', data: {
        'minutes': minutes,
      });
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
      return {'success': false, 'message': 'Invalid response'};
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  Future<Map<String, dynamic>> updateProfile(
      Map<String, dynamic> updates) async {
    try {
      final response = await _dioMobile.put('/profile', data: updates);
      final root = response.data;
      if (root is Map<String, dynamic>) {
        // Update cached user data
        if (root['success'] == true && root['user'] != null) {
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString(
              AppConfig.keyUserData, jsonEncode(root['user']));
        }
        return root;
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'فشل تحديث الملف الشخصي: ${e.toString()}'
      };
    }
    return {'success': false, 'message': 'فشل تحديث الملف الشخصي'};
  }

  Future<Map<String, dynamic>> getCallHistory() async {
    try {
      final response = await _dioMobile.get('/calls/history');
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      // Return empty history on error
      return {
        'success': true,
        'calls': [],
      };
    }
    return {
      'success': true,
      'calls': [],
    };
  }

  // -------------------------
  // Support Chat API
  // -------------------------

  /// Check support availability
  Future<Map<String, dynamic>> getSupportAvailability() async {
    try {
      final response = await _dioMobile.get('/support/availability');
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {
        'success': false,
        'data': {
          'agents_available': false,
          'available_count': 0,
        }
      };
    }
    return {'success': false};
  }

  /// Start a support chat session
  Future<Map<String, dynamic>> startSupportChat({
    required String message,
    String department = 'general',
  }) async {
    try {
      final response = await _dioMobile.post('/support/chat/start', data: {
        'initial_message': message,
        'department': department,
      });
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
    return {'success': false};
  }

  /// Get support session details
  Future<Map<String, dynamic>> getSupportSession(
      {required String sessionId}) async {
    try {
      final response = await _dioMobile.get('/support/chat/$sessionId');
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
    return {'success': false};
  }

  /// Get support messages
  Future<Map<String, dynamic>> getSupportMessages({
    required String sessionId,
    int afterId = 0,
  }) async {
    try {
      final response = await _dioMobile.get(
        '/support/chat/$sessionId/messages',
        queryParameters: afterId > 0 ? {'after': afterId} : null,
      );
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
    return {'success': false};
  }

  /// Send support message
  Future<Map<String, dynamic>> sendSupportMessage({
    required String sessionId,
    required String message,
  }) async {
    try {
      final response =
          await _dioMobile.post('/support/chat/$sessionId/messages', data: {
        'message': message,
      });
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
    return {'success': false};
  }

  /// End support chat
  Future<Map<String, dynamic>> endSupportChat(
      {required String sessionId}) async {
    try {
      final response = await _dioMobile.post('/support/chat/$sessionId/end');
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
    return {'success': false};
  }

  /// Rate support chat
  Future<Map<String, dynamic>> rateSupportChat({
    required String sessionId,
    required int rating,
    String? feedback,
  }) async {
    try {
      final response =
          await _dioMobile.post('/support/chat/$sessionId/rate', data: {
        'rating': rating,
        if (feedback != null) 'feedback': feedback,
      });
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
    return {'success': false};
  }

  /// Get support chat history
  Future<Map<String, dynamic>> getSupportChatHistory() async {
    try {
      final response = await _dioMobile.get('/support/history');
      final root = response.data;
      if (root is Map<String, dynamic>) {
        return root;
      }
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
    return {'success': false};
  }

  bool get isAuthenticated => _token != null;
}
