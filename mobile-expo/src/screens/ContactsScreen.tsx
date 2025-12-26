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
  TextInput,
  Modal,
  KeyboardAvoidingView,
  Platform,
  TouchableWithoutFeedback,
  Keyboard,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import { http } from '../api/http';
import { useLanguage } from '../context/LanguageContext';

interface Contact {
  id: number;
  name: string;
  phone: string | null;
  email: string | null;
  avatar_url: string | null;
  is_favorite: boolean;
  is_registered: boolean;
  contact_user_id: number | null;
  last_called_at: string | null;
}

export default function ContactsScreen() {
  const navigation = useNavigation<any>();
  const { t } = useLanguage();
  const [contacts, setContacts] = useState<Contact[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [modalVisible, setModalVisible] = useState(false);
  const [newContact, setNewContact] = useState({ name: '', phone: '', email: '' });
  const [saving, setSaving] = useState(false);
  const [sendingInvite, setSendingInvite] = useState(false);

  const fetchContacts = useCallback(async () => {
    try {
      const res = await http.get('/mobile/contacts');
      setContacts(res.data.contacts ?? []);
    } catch (err) {
      console.error('Contacts fetch error:', err);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useEffect(() => {
    fetchContacts();
  }, [fetchContacts]);

  // Refresh contacts when screen comes into focus
  useFocusEffect(
    useCallback(() => {
      fetchContacts();
    }, [fetchContacts])
  );

  const onRefresh = () => {
    setRefreshing(true);
    fetchContacts();
  };

  const handleAddContact = async () => {
    if (!newContact.name.trim()) {
      Alert.alert(t('error'), t('name'));
      return;
    }
    setSaving(true);
    try {
      await http.post('/mobile/contacts', newContact);
      setModalVisible(false);
      setNewContact({ name: '', phone: '', email: '' });
      fetchContacts();
    } catch (err: any) {
      Alert.alert(t('error'), err.response?.data?.message || 'Failed to add contact');
    } finally {
      setSaving(false);
    }
  };

  const handleToggleFavorite = async (contact: Contact) => {
    try {
      await http.put(`/mobile/contacts/${contact.id}`, { is_favorite: !contact.is_favorite });
      fetchContacts();
    } catch (err) {
      console.error('Toggle favorite error:', err);
    }
  };

  const handleDeleteContact = (contact: Contact) => {
    Alert.alert(
      t('deleteContact'),
      `${t('removeContactConfirm')} ${contact.name}`,
      [
        { text: t('cancel'), style: 'cancel' },
        {
          text: t('delete'),
          style: 'destructive',
          onPress: async () => {
            try {
              await http.delete(`/mobile/contacts/${contact.id}`);
              fetchContacts();
            } catch (err) {
              Alert.alert(t('error'), 'Failed to delete contact');
            }
          },
        },
      ]
    );
  };

  const handleCall = (contact: Contact) => {
    if (!contact.is_registered) {
      Alert.alert(
        t('notRegistered'),
        `${contact.name} ${t('notRegisteredMessage')}`,
        [
          { text: t('cancel'), style: 'cancel' },
          { text: t('sendInvite'), onPress: () => handleSendInvite(contact) },
        ]
      );
      return;
    }
    // Navigate to call session
    navigation.navigate('CallSession', { contactId: contact.id, contactName: contact.name });
  };

  const handleSendInvite = async (contact: Contact) => {
    if (!contact.email && !contact.phone) {
      Alert.alert(t('error'), t('noContactInfo'));
      return;
    }
    setSendingInvite(true);
    try {
      await http.post('/mobile/invites', {
        contact_id: contact.id,
        email: contact.email,
        phone: contact.phone,
        name: contact.name,
      });
      Alert.alert(
        `${t('inviteSent')} 🎉`,
        t('inviteSentMessage')
      );
    } catch (err: any) {
      Alert.alert(t('error'), err.response?.data?.message || 'Failed to send invite');
    } finally {
      setSendingInvite(false);
    }
  };

  const filteredContacts = contacts.filter(
    (c) =>
      c.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.phone?.includes(searchQuery) ||
      c.email?.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const getInitials = (name: string) => {
    const parts = name.trim().split(' ');
    return parts.length > 1
      ? `${parts[0][0]}${parts[parts.length - 1][0]}`.toUpperCase()
      : name.slice(0, 2).toUpperCase();
  };

  const renderContact = ({ item }: { item: Contact }) => (
    <TouchableOpacity
      style={styles.contactRow}
      onPress={() => handleCall(item)}
      onLongPress={() => handleDeleteContact(item)}
    >
      <View style={[styles.avatar, { backgroundColor: item.is_registered ? '#6366F1' : '#9CA3AF' }]}>
        <Text style={styles.avatarText}>{getInitials(item.name)}</Text>
      </View>
      <View style={styles.contactInfo}>
        <View style={styles.nameRow}>
          <Text style={styles.contactName}>{item.name}</Text>
          {item.is_registered && (
            <View style={styles.registeredBadge}>
              <Ionicons name="checkmark-circle" size={14} color="#10B981" />
            </View>
          )}
        </View>
        <Text style={styles.contactDetail}>
          {item.phone || item.email || t('noContactInfo')}
        </Text>
      </View>
      <View style={styles.contactActions}>
        <TouchableOpacity style={styles.actionBtn} onPress={() => handleToggleFavorite(item)}>
          <Ionicons
            name={item.is_favorite ? 'star' : 'star-outline'}
            size={20}
            color={item.is_favorite ? '#F59E0B' : '#9CA3AF'}
          />
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.callBtn, !item.is_registered && styles.callBtnDisabled]}
          onPress={() => handleCall(item)}
        >
          <Ionicons name="call" size={18} color={item.is_registered ? '#fff' : '#9CA3AF'} />
        </TouchableOpacity>
      </View>
    </TouchableOpacity>
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
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.title}>{t('contacts')}</Text>
        <TouchableOpacity style={styles.addBtn} onPress={() => setModalVisible(true)}>
          <Ionicons name="person-add" size={22} color="#6366F1" />
        </TouchableOpacity>
      </View>

      {/* Search Bar */}
      <View style={styles.searchContainer}>
        <Ionicons name="search" size={18} color="#9CA3AF" />
        <TextInput
          style={styles.searchInput}
          placeholder={t('searchContacts')}
          placeholderTextColor="#9CA3AF"
          value={searchQuery}
          onChangeText={setSearchQuery}
        />
        {searchQuery.length > 0 && (
          <TouchableOpacity onPress={() => setSearchQuery('')}>
            <Ionicons name="close-circle" size={18} color="#9CA3AF" />
          </TouchableOpacity>
        )}
      </View>

      {/* Contacts List */}
      <FlatList
        data={filteredContacts}
        keyExtractor={(item) => item.id.toString()}
        renderItem={renderContact}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
        ListEmptyComponent={
          <View style={styles.emptyState}>
            <Ionicons name="people-outline" size={48} color="#9CA3AF" />
            <Text style={styles.emptyText}>
              {searchQuery ? t('noContacts') : t('noContacts')}
            </Text>
            <Text style={styles.emptySubtext}>
              {searchQuery ? t('noContactsSubtext') : t('noContactsSubtext')}
            </Text>
          </View>
        }
        contentContainerStyle={filteredContacts.length === 0 ? styles.emptyContainer : styles.listContent}
      />

      {/* Add Contact Modal */}
      <Modal visible={modalVisible} transparent animationType="slide">
        <TouchableWithoutFeedback onPress={Keyboard.dismiss}>
          <KeyboardAvoidingView 
            style={styles.modalOverlay} 
            behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
          >
            <TouchableWithoutFeedback onPress={() => {}}>
              <View style={styles.modalContent}>
                <Text style={styles.modalTitle}>{t('addContact')}</Text>
                <TextInput
                  style={styles.input}
                  placeholder={`${t('name')} *`}
                  placeholderTextColor="#9CA3AF"
                  value={newContact.name}
                  onChangeText={(text) => setNewContact({ ...newContact, name: text })}
                  returnKeyType="next"
                  blurOnSubmit={false}
                />
                <TextInput
                  style={styles.input}
                  placeholder={t('phone')}
                  placeholderTextColor="#9CA3AF"
                  keyboardType="phone-pad"
                  value={newContact.phone}
                  onChangeText={(text) => setNewContact({ ...newContact, phone: text })}
                  returnKeyType="next"
                  blurOnSubmit={false}
                />
                <TextInput
                  style={styles.input}
                  placeholder={t('email')}
                  placeholderTextColor="#9CA3AF"
                  keyboardType="email-address"
                  autoCapitalize="none"
                  value={newContact.email}
                  onChangeText={(text) => setNewContact({ ...newContact, email: text })}
                  returnKeyType="done"
                  onSubmitEditing={handleAddContact}
                />
                <Text style={styles.hint}>
                  If the email matches a registered user, you can call them directly.
                </Text>
                <View style={styles.modalButtons}>
                  <TouchableOpacity
                    style={styles.cancelBtn}
                    onPress={() => {
                      Keyboard.dismiss();
                      setModalVisible(false);
                      setNewContact({ name: '', phone: '', email: '' });
                    }}
                  >
                    <Text style={styles.cancelBtnText}>{t('cancel')}</Text>
                  </TouchableOpacity>
                  <TouchableOpacity 
                    style={styles.saveBtn} 
                    onPress={() => { Keyboard.dismiss(); handleAddContact(); }} 
                    disabled={saving}
                  >
                    {saving ? (
                      <ActivityIndicator color="#fff" />
                    ) : (
                      <Text style={styles.saveBtnText}>{t('addContact')}</Text>
                    )}
                  </TouchableOpacity>
                </View>
              </View>
            </TouchableWithoutFeedback>
          </KeyboardAvoidingView>
        </TouchableWithoutFeedback>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F3F4F6' },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 16, paddingVertical: 12 },
  title: { fontSize: 28, fontWeight: '700', color: '#111827' },
  addBtn: { backgroundColor: '#EEF2FF', borderRadius: 10, padding: 10 },
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    marginHorizontal: 16,
    marginBottom: 12,
    borderRadius: 12,
    paddingHorizontal: 12,
    paddingVertical: 10,
    gap: 8,
  },
  searchInput: { flex: 1, fontSize: 16, color: '#111827' },
  listContent: { paddingHorizontal: 16, paddingBottom: 20 },
  contactRow: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
    marginBottom: 8,
  },
  avatar: { width: 48, height: 48, borderRadius: 24, alignItems: 'center', justifyContent: 'center' },
  avatarText: { color: '#fff', fontSize: 16, fontWeight: '600' },
  contactInfo: { flex: 1, marginLeft: 12 },
  nameRow: { flexDirection: 'row', alignItems: 'center', gap: 6 },
  contactName: { fontSize: 16, fontWeight: '600', color: '#111827' },
  registeredBadge: { marginLeft: 4 },
  contactDetail: { fontSize: 13, color: '#6B7280', marginTop: 2 },
  contactActions: { flexDirection: 'row', alignItems: 'center', gap: 8 },
  actionBtn: { padding: 8 },
  callBtn: { backgroundColor: '#6366F1', borderRadius: 20, padding: 10 },
  callBtnDisabled: { backgroundColor: '#E5E7EB' },
  emptyState: { alignItems: 'center', paddingVertical: 60 },
  emptyText: { fontSize: 16, color: '#6B7280', marginTop: 12 },
  emptySubtext: { fontSize: 14, color: '#9CA3AF', marginTop: 4 },
  emptyContainer: { flexGrow: 1, justifyContent: 'center' },
  modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.5)', justifyContent: 'flex-end' },
  modalContent: { backgroundColor: '#fff', borderTopLeftRadius: 24, borderTopRightRadius: 24, padding: 24 },
  modalTitle: { fontSize: 20, fontWeight: '700', color: '#111827', textAlign: 'center', marginBottom: 20 },
  input: {
    backgroundColor: '#F3F4F6',
    borderRadius: 12,
    paddingHorizontal: 16,
    paddingVertical: 14,
    fontSize: 16,
    color: '#111827',
    marginBottom: 12,
  },
  hint: { fontSize: 12, color: '#9CA3AF', marginBottom: 20, textAlign: 'center' },
  modalButtons: { flexDirection: 'row', gap: 12 },
  cancelBtn: { flex: 1, paddingVertical: 14, borderRadius: 12, backgroundColor: '#F3F4F6', alignItems: 'center' },
  cancelBtnText: { color: '#6B7280', fontWeight: '600' },
  saveBtn: { flex: 2, paddingVertical: 14, borderRadius: 12, backgroundColor: '#6366F1', alignItems: 'center' },
  saveBtnText: { color: '#fff', fontWeight: '600' },
});
