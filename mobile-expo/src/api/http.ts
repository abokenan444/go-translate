import * as SecureStore from 'expo-secure-store';
import { API_BASE_URL } from '../config';

const TOKEN_KEY = 'ct_mobile_token';

export async function setAuthToken(token: string | null) {
  if (!token) {
    await SecureStore.deleteItemAsync(TOKEN_KEY);
    return;
  }
  await SecureStore.setItemAsync(TOKEN_KEY, token);
}

export async function getAuthToken() {
  return SecureStore.getItemAsync(TOKEN_KEY);
}

export async function apiFetch(path: string, options: RequestInit = {}) {
  const token = await getAuthToken();
  const headers = new Headers(options.headers || {});
  headers.set('Accept', 'application/json');
  if (!(options.body instanceof FormData)) {
    headers.set('Content-Type', headers.get('Content-Type') || 'application/json');
  }
  if (token) headers.set('Authorization', `Bearer ${token}`);

  const res = await fetch(`${API_BASE_URL}${path}`, {
    ...options,
    headers,
  });

  const text = await res.text();
  let json: any = null;
  try {
    json = text ? JSON.parse(text) : null;
  } catch {
    // ignore
  }

  if (!res.ok) {
    const error: any = new Error(json?.message || `Request failed (${res.status})`);
    error.status = res.status;
    error.response = { data: json ?? { message: text } };
    error.payload = json ?? text;
    throw error;
  }

  return json;
}

// Axios-style wrapper for convenience
export const http = {
  async get(path: string, config?: { headers?: Record<string, string> }) {
    return { data: await apiFetch(path, { method: 'GET', headers: config?.headers }) };
  },
  async post(path: string, data?: any, config?: { headers?: Record<string, string> }) {
    const isFormData = data instanceof FormData;
    return {
      data: await apiFetch(path, {
        method: 'POST',
        body: isFormData ? data : JSON.stringify(data),
        headers: config?.headers,
      }),
    };
  },
  async put(path: string, data?: any, config?: { headers?: Record<string, string> }) {
    return {
      data: await apiFetch(path, {
        method: 'PUT',
        body: JSON.stringify(data),
        headers: config?.headers,
      }),
    };
  },
  async delete(path: string, config?: { headers?: Record<string, string> }) {
    return { data: await apiFetch(path, { method: 'DELETE', headers: config?.headers }) };
  },
};
