import { apiFetch } from './http';

// ============ Wallet API ============

export interface WalletInfo {
  balance_seconds: number;
  balance_minutes: number;
  auto_recharge: boolean;
  auto_recharge_threshold: number;
  auto_recharge_amount: number;
}

export async function getWallet(): Promise<WalletInfo> {
  return apiFetch('/api/mobile/wallet');
}

export async function rechargeWallet(minutes: number): Promise<WalletInfo> {
  return apiFetch('/api/mobile/wallet/recharge', {
    method: 'POST',
    body: JSON.stringify({ minutes }),
  });
}

export async function setAutoRecharge(settings: {
  enabled: boolean;
  threshold_minutes?: number;
  recharge_minutes?: number;
}): Promise<WalletInfo> {
  return apiFetch('/api/mobile/wallet/auto-recharge', {
    method: 'POST',
    body: JSON.stringify(settings),
  });
}

export async function getWalletHistory(page = 1): Promise<{
  transactions: Array<{
    id: number;
    type: 'credit' | 'debit';
    amount_seconds: number;
    description: string;
    created_at: string;
  }>;
  meta: { current_page: number; last_page: number; total: number };
}> {
  return apiFetch(`/api/mobile/wallet/history?page=${page}`);
}

// ============ Calls API ============

export interface CallSession {
  id: number;
  public_id: string;
  is_active: boolean;
  created_at: string;
  participants: CallParticipant[];
}

export interface CallParticipant {
  id: number;
  user_id: number;
  user_name?: string;
  send_language: string;
  receive_language: string;
  status: 'connected' | 'disconnected' | 'pending';
  joined_at: string;
}

export async function createCallSession(options: {
  send_language: string;
  receive_language: string;
}): Promise<{ session: CallSession }> {
  return apiFetch('/api/mobile/realtime/create', {
    method: 'POST',
    body: JSON.stringify(options),
  });
}

export async function joinCallSession(
  publicId: string,
  options: {
    send_language: string;
    receive_language: string;
  }
): Promise<{ session: CallSession; participant: CallParticipant }> {
  return apiFetch(`/api/mobile/realtime/${publicId}/join`, {
    method: 'POST',
    body: JSON.stringify(options),
  });
}

export async function leaveCallSession(publicId: string): Promise<{ ok: boolean }> {
  return apiFetch(`/api/mobile/realtime/${publicId}/leave`, {
    method: 'POST',
  });
}

export async function getCallSession(publicId: string): Promise<{ session: CallSession }> {
  return apiFetch(`/api/mobile/realtime/${publicId}`);
}

export async function updateParticipantLanguages(
  publicId: string,
  languages: { send_language?: string; receive_language?: string }
): Promise<{ participant: CallParticipant }> {
  return apiFetch(`/api/mobile/realtime/${publicId}/languages`, {
    method: 'POST',
    body: JSON.stringify(languages),
  });
}

// ============ Audio Streaming API ============

export interface AudioTurn {
  turn_id: number;
  direction: string;
  source_text: string;
  translated_text: string;
  source_language: string;
  target_language: string;
  translated_audio_url: string | null;
  latency_ms: number;
  billed_seconds: number;
}

export async function sendAudioChunk(
  publicId: string,
  audioBlob: Blob,
  durationMs: number
): Promise<AudioTurn> {
  const formData = new FormData();
  formData.append('audio', audioBlob, 'chunk.webm');
  formData.append('duration_ms', String(durationMs));
  formData.append('direction', 'mobile');

  return apiFetch(`/api/mobile/realtime/${publicId}/audio`, {
    method: 'POST',
    body: formData,
    headers: {}, // Let browser set Content-Type for FormData
  });
}

export async function pollTurns(
  publicId: string
): Promise<{
  ok: boolean;
  turns: Array<{
    id: number;
    user_id: number;
    direction: string;
    source_text: string;
    translated_text: string;
    source_language: string;
    target_language: string;
    translated_audio_url: string | null;
    latency_ms: number;
    created_at: string;
  }>;
}> {
  return apiFetch(`/api/mobile/realtime/${publicId}/poll`);
}

// ============ Contacts API ============

export interface Contact {
  id: number;
  name: string;
  email?: string;
  phone?: string;
  user_id?: number;
  preferred_language?: string;
  is_app_user: boolean;
  avatar_url?: string;
  created_at: string;
}

export async function getContacts(): Promise<{ contacts: Contact[] }> {
  return apiFetch('/api/mobile/contacts');
}

export async function addContact(contact: {
  name: string;
  email?: string;
  phone?: string;
  preferred_language?: string;
}): Promise<{ contact: Contact }> {
  return apiFetch('/api/mobile/contacts', {
    method: 'POST',
    body: JSON.stringify(contact),
  });
}

export async function updateContact(
  id: number,
  contact: Partial<Contact>
): Promise<{ contact: Contact }> {
  return apiFetch(`/api/mobile/contacts/${id}`, {
    method: 'PUT',
    body: JSON.stringify(contact),
  });
}

export async function deleteContact(id: number): Promise<{ ok: boolean }> {
  return apiFetch(`/api/mobile/contacts/${id}`, {
    method: 'DELETE',
  });
}

export async function inviteContact(contactId: number): Promise<{ ok: boolean; invite_link: string }> {
  return apiFetch(`/api/mobile/contacts/${contactId}/invite`, {
    method: 'POST',
  });
}

// ============ Call History API ============

export interface CallHistoryItem {
  id: number;
  public_id: string;
  contact_name?: string;
  contact_id?: number;
  direction: 'incoming' | 'outgoing';
  duration_seconds: number;
  billed_seconds: number;
  source_language: string;
  target_language: string;
  started_at: string;
  ended_at?: string;
}

export async function getCallHistory(page = 1): Promise<{
  calls: CallHistoryItem[];
  meta: { current_page: number; last_page: number; total: number };
}> {
  return apiFetch(`/api/mobile/calls/history?page=${page}`);
}

// ============ User Settings API ============

export interface UserSettings {
  default_send_language: string;
  default_receive_language: string;
  app_language: string;
  notifications_enabled: boolean;
  auto_answer: boolean;
  voice_quality: 'low' | 'medium' | 'high';
}

export async function getUserSettings(): Promise<UserSettings> {
  return apiFetch('/api/mobile/settings');
}

export async function updateUserSettings(settings: Partial<UserSettings>): Promise<UserSettings> {
  return apiFetch('/api/mobile/settings', {
    method: 'PUT',
    body: JSON.stringify(settings),
  });
}

// ============ Invitations API ============

export async function sendInvitation(options: {
  email?: string;
  phone?: string;
  name?: string;
}): Promise<{ ok: boolean; invite_code: string; invite_link: string }> {
  return apiFetch('/api/mobile/invitations', {
    method: 'POST',
    body: JSON.stringify(options),
  });
}

export async function getMyInvitations(): Promise<{
  invitations: Array<{
    id: number;
    code: string;
    recipient_email?: string;
    recipient_phone?: string;
    status: 'pending' | 'accepted' | 'expired';
    created_at: string;
    accepted_at?: string;
  }>;
}> {
  return apiFetch('/api/mobile/invitations');
}
