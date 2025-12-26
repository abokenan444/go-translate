import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import '../config/app_theme.dart';
import '../providers/auth_provider.dart';
import '../providers/call_provider.dart';
import '../services/api_service.dart';
import '../services/webrtc_service.dart';
import '../models/call_model.dart';
import '../models/user.dart';
import '../widgets/language_selector.dart';
import '../l10n/app_localizations.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedIndex = 0;
  final _searchController = TextEditingController();

  bool _isLoadingUsers = false;
  String? _usersError;
  List<User> _users = const [];

  @override
  void initState() {
    super.initState();
    _loadUsers();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _loadUsers() async {
    setState(() {
      _isLoadingUsers = true;
      _usersError = null;
    });

    try {
      final users = await ApiService().getUsers();
      if (!mounted) return;

      // If no users from API, add demo users for testing
      if (users.isEmpty) {
        setState(() {
          _users = _getDemoUsers();
        });
      } else {
        setState(() {
          _users = users;
        });
      }
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _usersError = e.toString();
        _users = _getDemoUsers(); // Use demo users on error
      });
    } finally {
      if (mounted) {
        setState(() {
          _isLoadingUsers = false;
        });
      }
    }
  }

  List<User> _getDemoUsers() {
    return [
      User(
        id: 1001,
        name: 'أحمد محمد',
        email: 'ahmed@example.com',
        accountType: 'individual',
      ),
      User(
        id: 1002,
        name: 'Sarah Johnson',
        email: 'sarah@example.com',
        accountType: 'individual',
      ),
      User(
        id: 1003,
        name: 'محمد علي',
        email: 'mohamed@example.com',
        accountType: 'individual',
      ),
      User(
        id: 1004,
        name: 'Marie Dubois',
        email: 'marie@example.com',
        accountType: 'individual',
      ),
    ];
  }

  Future<void> _startCall(User contact, CallType type) async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final me = authProvider.currentUser;
    if (me == null) return;

    final callProvider = Provider.of<CallProvider>(context, listen: false);
    final callId = DateTime.now().millisecondsSinceEpoch.toString();

    final realtimeSessionId = await WebRTCService().makeCall(
      contact.id.toString(),
      type,
      callId: callId,
    );

    callProvider.setCurrentCall(
      CallModel(
        callId: callId,
        realtimeSessionId: realtimeSessionId,
        fromUserId: me.id.toString(),
        toUserId: contact.id.toString(),
        fromUserName: me.name,
        toUserName: contact.name,
        type: type,
        status: CallStatus.ringing,
        startTime: DateTime.now(),
      ),
    );
    final local = WebRTCService().localStream;
    if (local != null) {
      callProvider.setLocalStream(local);
    }

    if (!mounted) return;
    Navigator.pushNamed(
      context,
      '/call',
      arguments: {
        'callType': type == CallType.video ? 'video' : 'audio',
        'contactName': contact.name,
      },
    );
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.currentUser;

    return Scaffold(
      appBar: AppBar(
        title: const Text('CulturalTranslate'),
        actions: [
          const LanguageSelector(),
          const SizedBox(width: 8),
          IconButton(
            icon: const Icon(Icons.notifications_outlined),
            onPressed: () {
              Navigator.pushNamed(context, '/notifications');
            },
          ),
          IconButton(
            icon: const Icon(Icons.settings_outlined),
            onPressed: () {
              Navigator.pushNamed(context, '/settings');
            },
          ),
        ],
      ),
      drawer: _buildDrawer(context, user),
      body: IndexedStack(
        index: _selectedIndex,
        children: [
          _buildContactsTab(),
          _buildCallHistoryTab(),
          _buildProfileTab(user),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: _onItemTapped,
        items: [
          BottomNavigationBarItem(
            icon: const Icon(Icons.people_outline),
            activeIcon: const Icon(Icons.people),
            label: AppLocalizations.of(context).translate('contacts'),
          ),
          BottomNavigationBarItem(
            icon: const Icon(Icons.history),
            activeIcon: const Icon(Icons.history),
            label: AppLocalizations.of(context).translate('call_history'),
          ),
          BottomNavigationBarItem(
            icon: const Icon(Icons.person_outline),
            activeIcon: const Icon(Icons.person),
            label: AppLocalizations.of(context).translate('profile'),
          ),
        ],
      ),
      floatingActionButton: _selectedIndex == 0
          ? FloatingActionButton.extended(
              onPressed: () {
                _showNewCallDialog(context);
              },
              backgroundColor: AppTheme.primaryColor,
              icon: const Icon(Icons.add_call),
              label: Text(AppLocalizations.of(context).translate('new_call')),
            )
          : null,
    );
  }

  Widget _buildDrawer(BuildContext context, User? user) {
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          UserAccountsDrawerHeader(
            decoration: const BoxDecoration(
              gradient: AppTheme.primaryGradient,
            ),
            accountName: Text(
              user?.name ?? 'User',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            accountEmail: Text(user?.email ?? ''),
            currentAccountPicture: CircleAvatar(
              backgroundColor: Colors.white,
              child: user?.avatar != null
                  ? ClipOval(
                      child: Image.network(
                        user!.avatar!,
                        fit: BoxFit.cover,
                        width: 90,
                        height: 90,
                      ),
                    )
                  : Text(
                      user?.name.substring(0, 1).toUpperCase() ?? 'U',
                      style: const TextStyle(
                        fontSize: 40,
                        color: AppTheme.primaryColor,
                      ),
                    ),
            ),
          ),
          ListTile(
            leading: const Icon(Icons.home),
            title: Text(AppLocalizations.of(context).translate('home')),
            onTap: () {
              Navigator.pop(context);
            },
          ),
          ListTile(
            leading: const Icon(Icons.translate),
            title: Text(
                AppLocalizations.of(context).translate('translation_services')),
            onTap: () {
              Navigator.pop(context);
              Navigator.pushNamed(context, '/dashboard');
            },
          ),
          ListTile(
            leading: const Icon(Icons.account_balance_wallet),
            title: Text(AppLocalizations.of(context).translate('wallet')),
            onTap: () {
              Navigator.pop(context);
              Navigator.pushNamed(context, '/settings');
              // Navigate to wallet tab in settings
            },
          ),
          ListTile(
            leading: const Icon(Icons.history),
            title: Text(AppLocalizations.of(context).translate('call_history')),
            onTap: () {
              Navigator.pop(context);
              Navigator.pushNamed(context, '/call-history');
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.settings),
            title: Text(AppLocalizations.of(context).translate('settings')),
            onTap: () {
              Navigator.pop(context);
              Navigator.pushNamed(context, '/settings');
            },
          ),
          ListTile(
            leading: const Icon(Icons.security),
            title: Text(
                AppLocalizations.of(context).translate('privacy_security')),
            onTap: () {
              Navigator.pop(context);
              Navigator.pushNamed(context, '/privacy-security');
            },
          ),
          ListTile(
            leading: const Icon(Icons.help_outline),
            title: Text(AppLocalizations.of(context).translate('help_support')),
            onTap: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                    content: Text(AppLocalizations.of(context)
                        .translate('support_email'))),
              );
            },
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.logout, color: AppTheme.errorColor),
            title: Text(
              AppLocalizations.of(context).translate('logout'),
              style: const TextStyle(color: AppTheme.errorColor),
            ),
            onTap: () async {
              final authProvider =
                  Provider.of<AuthProvider>(context, listen: false);
              await authProvider.logout();
              if (context.mounted) {
                Navigator.pushReplacementNamed(context, '/login');
              }
            },
          ),
        ],
      ),
    );
  }

  Widget _buildContactsTab() {
    final q = _searchController.text.trim().toLowerCase();
    final filtered = q.isEmpty
        ? _users
        : _users
            .where((u) =>
                u.name.toLowerCase().contains(q) ||
                u.email.toLowerCase().contains(q))
            .toList();

    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.all(16.0),
          child: TextField(
            controller: _searchController,
            decoration: InputDecoration(
              hintText:
                  AppLocalizations.of(context).translate('search_contact'),
              prefixIcon: const Icon(Icons.search),
              suffixIcon: _searchController.text.isNotEmpty
                  ? IconButton(
                      icon: const Icon(Icons.clear),
                      onPressed: () {
                        setState(() {
                          _searchController.clear();
                        });
                      },
                    )
                  : null,
            ),
            onChanged: (value) {
              setState(() {});
            },
          ),
        ),
        Expanded(
          child: _isLoadingUsers
              ? const Center(child: CircularProgressIndicator())
              : _usersError != null
                  ? Center(
                      child: Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Text('تعذر تحميل المستخدمين'),
                            const SizedBox(height: 8),
                            Text(
                              _usersError!,
                              textAlign: TextAlign.center,
                              style: const TextStyle(color: Colors.grey),
                            ),
                            const SizedBox(height: 12),
                            ElevatedButton(
                              onPressed: _loadUsers,
                              child: const Text('إعادة المحاولة'),
                            ),
                          ],
                        ),
                      ),
                    )
                  : ListView.builder(
                      itemCount: filtered.length,
                      itemBuilder: (context, index) {
                        final u = filtered[index];
                        return _buildContactCard(
                          user: u,
                          isOnline: index % 2 == 0,
                        );
                      },
                    ),
        ),
      ],
    );
  }

  Widget _buildContactCard({
    required User user,
    required bool isOnline,
  }) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: ListTile(
        leading: Stack(
          children: [
            CircleAvatar(
              backgroundColor: AppTheme.primaryColor,
              child: Text(
                user.name.substring(0, 1).toUpperCase(),
                style: const TextStyle(color: Colors.white),
              ),
            ),
            if (isOnline)
              Positioned(
                right: 0,
                bottom: 0,
                child: Container(
                  width: 12,
                  height: 12,
                  decoration: BoxDecoration(
                    color: AppTheme.successColor,
                    shape: BoxShape.circle,
                    border: Border.all(color: Colors.white, width: 2),
                  ),
                ),
              ),
          ],
        ),
        title: Text(user.name),
        subtitle: Text(user.email),
        trailing: IconButton(
          icon: const FaIcon(FontAwesomeIcons.phone, size: 20),
          color: AppTheme.primaryColor,
          onPressed: () {
            _startCall(user, CallType.audio);
          },
        ),
      ),
    );
  }

  Widget _buildCallHistoryTab() {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: 15, // TODO: Replace with actual call history
      itemBuilder: (context, index) {
        final isMissed = index % 5 == 0;
        final isIncoming = index % 2 == 0;
        return Card(
          child: ListTile(
            leading: CircleAvatar(
              backgroundColor: isMissed
                  ? AppTheme.errorColor
                  : isIncoming
                      ? AppTheme.successColor
                      : AppTheme.infoColor,
              child: Icon(
                isMissed
                    ? Icons.call_missed
                    : isIncoming
                        ? Icons.call_received
                        : Icons.call_made,
                color: Colors.white,
              ),
            ),
            title: Text('Contact ${index + 1}'),
            subtitle: Text(
              '${index + 1} دقيقة - ${DateTime.now().subtract(Duration(hours: index)).toString().substring(0, 16)}',
            ),
            trailing: IconButton(
              icon: const Icon(Icons.phone),
              color: AppTheme.primaryColor,
              onPressed: () {
                // TODO: Call back
              },
            ),
          ),
        );
      },
    );
  }

  Widget _buildProfileTab(User? user) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          const SizedBox(height: 20),
          CircleAvatar(
            radius: 60,
            backgroundColor: AppTheme.primaryColor,
            child: user?.avatar != null
                ? ClipOval(
                    child: Image.network(
                      user!.avatar!,
                      fit: BoxFit.cover,
                      width: 120,
                      height: 120,
                    ),
                  )
                : Text(
                    user?.name.substring(0, 1).toUpperCase() ?? 'U',
                    style: const TextStyle(
                      fontSize: 48,
                      color: Colors.white,
                    ),
                  ),
          ),
          const SizedBox(height: 16),
          Text(
            user?.name ?? 'User',
            style: const TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            user?.email ?? '',
            style: const TextStyle(
              fontSize: 16,
              color: Colors.grey,
            ),
          ),
          const SizedBox(height: 32),
          _buildProfileCard(
            icon: Icons.account_circle,
            title: 'نوع الحساب',
            value: user?.accountType ?? 'individual',
          ),
          _buildProfileCard(
            icon: Icons.verified_user,
            title: 'الحالة',
            value: user?.status ?? 'active',
          ),
          _buildProfileCard(
            icon: Icons.phone,
            title: 'رقم الهاتف',
            value: user?.phone ?? 'غير محدد',
          ),
          _buildProfileCard(
            icon: Icons.calendar_today,
            title: 'تاريخ التسجيل',
            value: user?.createdAt != null
                ? '${user!.createdAt!.day.toString().padLeft(2, '0')}/${user.createdAt!.month.toString().padLeft(2, '0')}/${user.createdAt!.year}'
                : 'غير محدد',
          ),
          const SizedBox(height: 24),
          SizedBox(
            width: double.infinity,
            height: 50,
            child: ElevatedButton.icon(
              onPressed: () {
                Navigator.pushNamed(context, '/edit-profile');
              },
              icon: const Icon(Icons.edit),
              label: const Text('تعديل الملف الشخصي'),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.primaryColor,
                foregroundColor: Colors.white,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildProfileCard({
    required IconData icon,
    required String title,
    required String value,
  }) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: Icon(icon, color: AppTheme.primaryColor),
        title: Text(title),
        subtitle: Text(value),
      ),
    );
  }

  void _showNewCallDialog(BuildContext context) {
    final loc = AppLocalizations.of(context);
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(loc.translate('new_call')),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            TextField(
              decoration: InputDecoration(
                labelText: loc.translate('enter_email_or_phone'),
                prefixIcon: const Icon(Icons.search),
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text(loc.translate('cancel')),
          ),
          ElevatedButton.icon(
            onPressed: () {
              Navigator.pop(context);
              // TODO: Start audio call
            },
            icon: const FaIcon(FontAwesomeIcons.phone, size: 16),
            label: Text(loc.translate('audio_call')),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.primaryColor,
            ),
          ),
        ],
      ),
    );
  }
}
