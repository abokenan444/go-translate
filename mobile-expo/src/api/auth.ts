import { apiFetch, setAuthToken } from './http';

export async function login(email: string, password: string) {
  const data = await apiFetch('/mobile/auth/login', {
    method: 'POST',
    body: JSON.stringify({ email, password }),
  });
  const token = data?.token ?? data?.data?.token ?? data?.data?.access_token ?? data?.access_token ?? null;
  await setAuthToken(token);
  return data;
}

export async function register(name: string, email: string, password: string) {
  const data = await apiFetch('/mobile/auth/register', {
    method: 'POST',
    body: JSON.stringify({ name, email, password }),
  });
  const token = data?.token ?? data?.data?.token ?? data?.data?.access_token ?? data?.access_token ?? null;
  await setAuthToken(token);
  return data;
}

export async function me() {
  return apiFetch('/mobile/auth/me', { method: 'GET' });
}

export async function logout() {
  try {
    await apiFetch('/mobile/auth/logout', { method: 'POST' });
  } finally {
    await setAuthToken(null);
  }
}
