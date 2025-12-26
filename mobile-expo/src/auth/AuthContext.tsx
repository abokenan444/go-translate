import React from 'react';
import { getAuthToken, setAuthToken } from '../api/http';
import { me } from '../api/auth';

interface User {
  id: number;
  name: string;
  email: string;
}

type AuthContextValue = {
  isAuthed: boolean;
  setAuthed: (value: boolean) => void;
  bootstrapping: boolean;
  signOut: () => Promise<void>;
  user: User | null;
  setUser: (user: User | null) => void;
};

const AuthContext = React.createContext<AuthContextValue | null>(null);

export function useAuth() {
  const ctx = React.useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');
  return ctx;
}

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [isAuthed, setAuthed] = React.useState(false);
  const [bootstrapping, setBootstrapping] = React.useState(true);
  const [user, setUser] = React.useState<User | null>(null);

  React.useEffect(() => {
    let cancelled = false;

    (async () => {
      try {
        const token = await getAuthToken();
        if (!token) {
          if (!cancelled) setAuthed(false);
          return;
        }
        const res = await me();
        if (!cancelled) {
          setUser(res.data.user || res.data);
          setAuthed(true);
        }
      } catch {
        await setAuthToken(null);
        if (!cancelled) setAuthed(false);
      } finally {
        if (!cancelled) setBootstrapping(false);
      }
    })();

    return () => {
      cancelled = true;
    };
  }, []);

  const signOut = React.useCallback(async () => {
    await setAuthToken(null);
    setUser(null);
    setAuthed(false);
  }, []);

  return (
    <AuthContext.Provider value={{ isAuthed, setAuthed, bootstrapping, signOut, user, setUser }}>
      {children}
    </AuthContext.Provider>
  );
}
