import React, { useRef, useEffect } from 'react';
import { NavigationContainer, NavigationContainerRef, useNavigation } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { Ionicons } from '@expo/vector-icons';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { View, ActivityIndicator } from 'react-native';

import LoginScreen from './src/screens/LoginScreen';
import RegisterScreen from './src/screens/RegisterScreen';
import CallsScreen from './src/screens/CallsScreen';
import CallSessionScreen from './src/screens/CallSessionScreen';
import ContactsScreen from './src/screens/ContactsScreen';
import WalletScreen from './src/screens/WalletScreen';
import SettingsScreen from './src/screens/SettingsScreen';
import NotificationsScreen from './src/screens/NotificationsScreen';
import { AuthProvider, useAuth } from './src/auth/AuthContext';
import { LanguageProvider, useLanguage } from './src/context/LanguageContext';
import { IncomingCallProvider, useIncomingCall } from './src/context/IncomingCallContext';

type AuthStackParamList = {
  Login: undefined;
  Register: undefined;
};

type AppStackParamList = {
  Tabs: undefined;
  CallSession: { publicId?: string; contactId?: number; contactName?: string };
};

const AuthStack = createNativeStackNavigator<AuthStackParamList>();
const AppStack = createNativeStackNavigator<AppStackParamList>();
const Tabs = createBottomTabNavigator();

function TabsNavigator() {
  const { t } = useLanguage();

  return (
    <Tabs.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarLabel: t(route.name.toLowerCase()),
        tabBarIcon: ({ focused, color, size }) => {
          let iconName: keyof typeof Ionicons.glyphMap;
          switch (route.name) {
            case 'Calls':
              iconName = focused ? 'call' : 'call-outline';
              break;
            case 'Contacts':
              iconName = focused ? 'people' : 'people-outline';
              break;
            case 'Notifications':
              iconName = focused ? 'notifications' : 'notifications-outline';
              break;
            case 'Wallet':
              iconName = focused ? 'wallet' : 'wallet-outline';
              break;
            case 'Settings':
              iconName = focused ? 'settings' : 'settings-outline';
              break;
            default:
              iconName = 'ellipse';
          }
          return <Ionicons name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: '#6366F1',
        tabBarInactiveTintColor: '#9CA3AF',
        tabBarStyle: {
          backgroundColor: '#fff',
          borderTopColor: '#E5E7EB',
          paddingTop: 8,
          height: 85,
        },
        tabBarLabelStyle: {
          fontSize: 12,
          fontWeight: '500',
          marginTop: 4,
        },
      })}
    >
      <Tabs.Screen name="Calls" component={CallsScreen} />
      <Tabs.Screen name="Contacts" component={ContactsScreen} />
      <Tabs.Screen name="Notifications" component={NotificationsScreen} />
      <Tabs.Screen name="Wallet" component={WalletScreen} />
      <Tabs.Screen name="Settings" component={SettingsScreen} />
    </Tabs.Navigator>
  );
}

export default function App() {
  const navigationRef = useRef<NavigationContainerRef<any>>(null);
  
  return (
    <SafeAreaProvider>
      <LanguageProvider>
        <AuthProvider>
          <IncomingCallProvider>
            <NavigationContainer ref={navigationRef}>
              <NavigationListener navigationRef={navigationRef} />
              <RootNavigator />
            </NavigationContainer>
          </IncomingCallProvider>
        </AuthProvider>
      </LanguageProvider>
    </SafeAreaProvider>
  );
}

// Component to listen for pending calls and navigate
function NavigationListener({ navigationRef }: { navigationRef: React.RefObject<NavigationContainerRef<any> | null> }) {
  const { pendingCall, handlePendingCall } = useIncomingCall();

  useEffect(() => {
    if (pendingCall && navigationRef.current) {
      console.log('[NavigationListener] pendingCall detected, navigating to CallSession:', pendingCall);
      navigationRef.current.navigate('CallSession', {
        publicId: pendingCall.session_id,
        contactName: pendingCall.caller_name,
      });
      handlePendingCall();
    }
  }, [pendingCall, navigationRef, handlePendingCall]);

  return null;
}

function RootNavigator() {
  const { isAuthed, setAuthed, bootstrapping } = useAuth();

  if (bootstrapping) {
    return null;
  }

  return isAuthed ? (
    <AppStack.Navigator>
      <AppStack.Screen name="Tabs" component={TabsNavigator} options={{ headerShown: false }} />
      <AppStack.Screen 
        name="CallSession" 
        component={CallSessionScreen} 
        options={{ 
          headerShown: false,
          presentation: 'fullScreenModal',
          animation: 'fade',
        }} 
      />
    </AppStack.Navigator>
  ) : (
    <AuthStack.Navigator screenOptions={{ headerShown: false }}>
      <AuthStack.Screen name="Login">
        {(props: any) => <LoginScreen {...props} onAuthed={() => setAuthed(true)} />}
      </AuthStack.Screen>
      <AuthStack.Screen name="Register">
        {(props: any) => <RegisterScreen {...props} onAuthed={() => setAuthed(true)} />}
      </AuthStack.Screen>
    </AuthStack.Navigator>
  );
}
