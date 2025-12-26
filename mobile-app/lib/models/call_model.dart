enum CallStatus {
  idle,
  ringing,
  connecting,
  connected,
  disconnected,
  failed,
}

enum CallType {
  audio,
  video,
}

class CallModel {
  final String callId;
  final String? realtimeSessionId;
  final String fromUserId;
  final String toUserId;
  final String fromUserName;
  final String toUserName;
  final CallType type;
  final CallStatus status;
  final DateTime startTime;
  final DateTime? endTime;
  final int? duration;

  CallModel({
    required this.callId,
    this.realtimeSessionId,
    required this.fromUserId,
    required this.toUserId,
    required this.fromUserName,
    required this.toUserName,
    required this.type,
    required this.status,
    required this.startTime,
    this.endTime,
    this.duration,
  });

  factory CallModel.fromJson(Map<String, dynamic> json) {
    final rawCallId = json['call_id'] ?? json['callId'] ?? json['id'];
    final rawRealtimeSessionId = json['realtime_session_id'] ??
        json['realtimeSessionId'] ??
        json['session_id'];
    final rawFromUserId =
        json['from_user_id'] ?? json['fromUserId'] ?? json['from'];
    final rawToUserId = json['to_user_id'] ?? json['toUserId'] ?? json['to'];
    final rawFromUserName = json['from_user_name'] ??
        json['fromUserName'] ??
        json['from_name'] ??
        json['fromName'];
    final rawToUserName = json['to_user_name'] ??
        json['toUserName'] ??
        json['to_name'] ??
        json['toName'];
    final rawType = json['type'];
    final rawStatus = json['status'];
    final rawStartTime = json['start_time'] ?? json['startTime'];

    DateTime startTime;
    if (rawStartTime is String) {
      startTime = DateTime.tryParse(rawStartTime) ?? DateTime.now();
    } else {
      startTime = DateTime.now();
    }

    return CallModel(
      callId: (rawCallId ?? DateTime.now().millisecondsSinceEpoch).toString(),
      realtimeSessionId: rawRealtimeSessionId?.toString(),
      fromUserId: (rawFromUserId ?? '').toString(),
      toUserId: (rawToUserId ?? '').toString(),
      fromUserName: (rawFromUserName ?? 'Unknown').toString(),
      toUserName: (rawToUserName ?? '').toString(),
      type: rawType == 'video' ? CallType.video : CallType.audio,
      status: _parseStatus((rawStatus ?? 'ringing').toString()),
      startTime: startTime,
      endTime: json['end_time'] != null
          ? DateTime.parse(json['end_time'] as String)
          : null,
      duration: json['duration'] as int?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'call_id': callId,
      'realtime_session_id': realtimeSessionId,
      'from_user_id': fromUserId,
      'to_user_id': toUserId,
      'from_user_name': fromUserName,
      'to_user_name': toUserName,
      'type': type == CallType.video ? 'video' : 'audio',
      'status': status.name,
      'start_time': startTime.toIso8601String(),
      'end_time': endTime?.toIso8601String(),
      'duration': duration,
    };
  }

  static CallStatus _parseStatus(String status) {
    switch (status.toLowerCase()) {
      case 'ringing':
        return CallStatus.ringing;
      case 'connecting':
        return CallStatus.connecting;
      case 'connected':
        return CallStatus.connected;
      case 'disconnected':
        return CallStatus.disconnected;
      case 'failed':
        return CallStatus.failed;
      default:
        return CallStatus.idle;
    }
  }
}
