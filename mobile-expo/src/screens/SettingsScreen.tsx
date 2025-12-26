import React, { useEffect, useState, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
  Modal,
  FlatList,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { logout } from '../api/auth';
import { useAuth } from '../auth/AuthContext';
import { http } from '../api/http';
import { useLanguage } from '../context/LanguageContext';

interface Language {
  code: string;
  name: string;
  name_en: string;
}

interface Settings {
  app_language: string;
  default_send_language: string;
  default_receive_language: string;
  auto_topup_enabled: boolean;
  auto_topup_threshold: number;
  auto_topup_amount: number;
}

export default function SettingsScreen() {
  const { user, signOut } = useAuth();
  const { t, setLocale, locale } = useLanguage();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [settings, setSettings] = useState<Settings>({
    app_language: 'en',
    default_send_language: 'en',
    default_receive_language: 'ar',
    auto_topup_enabled: false,
    auto_topup_threshold: 5,
    auto_topup_amount: 30,
  });
  const [languages, setLanguages] = useState<Language[]>([]);
  const [languageModalVisible, setLanguageModalVisible] = useState(false);
  const [languageModalType, setLanguageModalType] = useState<'send' | 'receive' | 'app'>('send');

  const fetchData = useCallback(async () => {
    try {
      const [settingsRes, langRes] = await Promise.all([
        http.get('/mobile/settings'),
        http.get('/mobile/settings/languages'),
      ]);
      const serverSettings = settingsRes.data.settings;
      setSettings(serverSettings);
      setLanguages(langRes.data.languages ?? []);
      
      // Sync app language from server if different
      if (serverSettings.app_language && serverSettings.app_language !== locale) {
        await setLocale(serverSettings.app_language);
      }
    } catch (err) {
      console.error('Settings fetch error:', err);
    } finally {
      setLoading(false);
    }
  }, [locale, setLocale]);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  const updateSetting = async (key: string, value: any) => {
    setSaving(true);
    try {
      console.log('Updating setting:', key, value);
      const res = await http.post('/mobile/settings', { [key]: value });
      console.log('Response:', res.data);
      setSettings(res.data.settings);
      // Show success feedback
      if (key.includes('language')) {
        Alert.alert('✅ تم الحفظ', `تم تغيير اللغة بنجاح`);
      }
    } catch (err: any) {
      console.error('Update error:', err);
      Alert.alert('Error', err.response?.data?.message || 'Failed to update settings');
    } finally {
      setSaving(false);
    }
  };

  const handleSelectLanguage = async (lang: Language) => {
    const key = languageModalType === 'send' ? 'default_send_language' :
                languageModalType === 'receive' ? 'default_receive_language' : 'app_language';
    setSettings((s) => ({ ...s, [key]: lang.code }));
    updateSetting(key, lang.code);
    setLanguageModalVisible(false);
    
    // If changing app language, apply it locally using context
    if (languageModalType === 'app') {
      await setLocale(lang.code);
      console.log('Language changed to:', lang.code);
    }
  };

  const openLanguageModal = (type: 'send' | 'receive' | 'app') => {
    setLanguageModalType(type);
    setLanguageModalVisible(true);
  };

  const getLanguageName = (code: string) => {
    const lang = languages.find((l) => l.code === code);
    return lang ? `${lang.name_en} (${lang.name})` : code.toUpperCase();
  };

  const handleLogout = () => {
    Alert.alert(
      'Logout',
      'Are you sure you want to logout?',
      [
        { text: 'Cancel', style: 'cancel' },
        {
          text: 'Logout',
          style: 'destructive',
          onPress: async () => {
            try {
              await logout();
            } finally {
              await signOut();
            }
          },
        },
      ]
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
      <ScrollView showsVerticalScrollIndicator={false}>
        {/* Header */}
        <View style={styles.header}>
          <Text style={styles.title}>{t('settings')}</Text>
          {saving && <ActivityIndicator size="small" color="#6366F1" />}
        </View>

        {/* Profile Section */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>{t('profile')}</Text>
          <View style={styles.card}>
            <View style={styles.profileRow}>
              <View style={styles.profileAvatar}>
                <Text style={styles.profileAvatarText}>
                  {(user?.name || 'U').slice(0, 2).toUpperCase()}
                </Text>
              </View>
              <View style={styles.profileInfo}>
                <Text style={styles.profileName}>{user?.name || 'User'}</Text>
                <Text style={styles.profileEmail}>{user?.email || ''}</Text>
              </View>
            </View>
          </View>
        </View>

        {/* Language Settings */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>{t('languagePrefs')}</Text>
          <View style={styles.card}>
            <TouchableOpacity style={styles.settingRow} onPress={() => openLanguageModal('send')}>
              <View style={styles.settingIcon}>
                <Ionicons name="mic" size={20} color="#6366F1" />
              </View>
              <View style={styles.settingInfo}>
                <Text style={styles.settingLabel}>{t('iSpeak')}</Text>
                <Text style={styles.settingValue}>{getLanguageName(settings.default_send_language)}</Text>
              </View>
              <Ionicons name="chevron-forward" size={20} color="#9CA3AF" />
            </TouchableOpacity>

            <View style={styles.divider} />

            <TouchableOpacity style={styles.settingRow} onPress={() => openLanguageModal('receive')}>
              <View style={styles.settingIcon}>
                <Ionicons name="ear" size={20} color="#10B981" />
              </View>
              <View style={styles.settingInfo}>
                <Text style={styles.settingLabel}>{t('translateTo')}</Text>
                <Text style={styles.settingValue}>{getLanguageName(settings.default_receive_language)}</Text>
              </View>
              <Ionicons name="chevron-forward" size={20} color="#9CA3AF" />
            </TouchableOpacity>

            <View style={styles.divider} />

            <TouchableOpacity style={styles.settingRow} onPress={() => openLanguageModal('app')}>
              <View style={styles.settingIcon}>
                <Ionicons name="globe" size={20} color="#F59E0B" />
              </View>
              <View style={styles.settingInfo}>
                <Text style={styles.settingLabel}>{t('appLanguage')}</Text>
                <Text style={styles.settingValue}>{getLanguageName(settings.app_language)}</Text>
              </View>
              <Ionicons name="chevron-forward" size={20} color="#9CA3AF" />
            </TouchableOpacity>
          </View>
        </View>

        {/* About Section */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>{t('about')}</Text>
          <View style={styles.card}>
            <View style={styles.settingRow}>
              <View style={styles.settingIcon}>
                <Ionicons name="information-circle" size={20} color="#6B7280" />
              </View>
              <View style={styles.settingInfo}>
                <Text style={styles.settingLabel}>{t('version')}</Text>
                <Text style={styles.settingValue}>1.0.0</Text>
              </View>
            </View>
          </View>
        </View>

        {/* Logout Button */}
        <TouchableOpacity style={styles.logoutBtn} onPress={handleLogout}>
          <Ionicons name="log-out-outline" size={20} color="#EF4444" />
          <Text style={styles.logoutText}>{t('logout')}</Text>
        </TouchableOpacity>
      </ScrollView>

      {/* Language Selection Modal */}
      <Modal 
        visible={languageModalVisible} 
        transparent 
        animationType="slide"
        onRequestClose={() => setLanguageModalVisible(false)}
      >
        <TouchableOpacity 
          style={styles.modalOverlay} 
          activeOpacity={1} 
          onPress={() => setLanguageModalVisible(false)}
        >
          <TouchableOpacity 
            style={styles.modalContent} 
            activeOpacity={1} 
            onPress={(e) => e.stopPropagation()}
          >
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>
                {languageModalType === 'send' ? t('iSpeak') :
                 languageModalType === 'receive' ? t('translateTo') : t('appLanguage')}
              </Text>
              <TouchableOpacity onPress={() => setLanguageModalVisible(false)}>
                <Ionicons name="close" size={24} color="#6B7280" />
              </TouchableOpacity>
            </View>
            {languages.length === 0 ? (
              <View style={styles.emptyLanguages}>
                <ActivityIndicator size="large" color="#6366F1" />
                <Text style={styles.emptyText}>Loading languages...</Text>
              </View>
            ) : (
              <FlatList
                data={languages}
                keyExtractor={(item) => item.code}
                renderItem={({ item }) => {
                  const currentValue = languageModalType === 'send' ? settings.default_send_language :
                                       languageModalType === 'receive' ? settings.default_receive_language :
                                       settings.app_language;
                  const isSelected = item.code === currentValue;
                  return (
                    <TouchableOpacity
                      style={[styles.languageOption, isSelected && styles.languageOptionSelected]}
                      onPress={() => handleSelectLanguage(item)}
                    >
                      <View>
                        <Text style={[styles.languageName, isSelected && styles.languageNameSelected]}>
                          {item.name}
                        </Text>
                        <Text style={styles.languageNameEn}>{item.name_en}</Text>
                      </View>
                      {isSelected && <Ionicons name="checkmark-circle" size={24} color="#6366F1" />}
                    </TouchableOpacity>
                  );
                }}
                style={styles.languageList}
                showsVerticalScrollIndicator={true}
              />
            )}
          </TouchableOpacity>
        </TouchableOpacity>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F3F4F6' },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 16, paddingVertical: 12 },
  title: { fontSize: 28, fontWeight: '700', color: '#111827' },
  section: { marginTop: 16, paddingHorizontal: 16 },
  sectionTitle: { fontSize: 14, fontWeight: '600', color: '#6B7280', textTransform: 'uppercase', marginBottom: 8, marginLeft: 4 },
  card: { backgroundColor: '#fff', borderRadius: 16, overflow: 'hidden' },
  profileRow: { flexDirection: 'row', alignItems: 'center', padding: 16 },
  profileAvatar: { width: 56, height: 56, borderRadius: 28, backgroundColor: '#6366F1', alignItems: 'center', justifyContent: 'center' },
  profileAvatarText: { color: '#fff', fontSize: 20, fontWeight: '600' },
  profileInfo: { marginLeft: 12 },
  profileName: { fontSize: 18, fontWeight: '600', color: '#111827' },
  profileEmail: { fontSize: 14, color: '#6B7280', marginTop: 2 },
  settingRow: { flexDirection: 'row', alignItems: 'center', padding: 16 },
  settingIcon: { width: 36, height: 36, borderRadius: 10, backgroundColor: '#F3F4F6', alignItems: 'center', justifyContent: 'center' },
  settingInfo: { flex: 1, marginLeft: 12 },
  settingLabel: { fontSize: 14, color: '#6B7280' },
  settingValue: { fontSize: 16, fontWeight: '500', color: '#111827', marginTop: 2 },
  divider: { height: 1, backgroundColor: '#F3F4F6', marginLeft: 64 },
  logoutBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', marginTop: 32, marginBottom: 40, gap: 8 },
  logoutText: { color: '#EF4444', fontSize: 16, fontWeight: '600' },
  modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.5)', justifyContent: 'flex-end' },
  modalContent: { backgroundColor: '#fff', borderTopLeftRadius: 24, borderTopRightRadius: 24, maxHeight: '70%', minHeight: 300 },
  modalHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', padding: 20, borderBottomWidth: 1, borderBottomColor: '#F3F4F6' },
  modalTitle: { fontSize: 18, fontWeight: '700', color: '#111827' },
  languageList: { padding: 8, flexGrow: 1 },
  languageOption: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', padding: 16, borderRadius: 12 },
  languageOptionSelected: { backgroundColor: '#EEF2FF' },
  languageName: { fontSize: 18, fontWeight: '500', color: '#111827' },
  languageNameSelected: { color: '#6366F1' },
  languageNameEn: { fontSize: 13, color: '#6B7280', marginTop: 2 },
  emptyLanguages: { alignItems: 'center', justifyContent: 'center', padding: 40 },
  emptyText: { marginTop: 12, fontSize: 16, color: '#6B7280' },
});
