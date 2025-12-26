import React, { useEffect, useState, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  ActivityIndicator,
  RefreshControl,
  Alert,
  TextInput,
  Modal,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { http } from '../api/http';
import { useLanguage } from '../context/LanguageContext';

interface Transaction {
  id: number;
  type: string;
  minutes_amount: number;
  balance_after: number;
  description: string;
  is_credit: boolean;
  created_at: string;
}

// Packages with prices
const PACKAGES = [
  { minutes: 30, price: 4.99, popular: false },
  { minutes: 60, price: 8.99, popular: true },
  { minutes: 120, price: 14.99, popular: false },
  { minutes: 300, price: 29.99, popular: false },
];

export default function WalletScreen({ navigation }: any) {
  const { t } = useLanguage();
  const [balance, setBalance] = useState<number>(0);
  const [transactions, setTransactions] = useState<Transaction[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [topupModalVisible, setTopupModalVisible] = useState(false);
  const [topupAmount, setTopupAmount] = useState('30');
  const [topupLoading, setTopupLoading] = useState(false);
  // New states for packages and auto top-up
  const [packagesModalVisible, setPackagesModalVisible] = useState(false);
  const [autoTopupModalVisible, setAutoTopupModalVisible] = useState(false);
  const [inviteModalVisible, setInviteModalVisible] = useState(false);
  const [autoTopupEnabled, setAutoTopupEnabled] = useState(false);
  const [autoTopupThreshold, setAutoTopupThreshold] = useState('5');
  const [autoTopupAmount, setAutoTopupAmount] = useState('30');
  const [savingAutoTopup, setSavingAutoTopup] = useState(false);
  const [inviteCode, setInviteCode] = useState('');
  const [inviteStats, setInviteStats] = useState({ total_invites: 0, successful_invites: 0, earned_minutes: 0 });

  const fetchData = useCallback(async () => {
    try {
      const [balanceRes, txRes, autoTopupRes, invitesRes] = await Promise.all([
        http.get('/mobile/wallet/balance'),
        http.get('/mobile/wallet/transactions'),
        http.get('/mobile/wallet/auto-topup'),
        http.get('/mobile/invites'),
      ]);
      setBalance(balanceRes.data.data?.balance_minutes ?? 0);
      setTransactions(txRes.data.transactions ?? []);
      // Auto top-up settings
      const autoSettings = autoTopupRes.data.settings ?? {};
      setAutoTopupEnabled(autoSettings.enabled ?? false);
      setAutoTopupThreshold(String(autoSettings.threshold ?? 5));
      setAutoTopupAmount(String(autoSettings.amount ?? 30));
      // Invite stats
      setInviteCode(invitesRes.data.invite_code ?? '');
      setInviteStats(invitesRes.data.stats ?? { total_invites: 0, successful_invites: 0, earned_minutes: 0 });
    } catch (err) {
      console.error('Wallet fetch error:', err);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  const onRefresh = () => {
    setRefreshing(true);
    fetchData();
  };

  const handleTopup = async () => {
    const minutes = parseInt(topupAmount, 10);
    if (isNaN(minutes) || minutes < 1) {
      Alert.alert('Invalid Amount', 'Please enter a valid number of minutes.');
      return;
    }
    setTopupLoading(true);
    try {
      await http.post('/mobile/wallet/topup', { minutes });
      setTopupModalVisible(false);
      setTopupAmount('30');
      fetchData();
      Alert.alert('Success', `Added ${minutes} minutes to your wallet!`);
    } catch (err: any) {
      Alert.alert('Error', err.response?.data?.message || 'Top-up failed');
    } finally {
      setTopupLoading(false);
    }
  };

  const handleBuyPackage = async (pkg: typeof PACKAGES[0]) => {
    Alert.alert(
      'Confirm Purchase',
      `Buy ${pkg.minutes} minutes for $${pkg.price}?`,
      [
        { text: 'Cancel', style: 'cancel' },
        {
          text: 'Buy',
          onPress: async () => {
            setTopupLoading(true);
            try {
              await http.post('/mobile/wallet/topup', { minutes: pkg.minutes, package_price: pkg.price });
              setPackagesModalVisible(false);
              fetchData();
              Alert.alert('Success', `Added ${pkg.minutes} minutes to your wallet!`);
            } catch (err: any) {
              Alert.alert('Error', err.response?.data?.message || 'Purchase failed');
            } finally {
              setTopupLoading(false);
            }
          },
        },
      ]
    );
  };

  const handleSaveAutoTopup = async () => {
    setSavingAutoTopup(true);
    try {
      await http.post('/mobile/wallet/auto-topup', {
        enabled: autoTopupEnabled,
        threshold: parseInt(autoTopupThreshold, 10),
        amount: parseInt(autoTopupAmount, 10),
      });
      setAutoTopupModalVisible(false);
      Alert.alert('Success', 'Auto top-up settings saved!');
    } catch (err: any) {
      Alert.alert('Error', err.response?.data?.message || 'Failed to save settings');
    } finally {
      setSavingAutoTopup(false);
    }
  };

  const handleShareInvite = () => {
    const message = `Join me on CulturalTranslate! Get free minutes when you sign up with my code: ${inviteCode}\n\nDownload: https://culturaltranslate.com/download`;
    Alert.alert('Share Invite', message);
    // In production, use Share API
  };

  const formatDate = (iso: string) => {
    const d = new Date(iso);
    return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  };

  const getTypeIcon = (type: string) => {
    switch (type) {
      case 'topup': return 'add-circle';
      case 'usage': return 'call';
      case 'refund': return 'refresh-circle';
      case 'bonus': return 'gift';
      case 'referral': return 'people';
      default: return 'ellipse';
    }
  };

  const getTypeColor = (isCredit: boolean) => isCredit ? '#10B981' : '#EF4444';

  const renderTransaction = ({ item }: { item: Transaction }) => (
    <View style={styles.txRow}>
      <View style={[styles.txIcon, { backgroundColor: getTypeColor(item.is_credit) + '20' }]}>
        <Ionicons name={getTypeIcon(item.type) as any} size={20} color={getTypeColor(item.is_credit)} />
      </View>
      <View style={styles.txInfo}>
        <Text style={styles.txType}>{item.type.charAt(0).toUpperCase() + item.type.slice(1)}</Text>
        <Text style={styles.txDesc}>{item.description}</Text>
        <Text style={styles.txDate}>{formatDate(item.created_at)}</Text>
      </View>
      <Text style={[styles.txAmount, { color: getTypeColor(item.is_credit) }]}>
        {item.is_credit ? '+' : '-'}{Math.abs(item.minutes_amount)} min
      </Text>
    </View>
  );

  if (loading) {
    return (
      <SafeAreaView style={styles.container}>
        <ActivityIndicator size="large" color="#6366F1" />
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container} edges={['top']}>
      {/* Balance Card */}
      <View style={styles.balanceCard}>
        <Text style={styles.balanceLabel}>{t('availableBalance')}</Text>
        <View style={styles.balanceRow}>
          <Ionicons name="time" size={32} color="#fff" />
          <Text style={styles.balanceValue}>{balance}</Text>
          <Text style={styles.balanceUnit}>{t('minutes')}</Text>
        </View>
        <TouchableOpacity style={styles.topupBtn} onPress={() => setTopupModalVisible(true)}>
          <Ionicons name="add" size={20} color="#6366F1" />
          <Text style={styles.topupBtnText}>{t('addMinutes')}</Text>
        </TouchableOpacity>
      </View>

      {/* Quick Actions */}
      <View style={styles.quickActions}>
        {[
          { icon: 'card', label: t('buyPackages'), action: () => setPackagesModalVisible(true) },
          { icon: 'settings', label: t('autoTopup'), action: () => setAutoTopupModalVisible(true) },
          { icon: 'gift', label: t('inviteEarn'), action: () => setInviteModalVisible(true) },
        ].map((item, idx) => (
          <TouchableOpacity key={idx} style={styles.quickAction} onPress={item.action}>
            <View style={styles.quickIcon}>
              <Ionicons name={item.icon as any} size={22} color="#6366F1" />
            </View>
            <Text style={styles.quickLabel}>{item.label}</Text>
          </TouchableOpacity>
        ))}
      </View>

      {/* Transaction History */}
      <View style={styles.txHeader}>
        <Text style={styles.txTitle}>{t('transactionHistory')}</Text>
      </View>
      <FlatList
        data={transactions}
        keyExtractor={(item) => item.id.toString()}
        renderItem={renderTransaction}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
        ListEmptyComponent={
          <View style={styles.emptyState}>
            <Ionicons name="receipt-outline" size={48} color="#9CA3AF" />
            <Text style={styles.emptyText}>{t('noTransactions')}</Text>
            <Text style={styles.emptySubtext}>{t('addMinutes')}</Text>
          </View>
        }
        contentContainerStyle={transactions.length === 0 ? styles.emptyContainer : undefined}
      />

      {/* Top-up Modal */}
      <Modal visible={topupModalVisible} transparent animationType="slide">
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>{t('addMinutes')}</Text>
            <Text style={styles.modalSubtitle}>{t('minutes')}</Text>
            <TextInput
              style={styles.input}
              value={topupAmount}
              onChangeText={setTopupAmount}
              keyboardType="number-pad"
              placeholder={t('minutes')}
              placeholderTextColor="#9CA3AF"
            />
            <View style={styles.presetRow}>
              {[10, 30, 60, 120].map((amt) => (
                <TouchableOpacity
                  key={amt}
                  style={[styles.presetBtn, topupAmount === amt.toString() && styles.presetBtnActive]}
                  onPress={() => setTopupAmount(amt.toString())}
                >
                  <Text style={[styles.presetText, topupAmount === amt.toString() && styles.presetTextActive]}>
                    {amt}
                  </Text>
                </TouchableOpacity>
              ))}
            </View>
            <View style={styles.modalButtons}>
              <TouchableOpacity style={styles.cancelBtn} onPress={() => setTopupModalVisible(false)}>
                <Text style={styles.cancelBtnText}>{t('cancel')}</Text>
              </TouchableOpacity>
              <TouchableOpacity style={styles.confirmBtn} onPress={handleTopup} disabled={topupLoading}>
                {topupLoading ? (
                  <ActivityIndicator color="#fff" />
                ) : (
                  <Text style={styles.confirmBtnText}>{t('addMinutes')} {topupAmount}</Text>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>

      {/* Packages Modal */}
      <Modal visible={packagesModalVisible} transparent animationType="slide">
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>{t('selectPackage')}</Text>
            <Text style={styles.modalSubtitle}>{t('buyPackages')}</Text>
            {PACKAGES.map((pkg, idx) => (
              <TouchableOpacity
                key={idx}
                style={[styles.packageItem, pkg.popular && styles.packageItemPopular]}
                onPress={() => handleBuyPackage(pkg)}
                disabled={topupLoading}
              >
                {pkg.popular && (
                  <View style={styles.popularBadge}>
                    <Text style={styles.popularText}>{t('popular')}</Text>
                  </View>
                )}
                <View style={styles.packageInfo}>
                  <Text style={styles.packageMinutes}>{pkg.minutes} {t('min')}</Text>
                  <Text style={styles.packagePrice}>${pkg.price}</Text>
                </View>
                <Ionicons name="chevron-forward" size={20} color="#9CA3AF" />
              </TouchableOpacity>
            ))}
            <TouchableOpacity style={styles.cancelBtnFull} onPress={() => setPackagesModalVisible(false)}>
              <Text style={styles.cancelBtnText}>{t('cancel')}</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* Auto Top-up Modal */}
      <Modal visible={autoTopupModalVisible} transparent animationType="slide">
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>{t('autoTopupSettings')}</Text>
            <Text style={styles.modalSubtitle}>{t('autoTopup')}</Text>
            
            <TouchableOpacity
              style={styles.toggleRow}
              onPress={() => setAutoTopupEnabled(!autoTopupEnabled)}
            >
              <Text style={styles.toggleLabel}>{t('enableAutoTopup')}</Text>
              <View style={[styles.toggle, autoTopupEnabled && styles.toggleActive]}>
                <View style={[styles.toggleKnob, autoTopupEnabled && styles.toggleKnobActive]} />
              </View>
            </TouchableOpacity>

            {autoTopupEnabled && (
              <>
                <Text style={styles.inputLabel}>{t('whenBalanceFalls')}</Text>
                <TextInput
                  style={styles.input}
                  value={autoTopupThreshold}
                  onChangeText={setAutoTopupThreshold}
                  keyboardType="number-pad"
                  placeholder="5"
                  placeholderTextColor="#9CA3AF"
                />
                <Text style={styles.inputLabel}>{t('topupWith')}</Text>
                <TextInput
                  style={styles.input}
                  value={autoTopupAmount}
                  onChangeText={setAutoTopupAmount}
                  keyboardType="number-pad"
                  placeholder="30"
                  placeholderTextColor="#9CA3AF"
                />
              </>
            )}

            <View style={styles.modalButtons}>
              <TouchableOpacity style={styles.cancelBtn} onPress={() => setAutoTopupModalVisible(false)}>
                <Text style={styles.cancelBtnText}>{t('cancel')}</Text>
              </TouchableOpacity>
              <TouchableOpacity style={styles.confirmBtn} onPress={handleSaveAutoTopup} disabled={savingAutoTopup}>
                {savingAutoTopup ? (
                  <ActivityIndicator color="#fff" />
                ) : (
                  <Text style={styles.confirmBtnText}>{t('save')}</Text>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>

      {/* Invite & Earn Modal */}
      <Modal visible={inviteModalVisible} transparent animationType="slide">
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>{t('inviteAndEarn')}</Text>
            <Text style={styles.modalSubtitle}>{t('earnMinutesInvite')}</Text>
            
            <View style={styles.inviteCodeBox}>
              <Text style={styles.inviteCodeLabel}>{t('yourInviteCode')}</Text>
              <Text style={styles.inviteCode}>{inviteCode || t('loading')}</Text>
            </View>

            <View style={styles.statsRow}>
              <View style={styles.statItem}>
                <Text style={styles.statValue}>{inviteStats.total_invites}</Text>
                <Text style={styles.statLabel}>{t('invitesSent')}</Text>
              </View>
              <View style={styles.statItem}>
                <Text style={styles.statValue}>{inviteStats.successful_invites}</Text>
                <Text style={styles.statLabel}>{t('successfulSignups')}</Text>
              </View>
              <View style={styles.statItem}>
                <Text style={[styles.statValue, { color: '#10B981' }]}>+{inviteStats.earned_minutes}</Text>
                <Text style={styles.statLabel}>{t('minutesEarned')}</Text>
              </View>
            </View>

            <TouchableOpacity style={styles.shareBtn} onPress={handleShareInvite}>
              <Ionicons name="share-social" size={20} color="#fff" />
              <Text style={styles.shareBtnText}>{t('shareInvite')}</Text>
            </TouchableOpacity>

            <TouchableOpacity style={styles.cancelBtnFull} onPress={() => setInviteModalVisible(false)}>
              <Text style={styles.cancelBtnText}>{t('close')}</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F3F4F6' },
  balanceCard: {
    backgroundColor: '#6366F1',
    margin: 16,
    borderRadius: 20,
    padding: 24,
    alignItems: 'center',
  },
  balanceLabel: { color: 'rgba(255,255,255,0.8)', fontSize: 14, marginBottom: 8 },
  balanceRow: { flexDirection: 'row', alignItems: 'center', gap: 8 },
  balanceValue: { color: '#fff', fontSize: 48, fontWeight: '700' },
  balanceUnit: { color: 'rgba(255,255,255,0.8)', fontSize: 16, alignSelf: 'flex-end', marginBottom: 8 },
  topupBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 20,
    marginTop: 16,
    gap: 6,
  },
  topupBtnText: { color: '#6366F1', fontWeight: '600', fontSize: 14 },
  quickActions: { flexDirection: 'row', paddingHorizontal: 16, gap: 12, marginBottom: 16 },
  quickAction: { flex: 1, backgroundColor: '#fff', borderRadius: 12, padding: 12, alignItems: 'center' },
  quickIcon: { backgroundColor: '#EEF2FF', borderRadius: 10, padding: 10, marginBottom: 8 },
  quickLabel: { fontSize: 12, color: '#374151', fontWeight: '500' },
  txHeader: { paddingHorizontal: 16, paddingVertical: 8 },
  txTitle: { fontSize: 16, fontWeight: '600', color: '#111827' },
  txRow: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    marginHorizontal: 16,
    marginBottom: 8,
    padding: 12,
    borderRadius: 12,
  },
  txIcon: { width: 40, height: 40, borderRadius: 20, alignItems: 'center', justifyContent: 'center' },
  txInfo: { flex: 1, marginLeft: 12 },
  txType: { fontSize: 14, fontWeight: '600', color: '#111827' },
  txDesc: { fontSize: 12, color: '#6B7280', marginTop: 2 },
  txDate: { fontSize: 11, color: '#9CA3AF', marginTop: 4 },
  txAmount: { fontSize: 14, fontWeight: '600' },
  emptyState: { alignItems: 'center', paddingVertical: 40 },
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
  presetRow: { flexDirection: 'row', gap: 8, marginTop: 16 },
  presetBtn: { flex: 1, backgroundColor: '#F3F4F6', borderRadius: 10, paddingVertical: 12, alignItems: 'center' },
  presetBtnActive: { backgroundColor: '#6366F1' },
  presetText: { fontSize: 14, fontWeight: '600', color: '#6B7280' },
  presetTextActive: { color: '#fff' },
  modalButtons: { flexDirection: 'row', gap: 12, marginTop: 24 },
  cancelBtn: { flex: 1, paddingVertical: 14, borderRadius: 12, backgroundColor: '#F3F4F6', alignItems: 'center' },
  cancelBtnText: { color: '#6B7280', fontWeight: '600' },
  confirmBtn: { flex: 2, paddingVertical: 14, borderRadius: 12, backgroundColor: '#6366F1', alignItems: 'center' },
  confirmBtnText: { color: '#fff', fontWeight: '600' },
  // Package styles
  packageItem: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F9FAFB',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#E5E7EB',
  },
  packageItemPopular: { borderColor: '#6366F1', borderWidth: 2 },
  popularBadge: {
    position: 'absolute',
    top: -10,
    right: 10,
    backgroundColor: '#6366F1',
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 4,
  },
  popularText: { color: '#fff', fontSize: 10, fontWeight: '700' },
  packageInfo: { flex: 1 },
  packageMinutes: { fontSize: 18, fontWeight: '700', color: '#111827' },
  packagePrice: { fontSize: 14, color: '#6B7280', marginTop: 2 },
  cancelBtnFull: {
    paddingVertical: 14,
    borderRadius: 12,
    backgroundColor: '#F3F4F6',
    alignItems: 'center',
    marginTop: 8,
  },
  // Auto top-up styles
  toggleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 12,
    marginBottom: 16,
  },
  toggleLabel: { fontSize: 16, fontWeight: '500', color: '#111827' },
  toggle: {
    width: 50,
    height: 28,
    borderRadius: 14,
    backgroundColor: '#E5E7EB',
    justifyContent: 'center',
    padding: 2,
  },
  toggleActive: { backgroundColor: '#6366F1' },
  toggleKnob: {
    width: 24,
    height: 24,
    borderRadius: 12,
    backgroundColor: '#fff',
  },
  toggleKnobActive: { alignSelf: 'flex-end' },
  inputLabel: { fontSize: 14, color: '#6B7280', marginBottom: 8, marginTop: 12 },
  // Invite styles
  inviteCodeBox: {
    backgroundColor: '#EEF2FF',
    borderRadius: 12,
    padding: 20,
    alignItems: 'center',
    marginBottom: 20,
  },
  inviteCodeLabel: { fontSize: 12, color: '#6B7280', marginBottom: 4 },
  inviteCode: { fontSize: 28, fontWeight: '700', color: '#6366F1', letterSpacing: 2 },
  statsRow: { flexDirection: 'row', justifyContent: 'space-around', marginBottom: 20 },
  statItem: { alignItems: 'center' },
  statValue: { fontSize: 24, fontWeight: '700', color: '#111827' },
  statLabel: { fontSize: 12, color: '#6B7280', marginTop: 4 },
  shareBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#6366F1',
    borderRadius: 12,
    paddingVertical: 14,
    gap: 8,
  },
  shareBtnText: { color: '#fff', fontWeight: '600', fontSize: 16 },
});
