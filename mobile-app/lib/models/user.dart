class User {
  final int id;
  final String name;
  final String email;
  final String? avatar;
  final String? phone;
  final String role;
  final String accountType;
  final String status;
  final DateTime? createdAt;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.avatar,
    this.phone,
    this.role = 'user',
    required this.accountType,
    this.status = 'active',
    this.createdAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      avatar: json['avatar'] as String?,
      phone: json['phone'] as String?,
      role: json['role'] as String? ?? 'user',
      accountType: json['account_type'] as String? ?? 'customer',
      status: json['status'] as String? ?? 'active',
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'avatar': avatar,
      'phone': phone,
      'role': role,
      'account_type': accountType,
      'status': status,
      'created_at': createdAt?.toIso8601String(),
    };
  }
}
