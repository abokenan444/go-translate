import React, { createContext, useContext, useEffect, useRef, useState, useCallback } from 'react';
import { Alert, Vibration, Platform, AppState } from 'react-native';
import { http, getAuthToken } from '../api/http';
import { useLanguage } from './LanguageContext';
import { useNavigation } from '@react-navigation/native';

interface IncomingCall {
  id: number;
  session_id: string;
  caller_name: string;
  caller_id: number;
}

interface IncomingCallContextType {
  incomingCall: IncomingCall | null;
  pendingCall: IncomingCall | null;
  handlePendingCall: () => void;
}

const IncomingCallContext = createContext<IncomingCallContextType | null>(null);

export function IncomingCallProvider({ children }: { children: React.ReactNode }) {
  const [incomingCall, setIncomingCall] = useState<IncomingCall | null>(null);
  const [pendingCall, setPendingCall] = useState<IncomingCall | null>(null);
  const [isAuthed, setIsAuthed] = useState(false);
  const isShowingAlertRef = useRef(false);
  const pollingRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const lastNotificationIdRef = useRef<number | null>(null);
  const vibrationIntervalRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const { t } = useLanguage();

  // Check auth status
  useEffect(() => {
    const checkAuth = async () => {
      const token = await getAuthToken();
      setIsAuthed(!!token);
    };
    checkAuth();
    const interval = setInterval(checkAuth, 2000);
    return () => clearInterval(interval);
  }, []);

  // Stop vibration helper
  const stopVibration = useCallback(() => {
    Vibration.cancel();
    if (vibrationIntervalRef.current) {
      clearInterval(vibrationIntervalRef.current);
      vibrationIntervalRef.current = null;
    }
  }, []);

  // Start vibration helper  
  const startVibration = useCallback(() => {
    stopVibration();
    if (Platform.OS === 'ios') {
      Vibration.vibrate();
      vibrationIntervalRef.current = setInterval(() => {
        Vibration.vibrate();
      }, 1500);
    } else {
      Vibration.vibrate([0, 500, 500, 500, 500, 500], true);
    }
  }, [stopVibration]);

  // Answer call - just store it and let navigation component handle it
  const handleAnswer = useCallback(async (call: IncomingCall) => {
    console.log('[IncomingCall] handleAnswer called', call);
    stopVibration();
    isShowingAlertRef.current = false;
    setIncomingCall(null);

    try {
      // Mark notification as read
      await http.post(`/mobile/notifications/${call.id}/read`);
      console.log('[IncomingCall] Notification marked as read');
      
      // Store pending call - will be handled by navigation component
      setPendingCall(call);
      console.log('[IncomingCall] Pending call stored');
    } catch (error) {
      console.error('[IncomingCall] Error in handleAnswer:', error);
      Alert.alert('خطأ', 'حدث خطأ أثناء الرد على المكالمة');
    }
  }, [stopVibration]);

  // Function to be called by navigation component to handle pending call
  const handlePendingCall = useCallback(() => {
    console.log('[IncomingCall] handlePendingCall called, pendingCall:', pendingCall);
    if (pendingCall) {
      setPendingCall(null);
    }
  }, [pendingCall]);

  // Reject call
  const handleReject = useCallback(async (call: IncomingCall) => {
    console.log('[IncomingCall] handleReject called', call);
    stopVibration();
    isShowingAlertRef.current = false;
    setIncomingCall(null);

    try {
      await http.post(`/mobile/notifications/${call.id}/read`);
    } catch (error) {
      console.error('[IncomingCall] Error:', error);
    }
  }, [stopVibration]);

  // Poll for incoming calls
  const checkForIncomingCalls = useCallback(async () => {
    // Skip if not authenticated or already showing alert
    if (!isAuthed || isShowingAlertRef.current) {
      return;
    }
    
    try {
      const response = await http.get('/mobile/notifications');
      const notifications = response.data.notifications || response.data.data || [];
      
      // Find unread incoming call
      const callNotification = notifications.find(
        (n: any) => n.type === 'incoming_call' && !n.read
      );

      // If we found one and it's new
      if (callNotification && callNotification.id !== lastNotificationIdRef.current) {
        console.log('[IncomingCall] New incoming call:', callNotification);
        lastNotificationIdRef.current = callNotification.id;
        isShowingAlertRef.current = true;
        
        const callData: IncomingCall = {
          id: callNotification.id,
          session_id: callNotification.data?.session_id,
          caller_name: callNotification.data?.caller_name || 'مجهول',
          caller_id: callNotification.data?.caller_id,
        };
        
        setIncomingCall(callData);
        
        // Start vibration
        startVibration();

        // Show alert after a small delay to ensure UI is ready
        setTimeout(() => {
          Alert.alert(
            '📞 مكالمة واردة',
            `${callData.caller_name} يتصل بك`,
            [
              {
                text: 'رفض',
                style: 'destructive',
                onPress: () => handleReject(callData),
              },
              {
                text: 'رد',
                style: 'default',
                onPress: () => handleAnswer(callData),
              },
            ],
            { cancelable: false }
          );
        }, 100);
      }
    } catch (error) {
      // Silent fail
    }
  }, [isAuthed, startVibration, handleAnswer, handleReject]);

  // Start/stop polling based on auth
  useEffect(() => {
    if (isAuthed) {
      // Poll every 3 seconds
      pollingRef.current = setInterval(checkForIncomingCalls, 3000);
      // Also check immediately
      checkForIncomingCalls();
    }

    return () => {
      if (pollingRef.current) {
        clearInterval(pollingRef.current);
        pollingRef.current = null;
      }
      stopVibration();
    };
  }, [isAuthed, checkForIncomingCalls, stopVibration]);

  return (
    <IncomingCallContext.Provider value={{ incomingCall, pendingCall, handlePendingCall }}>
      {children}
    </IncomingCallContext.Provider>
  );
}

export function useIncomingCall() {
  const context = useContext(IncomingCallContext);
  if (!context) {
    throw new Error('useIncomingCall must be used within IncomingCallProvider');
  }
  return context;
}
