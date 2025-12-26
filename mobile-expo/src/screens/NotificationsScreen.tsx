import React, { useEffect, useState, useCallback, useRef } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  ActivityIndicator,
  RefreshControl,
  Alert,
  Vibration,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import { http } from '../api/http';
import { useLanguage } from '../context/LanguageContext';

interface Notification {
  id: number;
  type: string;
  title: string;
  body: string;
  data: any;
  read: boolean;
  created_at: string;
}

export default function NotificationsScreen() {
  const { t } = useLanguage();
  const navigation = useNavigation<any>();
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [respondingTo, setRespondingTo] = useState<number | null>(null);
  const pollingRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const lastNotificationCount = useRef(0);

  const fetchNotifications = useCallback(async (silent = false) => {
    try {
      const res = await http.get('/mobile/notifications');
      const newNotifications = res.data.notifications ?? [];
      setNotifications(newNotifications);
      
      // Check for new incoming call notifications and vibrate
      const unreadCalls = newNotifications.filter(
        (n: Notification) => n.type === 'incoming_call' && !n.read
      );
      if (unreadCalls.length > 0 && newNotifications.length > lastNotificationCount.current) {
        Vibration.vibrate([0, 500, 200, 500, 200, 500]); // Ring pattern
      }
      lastNotificationCount.current = newNotifications.length;
    } catch (err) {
      console.error('Fetch notifications error:', err);
    } finally {
      if (!silent) {
        setLoading(false);
        setRefreshing(false);
      }
    }
  }, []);

  // Poll for new notifications every 5 seconds when screen is focused
  useFocusEffect(
    useCallback(() => {
      fetchNotifications();
      pollingRef.current = setInterval(() => {
        fetchNotifications(true);
      }, 5000);
      
      return () => {
        if (pollingRef.current) {
          clearInterval(pollingRef.current);
        }
      };
    }, [fetchNotifications])
  );

  useEffect(() => {
    fetchNotifications();
  }, [fetchNotifications]);

  const handleRefresh = () => {
    setRefreshing(true);
    fetchNotifications();
  };

  const handleCostShareResponse = async (notification: Notification, accept: boolean) => {
    const callId = notification.data?.call_id;
    if (!callId) {
      Alert.alert('Error', 'Invalid notification data');
      return;
    }

    setRespondingTo(notification.id);
    try {
      await http.post(`/mobile/calls/${callId}/respond-cost-share`, { accept });
      
      if (accept) {
        Alert.alert('✅ تم القبول', 'تمت مشاركة تكلفة المكالمة بنجاح');
      } else {
        Alert.alert('تم الرفض', 'التكلفة الكاملة ستبقى على المتصل');
      }
      
      // Mark as read and refresh
      await markAsRead(notification);
      fetchNotifications();
    } catch (err: any) {
      Alert.alert('Error', err.response?.data?.message || 'Failed to respond');
    } finally {
      setRespondingTo(null);
    }
  };

  const handleContactRequestResponse = async (notification: Notification, accept: boolean) => {
    const userId = notification.data?.added_by_id;
    if (!userId) {
      Alert.alert('Error', 'Invalid notification data');
      return;
    }

    setRespondingTo(notification.id);
    try {
      const endpoint = accept ? '/mobile/contacts/accept-request' : '/mobile/contacts/reject-request';
      await http.post(endpoint, { 
        user_id: userId,
        notification_id: notification.id,
      });
      
      if (accept) {
        Alert.alert('✅ تم القبول', `تمت إضافة ${notification.data?.added_by_name || 'المستخدم'} كجهة اتصال`);
      } else {
        Alert.alert('تم الرفض', 'تم رفض طلب الإضافة');
      }
      
      fetchNotifications();
    } catch (err: any) {
      Alert.alert('Error', err.response?.data?.message || 'Failed to respond');
    } finally {
      setRespondingTo(null);
    }
  };

  const handleIncomingCallResponse = async (notification: Notification, accept: boolean) => {
    const sessionId = notification.data?.session_id;
    const callerName = notification.data?.caller_name;
    
    if (!sessionId) {
      Alert.alert('Error', 'Invalid call data');
      return;
    }

    await markAsRead(notification);

    if (accept) {
      // Navigate to call session to answer the call
      navigation.navigate('CallSession', {
        publicId: sessionId,
        contactName: callerName,
      });
    } else {
      // Reject the call - just mark as read
      Alert.alert('تم الرفض', 'تم رفض المكالمة');
    }
  };

  const markAsRead = async (notification: Notification) => {
    if (notification.read) return;
    
    try {
      await http.post(`/mobile/notifications/${notification.id}/read`);
      setNotifications((prev) =>
        prev.map((n) =>
          n.id === notification.id ? { ...n, read: true } : n
        )
      );
    } catch (err) {
      console.error('Mark as read error:', err);
    }
  };

  const markAllAsRead = async () => {
    try {
      await http.post('/mobile/notifications/mark-all-read');
      setNotifications((prev) => prev.map((n) => ({ ...n, read: true })));
    } catch (err) {
      console.error('Mark all as read error:', err);
    }
  };

  const getNotificationIcon = (type: string) => {
    switch (type) {
      case 'contact_added':
        return { name: 'person-add', color: '#6366F1' };
      case 'contact_accepted':
        return { name: 'person-done', color: '#10B981' };
      case 'incoming_call':
        return { name: 'call', color: '#10B981' };
      case 'call_missed':
        return { name: 'call', color: '#EF4444' };
      case 'invite':
        return { name: 'mail', color: '#10B981' };
      case 'cost_share_request':
        return { name: 'wallet', color: '#F59E0B' };
      case 'cost_share_accepted':
        return { name: 'checkmark-circle', color: '#10B981' };
      case 'cost_share_rejected':
        return { name: 'close-circle', color: '#EF4444' };
      default:
        return { name: 'notifications', color: '#6B7280' };
    }
  };

  const formatTime = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now.getTime() - date.getTime();
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return t('justNow');
    if (minutes < 60) return `${minutes}${t('min')}`;
    if (hours < 24) return `${hours}h`;
    if (days < 7) return `${days}d`;
    return date.toLocaleDateString();
  };

  const renderNotification = ({ item }: { item: Notification }) => {
    const icon = getNotificationIcon(item.type);
    const isCostShareRequest = item.type === 'cost_share_request' && !item.read;
    const isContactRequest = item.type === 'contact_added' && !item.read;
    const isIncomingCall = item.type === 'incoming_call' && !item.read;
    const hasActionButtons = isCostShareRequest || isContactRequest || isIncomingCall;
    const isResponding = respondingTo === item.id;
    
    return (
      <TouchableOpacity
        style={[styles.notificationItem, !item.read && styles.unread]}
        onPress={() => !hasActionButtons && markAsRead(item)}
        activeOpacity={0.7}
      >
        <View style={[styles.iconContainer, { backgroundColor: `${icon.color}15` }]}>
          <Ionicons name={icon.name as any} size={24} color={icon.color} />
        </View>
        <View style={styles.content}>
          <View style={styles.header}>
            <Text style={styles.title} numberOfLines={1}>
              {item.title}
            </Text>
            {!item.read && <View style={styles.unreadDot} />}
          </View>
          <Text style={styles.body} numberOfLines={hasActionButtons ? 3 : 2}>
            {item.body}
          </Text>
          
          {/* Incoming call action buttons */}
          {isIncomingCall && (
            <View style={styles.actionButtons}>
              <TouchableOpacity
                style={[styles.rejectBtn, { backgroundColor: '#FEE2E2' }]}
                onPress={() => handleIncomingCallResponse(item, false)}
              >
                <Ionicons name="call" size={16} color="#EF4444" />
                <Text style={styles.rejectBtnText}>{t('reject')}</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[styles.acceptBtn, { backgroundColor: '#10B981' }]}
                onPress={() => handleIncomingCallResponse(item, true)}
              >
                <Ionicons name="call" size={16} color="#fff" />
                <Text style={styles.acceptBtnText}>الرد</Text>
              </TouchableOpacity>
            </View>
          )}

          {/* Contact request action buttons */}
          {isContactRequest && (
            <View style={styles.actionButtons}>
              {isResponding ? (
                <ActivityIndicator size="small" color="#6366F1" />
              ) : (
                <>
                  <TouchableOpacity
                    style={styles.rejectBtn}
                    onPress={() => handleContactRequestResponse(item, false)}
                  >
                    <Ionicons name="close" size={16} color="#EF4444" />
                    <Text style={styles.rejectBtnText}>{t('reject')}</Text>
                  </TouchableOpacity>
                  <TouchableOpacity
                    style={styles.acceptBtn}
                    onPress={() => handleContactRequestResponse(item, true)}
                  >
                    <Ionicons name="checkmark" size={16} color="#fff" />
                    <Text style={styles.acceptBtnText}>{t('accept')}</Text>
                  </TouchableOpacity>
                </>
              )}
            </View>
          )}

          {/* Cost share action buttons */}
          {isCostShareRequest && (
            <View style={styles.actionButtons}>
              {isResponding ? (
                <ActivityIndicator size="small" color="#6366F1" />
              ) : (
                <>
                  <TouchableOpacity
                    style={styles.rejectBtn}
                    onPress={() => handleCostShareResponse(item, false)}
                  >
                    <Ionicons name="close" size={16} color="#EF4444" />
                    <Text style={styles.rejectBtnText}>{t('reject')}</Text>
                  </TouchableOpacity>
                  <TouchableOpacity
                    style={styles.acceptBtn}
                    onPress={() => handleCostShareResponse(item, true)}
                  >
                    <Ionicons name="checkmark" size={16} color="#fff" />
                    <Text style={styles.acceptBtnText}>{t('acceptShare')}</Text>
                  </TouchableOpacity>
                </>
              )}
            </View>
          )}
          
          <Text style={styles.time}>{formatTime(item.created_at)}</Text>
        </View>
      </TouchableOpacity>
    );
  };

  const unreadCount = notifications.filter((n) => !n.read).length;

  if (loading) {
    return (
      <SafeAreaView style={styles.container}>
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color="#6366F1" />
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container} edges={['top']}>
      {/* Header */}
      <View style={styles.headerContainer}>
        <Text style={styles.headerTitle}>{t('notifications')}</Text>
        {unreadCount > 0 && (
          <TouchableOpacity style={styles.markAllBtn} onPress={markAllAsRead}>
            <Text style={styles.markAllText}>{t('markAllRead')}</Text>
          </TouchableOpacity>
        )}
      </View>

      {notifications.length === 0 ? (
        <View style={styles.emptyContainer}>
          <Ionicons name="notifications-off-outline" size={64} color="#D1D5DB" />
          <Text style={styles.emptyTitle}>{t('noNotifications')}</Text>
          <Text style={styles.emptySubtitle}>
            {t('noNotificationsSubtext')}
          </Text>
        </View>
      ) : (
        <FlatList
          data={notifications}
          keyExtractor={(item) => item.id.toString()}
          renderItem={renderNotification}
          contentContainerStyle={styles.list}
          refreshControl={
            <RefreshControl
              refreshing={refreshing}
              onRefresh={handleRefresh}
              colors={['#6366F1']}
              tintColor="#6366F1"
            />
          }
          ItemSeparatorComponent={() => <View style={styles.separator} />}
        />
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F3F4F6',
  },
  loadingContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  headerContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#E5E7EB',
  },
  headerTitle: {
    fontSize: 28,
    fontWeight: '700',
    color: '#111827',
  },
  markAllBtn: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    backgroundColor: '#EEF2FF',
    borderRadius: 8,
  },
  markAllText: {
    color: '#6366F1',
    fontSize: 14,
    fontWeight: '600',
  },
  list: {
    padding: 16,
  },
  notificationItem: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
  },
  unread: {
    backgroundColor: '#EEF2FF',
  },
  iconContainer: {
    width: 48,
    height: 48,
    borderRadius: 12,
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  content: {
    flex: 1,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  title: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
    flex: 1,
  },
  unreadDot: {
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: '#6366F1',
    marginLeft: 8,
  },
  body: {
    fontSize: 14,
    color: '#6B7280',
    lineHeight: 20,
    marginBottom: 4,
  },
  time: {
    fontSize: 12,
    color: '#9CA3AF',
  },
  actionButtons: {
    flexDirection: 'row',
    gap: 8,
    marginTop: 12,
    marginBottom: 8,
  },
  acceptBtn: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#10B981',
    paddingVertical: 10,
    paddingHorizontal: 12,
    borderRadius: 8,
    gap: 4,
  },
  acceptBtnText: {
    color: '#fff',
    fontWeight: '600',
    fontSize: 14,
  },
  rejectBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#FEE2E2',
    paddingVertical: 10,
    paddingHorizontal: 16,
    borderRadius: 8,
    gap: 4,
  },
  rejectBtnText: {
    color: '#EF4444',
    fontWeight: '600',
    fontSize: 14,
  },
  separator: {
    height: 8,
  },
  emptyContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: 32,
  },
  emptyTitle: {
    fontSize: 20,
    fontWeight: '600',
    color: '#6B7280',
    marginTop: 16,
  },
  emptySubtitle: {
    fontSize: 14,
    color: '#9CA3AF',
    textAlign: 'center',
    marginTop: 8,
  },
});
