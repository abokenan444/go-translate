class AppConfig {
  // API Configuration
  // Override at build/run time with:
  // `--dart-define=API_BASE_URL=http://127.0.0.1:8001`
  static const String _baseUrlRaw = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'https://culturaltranslate.com',
  );

  static String get baseUrl {
    final trimmed = _baseUrlRaw.trim();
    if (trimmed.endsWith('/')) {
      return trimmed.substring(0, trimmed.length - 1);
    }
    return trimmed;
  }

  static String get apiUrl => '$baseUrl/api/v1';
  static String get mobileApiUrl => '$baseUrl/api/mobile';

  // Socket base URL (socket_io_client expects http/https; it upgrades to ws/wss)
  static String get wsUrl => baseUrl;

  // WebRTC Configuration
  static const Map<String, dynamic> iceServers = {
    'iceServers': [
      {'urls': 'stun:stun.l.google.com:19302'},
      {'urls': 'stun:stun1.l.google.com:19302'},
    ]
  };

  // App Configuration
  static const String appName = 'CulturalTranslate';
  static const String appVersion = '1.0.0';

  // Timeouts
  static const Duration apiTimeout = Duration(seconds: 30);
  static const Duration callTimeout = Duration(seconds: 60);

  // Preferences Keys
  static const String keyToken = 'auth_token';
  static const String keyUserId = 'user_id';
  static const String keyUserData = 'user_data';
  static const String keyThemeMode = 'theme_mode';
  static const String keyLanguage = 'language';
}
