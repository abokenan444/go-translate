import React, { useEffect, useState, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  ActivityIndicator,
  RefreshControl,
  TextInput,
  Modal,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import { http } from '../api/http';
import { useLanguage } from '../context/LanguageContext';

interface CallRecord {
  id: number;
  session_public_id: string;
  direction: 'outgoing' | 'incoming';
  status: 'completed' | 'missed' | 'declined' | 'ongoing';
  contact: { id: number; name: string; avatar_url: string | null } | null;
  duration: string;
  duration_seconds: number;
  minutes_used: number;
  languages: {
    caller_send: string;
    caller_receive: string;
    receiver_send: string;
    receiver_receive: string;
  };
  started_at: string;
}

export default function CallsScreen() {
  const navigation = useNavigation<any>();
  const { t } = useLanguage();
  const [calls, setCalls] = useState<CallRecord[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [joinModalVisible, setJoinModalVisible] = useState(false);
  const [joinCode, setJoinCode] = useState('');

  const fetchCalls = useCallback(async () => {
    try {
      const res = await http.get('/mobile/call-history');
      setCalls(res.data.calls ?? []);
    } catch (err) {
      console.error('Call history fetch error:', err);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useEffect(() => {
    fetchCalls();
  }, [fetchCalls]);

  const onRefresh = () => {
    setRefreshing(true);
    fetchCalls();
  };

  const handleJoinCall = () => {
    if (!joinCode.trim()) return;
    setJoinModalVisible(false);
    navigation.navigate('CallSession', { publicId: joinCode.trim() });
    setJoinCode('');
  };

  const formatTime = (iso: string) => {
    const d = new Date(iso);
    const now = new Date();
    const diffDays = Math.floor((now.getTime() - d.getTime()) / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
      return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } else if (diffDays === 1) {
      return t('yesterday');
    } else if (diffDays < 7) {
      return d.toLocaleDateString([], { weekday: 'short' });
    } else {
      return d.toLocaleDateString([], { month: 'short', day: 'numeric' });
    }
  };

  const getStatusIcon = (call: CallRecord) => {
    if (call.status === 'missed') return { name: 'call', color: '#EF4444' };
    if (call.status === 'declined') return { name: 'close-circle', color: '#F59E0B' };
    if (call.direction === 'outgoing') return { name: 'arrow-up-circle', color: '#10B981' };
    return { name: 'arrow-down-circle', color: '#6366F1' };
  };

  const getInitials = (name: string) => {
    const parts = name.trim().split(' ');
    return parts.length > 1
      ? `${parts[0][0]}${parts[parts.length - 1][0]}`.toUpperCase()
      : name.slice(0, 2).toUpperCase();
  };

  const renderCall = ({ item }: { item: CallRecord }) => {
    const icon = getStatusIcon(item);
    return (
      <TouchableOpacity
        style={styles.callRow}
        onPress={() => {
          // Could navigate to call details or redial
        }}
      >
        <View style={styles.avatarContainer}>
          <View style={styles.avatar}>
            <Text style={styles.avatarText}>
              {item.contact ? getInitials(item.contact.name) : '??'}
            </Text>
          </View>
          <View style={[styles.statusIndicator, { backgroundColor: icon.color }]}>
            <Ionicons name={icon.name as any} size={10} color="#fff" />
          </View>
        </View>
        <View style={styles.callInfo}>
          <Text style={styles.callName}>{item.contact?.name || 'Unknown'}</Text>
          <View style={styles.callMeta}>
            <Text style={[styles.callStatus, { color: icon.color }]}>
              {item.status === 'missed' ? t('missed') : 
               item.status === 'declined' ? t('declined') :
               item.direction === 'outgoing' ? t('outgoing') : t('incoming')}
            </Text>
            <Text style={styles.callDuration}>
              {item.status === 'completed' ? ` · ${item.duration}` : ''}
            </Text>
          </View>
          <View style={styles.languageInfo}>
            <Text style={styles.languageText}>
              {item.languages.caller_send.toUpperCase()} → {item.languages.caller_receive.toUpperCase()}
            </Text>
          </View>
        </View>
        <View style={styles.callRight}>
          <Text style={styles.callTime}>{formatTime(item.started_at)}</Text>
          {item.minutes_used > 0 && (
            <Text style={styles.minutesUsed}>{item.minutes_used.toFixed(1)} min</Text>
          )}
        </View>
      </TouchableOpacity>
    );
  };

  if (loading) {
    return (
      <SafeAreaView style={styles.container}>
        <ActivityIndicator size="large" color="#6366F1" />
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container} edges={['top']}>
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.title}>{t('calls')}</Text>
        <TouchableOpacity style={styles.joinBtn} onPress={() => setJoinModalVisible(true)}>
          <Ionicons name="enter-outline" size={22} color="#6366F1" />
        </TouchableOpacity>
      </View>

      {/* New Call Button */}
      <TouchableOpacity 
        style={styles.newCallBtn}
        onPress={() => navigation.navigate('Contacts')}
      >
        <View style={styles.newCallIcon}>
          <Ionicons name="call" size={24} color="#fff" />
        </View>
        <View style={styles.newCallText}>
          <Text style={styles.newCallTitle}>{t('startNewCall')}</Text>
          <Text style={styles.newCallSubtitle}>{t('selectContactToCall')}</Text>
        </View>
        <Ionicons name="chevron-forward" size={24} color="#9CA3AF" />
      </TouchableOpacity>

      {/* Call History */}
      <View style={styles.historyHeader}>
        <Text style={styles.historyTitle}>{t('recentCalls')}</Text>
      </View>

      <FlatList
        data={calls}
        keyExtractor={(item) => item.id.toString()}
        renderItem={renderCall}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
        ListEmptyComponent={
          <View style={styles.emptyState}>
            <Ionicons name="call-outline" size={48} color="#9CA3AF" />
            <Text style={styles.emptyText}>{t('noCalls')}</Text>
            <Text style={styles.emptySubtext}>{t('noCallsSubtext')}</Text>
          </View>
        }
        contentContainerStyle={calls.length === 0 ? styles.emptyContainer : styles.listContent}
      />

      {/* Join Call Modal */}
      <Modal visible={joinModalVisible} transparent animationType="slide">
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>{t('joinCallTitle')}</Text>
            <Text style={styles.modalSubtitle}>{t('enterCallCode')}</Text>
            <TextInput
              style={styles.input}
              placeholder={t('enterCallCode')}
              placeholderTextColor="#9CA3AF"
              value={joinCode}
              onChangeText={setJoinCode}
              autoCapitalize="none"
              autoCorrect={false}
            />
            <View style={styles.modalButtons}>
              <TouchableOpacity style={styles.cancelBtn} onPress={() => setJoinModalVisible(false)}>
                <Text style={styles.cancelBtnText}>{t('cancel')}</Text>
              </TouchableOpacity>
              <TouchableOpacity style={styles.joinCallBtn} onPress={handleJoinCall}>
                <Text style={styles.joinCallBtnText}>{t('join')}</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F3F4F6' },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 16, paddingVertical: 12 },
  title: { fontSize: 28, fontWeight: '700', color: '#111827' },
  joinBtn: { backgroundColor: '#EEF2FF', borderRadius: 10, padding: 10 },
  newCallBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    marginHorizontal: 16,
    marginBottom: 16,
    borderRadius: 16,
    padding: 16,
  },
  newCallIcon: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: '#6366F1',
    alignItems: 'center',
    justifyContent: 'center',
  },
  newCallText: { flex: 1, marginLeft: 12 },
  newCallTitle: { fontSize: 16, fontWeight: '600', color: '#111827' },
  newCallSubtitle: { fontSize: 13, color: '#6B7280', marginTop: 2 },
  historyHeader: { paddingHorizontal: 16, paddingVertical: 8 },
  historyTitle: { fontSize: 14, fontWeight: '600', color: '#6B7280', textTransform: 'uppercase' },
  listContent: { paddingHorizontal: 16, paddingBottom: 20 },
  callRow: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
    marginBottom: 8,
  },
  avatarContainer: { position: 'relative' },
  avatar: { width: 48, height: 48, borderRadius: 24, backgroundColor: '#6366F1', alignItems: 'center', justifyContent: 'center' },
  avatarText: { color: '#fff', fontSize: 16, fontWeight: '600' },
  statusIndicator: {
    position: 'absolute',
    bottom: 0,
    right: 0,
    width: 18,
    height: 18,
    borderRadius: 9,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 2,
    borderColor: '#fff',
  },
  callInfo: { flex: 1, marginLeft: 12 },
  callName: { fontSize: 16, fontWeight: '600', color: '#111827' },
  callMeta: { flexDirection: 'row', marginTop: 2 },
  callStatus: { fontSize: 13 },
  callDuration: { fontSize: 13, color: '#6B7280' },
  languageInfo: { marginTop: 4 },
  languageText: { fontSize: 11, color: '#9CA3AF', fontWeight: '500' },
  callRight: { alignItems: 'flex-end' },
  callTime: { fontSize: 13, color: '#6B7280' },
  minutesUsed: { fontSize: 11, color: '#6366F1', fontWeight: '500', marginTop: 4 },
  emptyState: { alignItems: 'center', paddingVertical: 60 },
  emptyText: { fontSize: 16, color: '#6B7280', marginTop: 12 },
  emptySubtext: { fontSize: 14, color: '#9CA3AF', marginTop: 4 },
  emptyContainer: { flexGrow: 1, justifyContent: 'center' },
  modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.5)', justifyContent: 'flex-end' },
  modalContent: { backgroundColor: '#fff', borderTopLeftRadius: 24, borderTopRightRadius: 24, padding: 24 },
  modalTitle: { fontSize: 20, fontWeight: '700', color: '#111827', textAlign: 'center' },
  modalSubtitle: { fontSize: 14, color: '#6B7280', textAlign: 'center', marginTop: 4, marginBottom: 20 },
  input: {
    backgroundColor: '#F3F4F6',
    borderRadius: 12,
    paddingHorizontal: 16,
    paddingVertical: 14,
    fontSize: 18,
    textAlign: 'center',
    fontWeight: '600',
    color: '#111827',
  },
  modalButtons: { flexDirection: 'row', gap: 12, marginTop: 24 },
  cancelBtn: { flex: 1, paddingVertical: 14, borderRadius: 12, backgroundColor: '#F3F4F6', alignItems: 'center' },
  cancelBtnText: { color: '#6B7280', fontWeight: '600' },
  joinCallBtn: { flex: 2, paddingVertical: 14, borderRadius: 12, backgroundColor: '#6366F1', alignItems: 'center' },
  joinCallBtnText: { color: '#fff', fontWeight: '600' },
});
