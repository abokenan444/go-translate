import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
  Animated,
  Vibration,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { Audio } from 'expo-av';
import { useNavigation, CommonActions } from '@react-navigation/native';
import { http } from '../api/http';

interface CallState {
  status: 'connecting' | 'ringing' | 'connected' | 'ended';
  duration: number;
  sessionId: string | null;
  myLanguage: string;
  theirLanguage: string;
}

export default function CallSessionScreen({ route }: any) {
  const navigation = useNavigation<any>();
  const { contactId, contactName, publicId } = route?.params ?? {};

  // Safe navigation back - handles case when there's no screen to go back to
  const safeGoBack = useCallback(() => {
    if (navigation.canGoBack()) {
      navigation.goBack();
    } else {
      navigation.dispatch(
        CommonActions.reset({
          index: 0,
          routes: [{ name: 'Tabs' }],
        })
      );
    }
  }, [navigation]);

  const [callState, setCallState] = useState<CallState>({
    status: 'connecting',
    duration: 0,
    sessionId: publicId || null,
    myLanguage: 'en',
    theirLanguage: 'ar',
  });
  const [isRecording, setIsRecording] = useState(false);
  const [isMuted, setIsMuted] = useState(false);
  const [isSpeakerOn, setIsSpeakerOn] = useState(true);
  const [processing, setProcessing] = useState(false);
  const [callHistoryId, setCallHistoryId] = useState<number | null>(null);

  const recordingRef = useRef<Audio.Recording | null>(null);
  const soundRef = useRef<Audio.Sound | null>(null);
  const durationInterval = useRef<ReturnType<typeof setInterval> | null>(null);
  const pulseAnim = useRef(new Animated.Value(1)).current;

  // Initialize audio
  useEffect(() => {
    const initAudio = async () => {
      try {
        await Audio.setAudioModeAsync({
          allowsRecordingIOS: true,
          playsInSilentModeIOS: true,
          staysActiveInBackground: true,
          shouldDuckAndroid: true,
          playThroughEarpieceAndroid: !isSpeakerOn,
        });
      } catch (err) {
        console.error('Audio init error:', err);
      }
    };
    initAudio();
    return () => {
      if (recordingRef.current) {
        recordingRef.current.stopAndUnloadAsync();
      }
      if (soundRef.current) {
        soundRef.current.unloadAsync();
      }
    };
  }, []);

  // Join or create session
  useEffect(() => {
    const setupCall = async () => {
      console.log('[CallSession] setupCall - publicId:', publicId, 'contactId:', contactId);
      try {
        if (publicId) {
          // Joining existing session
          console.log('[CallSession] Joining existing session:', publicId);
          const res = await http.post(`/mobile/realtime/sessions/${publicId}/participants/join`, {
            send_language: callState.myLanguage,
            receive_language: callState.theirLanguage,
          });
          console.log('[CallSession] Joined session successfully');
          setCallState((s) => ({ ...s, status: 'connected', sessionId: publicId }));
        } else if (contactId) {
          // Creating new call session
          const res = await http.post('/mobile/realtime/sessions', {
            type: 'call',
            contact_id: contactId,
            source_language: callState.myLanguage,
            target_language: callState.theirLanguage,
          });
          setCallState((s) => ({
            ...s,
            status: 'ringing',
            sessionId: res.data.session?.public_id,
          }));
          // Simulate connection after 2s (in real app, wait for receiver to join)
          setTimeout(() => {
            setCallState((s) => ({ ...s, status: 'connected' }));
          }, 2000);
        }
      } catch (err: any) {
        console.error('Setup call error:', err);
        Alert.alert('Error', err.response?.data?.message || 'Failed to connect call');
        safeGoBack();
      }
    };
    setupCall();
  }, [publicId, contactId]);

  // Duration timer
  useEffect(() => {
    if (callState.status === 'connected') {
      durationInterval.current = setInterval(() => {
        setCallState((s) => ({ ...s, duration: s.duration + 1 }));
      }, 1000);
    }
    return () => {
      if (durationInterval.current) {
        clearInterval(durationInterval.current);
      }
    };
  }, [callState.status]);

  // Pulse animation for recording
  useEffect(() => {
    if (isRecording) {
      Animated.loop(
        Animated.sequence([
          Animated.timing(pulseAnim, { toValue: 1.2, duration: 500, useNativeDriver: true }),
          Animated.timing(pulseAnim, { toValue: 1, duration: 500, useNativeDriver: true }),
        ])
      ).start();
    } else {
      pulseAnim.setValue(1);
    }
  }, [isRecording]);

  const formatDuration = (seconds: number) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
  };

  const startRecording = async () => {
    try {
      if (isMuted) return;
      
      const { granted } = await Audio.requestPermissionsAsync();
      if (!granted) {
        Alert.alert('Permission Denied', 'Microphone access is required for calls.');
        return;
      }

      await Audio.setAudioModeAsync({
        allowsRecordingIOS: true,
        playsInSilentModeIOS: true,
      });

      const recording = new Audio.Recording();
      await recording.prepareToRecordAsync({
        android: {
          extension: '.m4a',
          outputFormat: Audio.AndroidOutputFormat.MPEG_4,
          audioEncoder: Audio.AndroidAudioEncoder.AAC,
          sampleRate: 16000,
          numberOfChannels: 1,
          bitRate: 64000,
        },
        ios: {
          extension: '.m4a',
          outputFormat: Audio.IOSOutputFormat.MPEG4AAC,
          audioQuality: Audio.IOSAudioQuality.MEDIUM,
          sampleRate: 16000,
          numberOfChannels: 1,
          bitRate: 64000,
        },
        web: { mimeType: 'audio/webm', bitsPerSecond: 64000 },
      });

      await recording.startAsync();
      recordingRef.current = recording;
      setIsRecording(true);
      Vibration.vibrate(50);
    } catch (err) {
      console.error('Start recording error:', err);
    }
  };

  const stopRecording = async () => {
    try {
      if (!recordingRef.current) return;

      setIsRecording(false);
      await recordingRef.current.stopAndUnloadAsync();
      const uri = recordingRef.current.getURI();
      recordingRef.current = null;
      Vibration.vibrate(50);

      if (uri && callState.sessionId) {
        await processAndSendAudio(uri);
      }
    } catch (err) {
      console.error('Stop recording error:', err);
    }
  };

  const processAndSendAudio = async (uri: string) => {
    if (!callState.sessionId) return;
    setProcessing(true);

    try {
      // Create form data with audio
      const formData = new FormData();
      formData.append('audio', {
        uri,
        type: 'audio/m4a',
        name: 'recording.m4a',
      } as any);

      // Send to backend for transcription + translation
      const res = await http.post(
        `/mobile/realtime/sessions/${callState.sessionId}/turn`,
        formData,
        { headers: { 'Content-Type': 'multipart/form-data' } }
      );

      // Play translated audio if available
      if (res.data.translated_audio_url) {
        await playTranslatedAudio(res.data.translated_audio_url);
      }
    } catch (err: any) {
      console.error('Process audio error:', err);
    } finally {
      setProcessing(false);
    }
  };

  const playTranslatedAudio = async (url: string) => {
    try {
      if (soundRef.current) {
        await soundRef.current.unloadAsync();
      }
      const { sound } = await Audio.Sound.createAsync({ uri: url });
      soundRef.current = sound;
      await sound.playAsync();
    } catch (err) {
      console.error('Play audio error:', err);
    }
  };

  const handleEndCall = async () => {
    try {
      if (callState.sessionId) {
        const res = await http.post(`/mobile/realtime/sessions/${callState.sessionId}/end`);
        // Store call history ID for cost sharing
        if (res.data.call_history_id) {
          setCallHistoryId(res.data.call_history_id);
        }
      }
    } catch (err) {
      console.error('End call error:', err);
    }
    setCallState((s) => ({ ...s, status: 'ended' }));
    
    // Show cost sharing option if call was long enough
    const durationMinutes = Math.ceil(callState.duration / 60);
    if (durationMinutes > 0 && callHistoryId) {
      Alert.alert(
        'مشاركة التكلفة 💰',
        `مدة المكالمة: ${durationMinutes} دقيقة\n\nهل تريد طلب مشاركة التكلفة مع الطرف الآخر؟ (كل طرف يدفع ${Math.ceil(durationMinutes / 2)} دقيقة)`,
        [
          { text: 'لا، سأدفع الكل', style: 'cancel', onPress: () => safeGoBack() },
          { 
            text: 'نعم، طلب المشاركة', 
            onPress: async () => {
              try {
                await http.post(`/mobile/calls/${callHistoryId}/request-cost-share`);
                Alert.alert('✅ تم الإرسال', 'تم إرسال طلب مشاركة التكلفة للطرف الآخر');
              } catch (err: any) {
                Alert.alert('خطأ', err.response?.data?.message || 'فشل إرسال الطلب');
              }
              safeGoBack();
            }
          },
        ]
      );
    } else {
      safeGoBack();
    }
  };

  const toggleMute = () => setIsMuted(!isMuted);
  
  const toggleSpeaker = async () => {
    const newValue = !isSpeakerOn;
    setIsSpeakerOn(newValue);
    await Audio.setAudioModeAsync({
      allowsRecordingIOS: true,
      playsInSilentModeIOS: true,
      playThroughEarpieceAndroid: !newValue,
    });
  };

  const getStatusText = () => {
    switch (callState.status) {
      case 'connecting': return 'Connecting...';
      case 'ringing': return 'Ringing...';
      case 'connected': return formatDuration(callState.duration);
      case 'ended': return 'Call Ended';
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      {/* Background gradient effect */}
      <View style={styles.bgGradient} />

      {/* Contact Info */}
      <View style={styles.contactSection}>
        <View style={styles.avatarLarge}>
          <Text style={styles.avatarLargeText}>
            {(contactName || 'Guest').slice(0, 2).toUpperCase()}
          </Text>
        </View>
        <Text style={styles.contactName}>{contactName || 'Voice Call'}</Text>
        <Text style={styles.callStatus}>{getStatusText()}</Text>
      </View>

      {/* Language Info */}
      <View style={styles.languageBar}>
        <View style={styles.languageItem}>
          <Text style={styles.languageLabel}>You speak</Text>
          <Text style={styles.languageValue}>{callState.myLanguage.toUpperCase()}</Text>
        </View>
        <Ionicons name="swap-horizontal" size={20} color="#9CA3AF" />
        <View style={styles.languageItem}>
          <Text style={styles.languageLabel}>They hear</Text>
          <Text style={styles.languageValue}>{callState.theirLanguage.toUpperCase()}</Text>
        </View>
      </View>

      {/* Processing Indicator */}
      {processing && (
        <View style={styles.processingBar}>
          <ActivityIndicator size="small" color="#6366F1" />
          <Text style={styles.processingText}>Translating...</Text>
        </View>
      )}

      {/* Push-to-Talk */}
      {callState.status === 'connected' && (
        <View style={styles.pttSection}>
          <Text style={styles.pttHint}>
            {isRecording ? 'Release to send' : 'Hold to speak'}
          </Text>
          <Animated.View style={[styles.pttButtonOuter, { transform: [{ scale: pulseAnim }] }]}>
            <TouchableOpacity
              style={[styles.pttButton, isRecording && styles.pttButtonActive, isMuted && styles.pttButtonMuted]}
              onPressIn={startRecording}
              onPressOut={stopRecording}
              disabled={isMuted}
              activeOpacity={0.8}
            >
              <Ionicons
                name={isMuted ? 'mic-off' : 'mic'}
                size={48}
                color={isMuted ? '#9CA3AF' : '#fff'}
              />
            </TouchableOpacity>
          </Animated.View>
        </View>
      )}

      {/* Loading state */}
      {callState.status === 'connecting' && (
        <View style={styles.loadingSection}>
          <ActivityIndicator size="large" color="#fff" />
        </View>
      )}

      {/* Control Buttons */}
      <View style={styles.controls}>
        <TouchableOpacity style={styles.controlBtn} onPress={toggleMute}>
          <Ionicons name={isMuted ? 'mic-off' : 'mic'} size={24} color="#fff" />
          <Text style={styles.controlLabel}>{isMuted ? 'Unmute' : 'Mute'}</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.endCallBtn} onPress={handleEndCall}>
          <Ionicons name="call" size={32} color="#fff" style={{ transform: [{ rotate: '135deg' }] }} />
        </TouchableOpacity>

        <TouchableOpacity style={styles.controlBtn} onPress={toggleSpeaker}>
          <Ionicons name={isSpeakerOn ? 'volume-high' : 'volume-low'} size={24} color="#fff" />
          <Text style={styles.controlLabel}>{isSpeakerOn ? 'Speaker' : 'Earpiece'}</Text>
        </TouchableOpacity>
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#1F2937' },
  bgGradient: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    height: '50%',
    backgroundColor: '#4F46E5',
    opacity: 0.3,
  },
  contactSection: { alignItems: 'center', paddingTop: 60, paddingBottom: 20 },
  avatarLarge: {
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: '#6366F1',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 16,
  },
  avatarLargeText: { color: '#fff', fontSize: 36, fontWeight: '600' },
  contactName: { fontSize: 28, fontWeight: '700', color: '#fff' },
  callStatus: { fontSize: 16, color: 'rgba(255,255,255,0.7)', marginTop: 8 },
  languageBar: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'rgba(255,255,255,0.1)',
    marginHorizontal: 32,
    borderRadius: 12,
    paddingVertical: 12,
    paddingHorizontal: 20,
    gap: 16,
  },
  languageItem: { alignItems: 'center' },
  languageLabel: { fontSize: 12, color: 'rgba(255,255,255,0.6)' },
  languageValue: { fontSize: 16, fontWeight: '600', color: '#fff', marginTop: 4 },
  processingBar: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'rgba(99,102,241,0.2)',
    marginHorizontal: 32,
    marginTop: 16,
    borderRadius: 8,
    paddingVertical: 8,
    gap: 8,
  },
  processingText: { color: '#A5B4FC', fontSize: 14 },
  pttSection: { flex: 1, alignItems: 'center', justifyContent: 'center' },
  pttHint: { color: 'rgba(255,255,255,0.6)', fontSize: 14, marginBottom: 20 },
  pttButtonOuter: { borderRadius: 80, padding: 8, backgroundColor: 'rgba(99,102,241,0.3)' },
  pttButton: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: '#6366F1',
    alignItems: 'center',
    justifyContent: 'center',
  },
  pttButtonActive: { backgroundColor: '#10B981' },
  pttButtonMuted: { backgroundColor: '#4B5563' },
  loadingSection: { flex: 1, alignItems: 'center', justifyContent: 'center' },
  controls: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    paddingVertical: 30,
    paddingHorizontal: 20,
    backgroundColor: 'rgba(0,0,0,0.3)',
  },
  controlBtn: { alignItems: 'center', gap: 6 },
  controlLabel: { color: 'rgba(255,255,255,0.7)', fontSize: 12 },
  endCallBtn: {
    width: 70,
    height: 70,
    borderRadius: 35,
    backgroundColor: '#EF4444',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
