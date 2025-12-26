import 'package:flutter/foundation.dart';
import '../models/user.dart';
import '../services/api_service.dart';
import '../services/webrtc_service.dart';

class AuthProvider with ChangeNotifier {
  User? _currentUser;
  bool _isLoading = false;
  String? _error;

  User? get currentUser => _currentUser;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isAuthenticated => _currentUser != null;

  Future<void> init() async {
    _isLoading = true;
    notifyListeners();

    _currentUser = await ApiService().getCurrentUser();

    final token = ApiService().token;
    if (token != null && token.isNotEmpty) {
      await WebRTCService().init(token);
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    final result = await ApiService().login(email, password);

    if (result['success']) {
      _currentUser = User.fromJson(result['data']['user']);

      final token = ApiService().token;
      if (token != null && token.isNotEmpty) {
        await WebRTCService().init(token);
      }

      _isLoading = false;
      notifyListeners();
      return true;
    } else {
      _error = result['message'];
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
    String accountType = 'individual',
    String? referralCode,
    String? inviteCode,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    final result = await ApiService().register(
      name: name,
      email: email,
      password: password,
      passwordConfirmation: passwordConfirmation,
      phone: phone,
      accountType: accountType,
      referralCode: referralCode,
      inviteCode: inviteCode,
    );

    if (result['success'] == true) {
      final data = result['data'];
      Map<String, dynamic>? userJson;

      if (data is Map<String, dynamic>) {
        if (data['user'] is Map<String, dynamic>) {
          userJson = data['user'] as Map<String, dynamic>;
        } else if (data['data'] is Map &&
            (data['data'] as Map)['user'] is Map) {
          userJson =
              Map<String, dynamic>.from((data['data'] as Map)['user'] as Map);
        }
      }

      if (userJson != null) {
        _currentUser = User.fromJson(userJson);
      } else {
        _currentUser = await ApiService().getCurrentUser();
      }

      final token = ApiService().token;
      if (token != null && token.isNotEmpty) {
        await WebRTCService().init(token);
      }

      _isLoading = false;
      notifyListeners();
      return true;
    }

    _error = result['message']?.toString();
    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<void> logout() async {
    await ApiService().logout();
    _currentUser = null;
    notifyListeners();
  }

  Future<void> refreshUser() async {
    _currentUser = await ApiService().getCurrentUser();
    notifyListeners();
  }

  // Alias for currentUser
  User? get user => _currentUser;
}
