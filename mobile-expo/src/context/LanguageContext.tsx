import React, { createContext, useContext, useState, useEffect, useCallback } from 'react';
import { I18nManager } from 'react-native';
import { i18n, loadSavedLanguage, saveLanguage, isRTL } from '../i18n';

interface LanguageContextValue {
  locale: string;
  setLocale: (lang: string) => Promise<void>;
  t: (key: string) => string;
  isRTL: boolean;
}

const LanguageContext = createContext<LanguageContextValue | null>(null);

export function useLanguage() {
  const ctx = useContext(LanguageContext);
  if (!ctx) throw new Error('useLanguage must be used within LanguageProvider');
  return ctx;
}

export function LanguageProvider({ children }: { children: React.ReactNode }) {
  const [locale, setLocaleState] = useState(i18n.locale);
  const [isReady, setIsReady] = useState(false);

  useEffect(() => {
    loadSavedLanguage().then((savedLang) => {
      setLocaleState(savedLang);
      setIsReady(true);
    });
  }, []);

  const setLocale = useCallback(async (lang: string) => {
    i18n.locale = lang;
    await saveLanguage(lang);
    setLocaleState(lang);
    
    // Handle RTL
    const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
    const needsRTL = rtlLanguages.includes(lang);
    if (I18nManager.isRTL !== needsRTL) {
      I18nManager.allowRTL(needsRTL);
      I18nManager.forceRTL(needsRTL);
    }
  }, []);

  const t = useCallback((key: string) => {
    return i18n.t(key);
  }, [locale]); // Re-create when locale changes

  if (!isReady) {
    return null;
  }

  return (
    <LanguageContext.Provider value={{ 
      locale, 
      setLocale, 
      t,
      isRTL: ['ar', 'he', 'fa', 'ur'].includes(locale)
    }}>
      {children}
    </LanguageContext.Provider>
  );
}
