import { I18n } from 'i18n-js';
import * as SecureStore from 'expo-secure-store';

const LANGUAGE_KEY = 'ct_app_language';

const translations = {
  en: {
    // Common
    cancel: 'Cancel',
    save: 'Save',
    delete: 'Delete',
    edit: 'Edit',
    close: 'Close',
    loading: 'Loading...',
    error: 'Error',
    success: 'Success',
    confirm: 'Confirm',
    phone: 'Phone',
    
    // Auth
    login: 'Login',
    loginFailed: 'Login failed',
    email: 'Email',
    password: 'Password',
    register: 'Register',
    registerFailed: 'Registration failed',
    name: 'Name',
    pleaseWait: 'Please wait…',
    goRegister: 'Create a new account',
    goLogin: 'I already have an account',
    
    // Tabs
    calls: 'Calls',
    contacts: 'Contacts',
    notifications: 'Notifications',
    wallet: 'Wallet',
    settings: 'Settings',
    
    // Calls
    recentCalls: 'Recent Calls',
    noCalls: 'No calls yet',
    noCallsSubtext: 'Your call history will appear here',
    startNewCall: 'Start a New Call',
    selectContactToCall: 'Select a contact to call',
    missed: 'Missed',
    declined: 'Declined',
    outgoing: 'Outgoing',
    incoming: 'Incoming',
    yesterday: 'Yesterday',
    joinCallTitle: 'Join Call',
    enterCallCode: 'Enter call code',
    join: 'Join',
    
    // Contacts
    searchContacts: 'Search contacts...',
    addContact: 'Add Contact',
    noContacts: 'No contacts',
    noContactsSubtext: 'Add contacts to start calling',
    notRegistered: 'Not Registered',
    sendInvite: 'Send Invite',
    deleteContact: 'Delete Contact',
    removeContactConfirm: 'Remove from contacts?',
    inviteSent: 'Invite Sent!',
    inviteSentMessage: 'An invitation has been sent. You\'ll get 5 free minutes when they sign up!',
    notRegisteredMessage: 'is not using the app yet. Would you like to invite them?',
    noContactInfo: 'No contact info',
    
    // Wallet
    availableBalance: 'Available Balance',
    minutes: 'minutes',
    min: 'min',
    addMinutes: 'Add Minutes',
    buyPackages: 'Buy Packages',
    autoTopup: 'Auto Top-up',
    inviteEarn: 'Invite & Earn',
    transactionHistory: 'Transaction History',
    noTransactions: 'No transactions yet',
    popular: 'POPULAR',
    selectPackage: 'Select a Package',
    confirmPurchase: 'Confirm Purchase',
    buyMinutesConfirm: 'Buy {minutes} minutes for ${price}?',
    buy: 'Buy',
    topup: 'Top-up',
    usage: 'Usage',
    refund: 'Refund',
    bonus: 'Bonus',
    referral: 'Referral',
    
    // Auto Top-up
    autoTopupSettings: 'Auto Top-up Settings',
    enableAutoTopup: 'Enable Auto Top-up',
    whenBalanceFalls: 'When balance falls below',
    topupWith: 'Top up with',
    
    // Invite
    inviteAndEarn: 'Invite & Earn',
    yourInviteCode: 'Your Invite Code',
    shareInvite: 'Share Invite',
    invitesSent: 'Invites Sent',
    successfulSignups: 'Successful Signups',
    minutesEarned: 'Minutes Earned',
    earnMinutesInvite: 'Earn 5 free minutes for every friend who joins!',
    
    // Settings
    profile: 'Profile',
    languagePrefs: 'Language Preferences',
    iSpeak: 'I speak',
    translateTo: 'Translate to',
    appLanguage: 'App language',
    about: 'About',
    version: 'Version',
    logout: 'Logout',
    logoutConfirm: 'Are you sure you want to logout?',
    selectLanguage: 'Select Your Language',
    saved: 'Saved',
    languageChanged: 'Language changed successfully',
    
    // Notifications
    markAllRead: 'Mark all read',
    noNotifications: 'No notifications',
    noNotificationsSubtext: "You're all caught up!",
    accept: 'Accept',
    reject: 'Reject',
    acceptShare: 'Accept Share',
    justNow: 'Just now',
    minutesAgo: '{n}m ago',
    hoursAgo: '{n}h ago',
    daysAgo: '{n}d ago',
    
    // Incoming Call
    incoming_call: 'Incoming Call',
    incoming_call_from: 'Call from',
    answer: 'Answer',
    
    // Call Session
    startCall: 'Start Call',
    joinCall: 'Join Call',
    sessionId: 'Session ID',
    sendLanguage: 'Send language',
    receiveLanguage: 'Receive language',
    connecting: 'Connecting...',
    ringing: 'Ringing...',
    connected: 'Connected',
    callEnded: 'Call Ended',
    holdToSpeak: 'Hold to speak',
    releaseToSend: 'Release to send',
    mute: 'Mute',
    unmute: 'Unmute',
    speaker: 'Speaker',
    earpiece: 'Earpiece',
    youSpeak: 'You speak',
    theyHear: 'They hear',
    translating: 'Translating...',
  },
  ar: {
    // Common
    cancel: 'إلغاء',
    save: 'حفظ',
    delete: 'حذف',
    edit: 'تعديل',
    close: 'إغلاق',
    loading: 'جاري التحميل...',
    error: 'خطأ',
    success: 'تم بنجاح',
    confirm: 'تأكيد',
    phone: 'الهاتف',
    
    // Auth
    login: 'تسجيل الدخول',
    loginFailed: 'فشل تسجيل الدخول',
    email: 'البريد الإلكتروني',
    password: 'كلمة المرور',
    register: 'إنشاء حساب',
    registerFailed: 'فشل إنشاء الحساب',
    name: 'الاسم',
    pleaseWait: 'يرجى الانتظار…',
    goRegister: 'إنشاء حساب جديد',
    goLogin: 'لدي حساب بالفعل',
    
    // Tabs
    calls: 'المكالمات',
    contacts: 'جهات الاتصال',
    notifications: 'الإشعارات',
    wallet: 'المحفظة',
    settings: 'الإعدادات',
    
    // Calls
    recentCalls: 'المكالمات الأخيرة',
    noCalls: 'لا توجد مكالمات',
    noCallsSubtext: 'سيظهر سجل مكالماتك هنا',
    startNewCall: 'بدء مكالمة جديدة',
    selectContactToCall: 'اختر جهة اتصال للاتصال',
    missed: 'فائتة',
    declined: 'مرفوضة',
    outgoing: 'صادرة',
    incoming: 'واردة',
    yesterday: 'أمس',
    joinCallTitle: 'الانضمام لمكالمة',
    enterCallCode: 'أدخل رمز المكالمة',
    join: 'انضمام',
    
    // Contacts
    searchContacts: 'بحث في جهات الاتصال...',
    addContact: 'إضافة جهة اتصال',
    noContacts: 'لا توجد جهات اتصال',
    noContactsSubtext: 'أضف جهات اتصال للبدء بالاتصال',
    notRegistered: 'غير مسجل',
    sendInvite: 'إرسال دعوة',
    deleteContact: 'حذف جهة الاتصال',
    removeContactConfirm: 'إزالة من جهات الاتصال؟',
    inviteSent: 'تم إرسال الدعوة!',
    inviteSentMessage: 'تم إرسال الدعوة. ستحصل على 5 دقائق مجانية عند التسجيل!',
    notRegisteredMessage: 'لا يستخدم التطبيق بعد. هل تريد دعوته؟',
    noContactInfo: 'لا توجد معلومات اتصال',
    
    // Wallet
    availableBalance: 'الرصيد المتاح',
    minutes: 'دقيقة',
    min: 'د',
    addMinutes: 'إضافة دقائق',
    buyPackages: 'شراء باقات',
    autoTopup: 'شحن تلقائي',
    inviteEarn: 'ادعُ واكسب',
    transactionHistory: 'سجل المعاملات',
    noTransactions: 'لا توجد معاملات',
    popular: 'الأكثر شيوعاً',
    selectPackage: 'اختر باقة',
    confirmPurchase: 'تأكيد الشراء',
    buyMinutesConfirm: 'شراء {minutes} دقيقة بـ ${price}؟',
    buy: 'شراء',
    topup: 'شحن',
    usage: 'استخدام',
    refund: 'استرداد',
    bonus: 'مكافأة',
    referral: 'إحالة',
    
    // Auto Top-up
    autoTopupSettings: 'إعدادات الشحن التلقائي',
    enableAutoTopup: 'تفعيل الشحن التلقائي',
    whenBalanceFalls: 'عندما ينخفض الرصيد عن',
    topupWith: 'شحن بـ',
    
    // Invite
    inviteAndEarn: 'ادعُ واكسب',
    yourInviteCode: 'رمز الدعوة الخاص بك',
    shareInvite: 'مشاركة الدعوة',
    invitesSent: 'الدعوات المرسلة',
    successfulSignups: 'التسجيلات الناجحة',
    minutesEarned: 'الدقائق المكتسبة',
    earnMinutesInvite: 'اكسب 5 دقائق مجانية لكل صديق ينضم!',
    
    // Settings
    profile: 'الملف الشخصي',
    languagePrefs: 'تفضيلات اللغة',
    iSpeak: 'أتحدث',
    translateTo: 'ترجم إلى',
    appLanguage: 'لغة التطبيق',
    about: 'حول',
    version: 'الإصدار',
    logout: 'تسجيل الخروج',
    logoutConfirm: 'هل أنت متأكد من تسجيل الخروج؟',
    selectLanguage: 'اختر لغتك',
    saved: 'تم الحفظ',
    languageChanged: 'تم تغيير اللغة بنجاح',
    
    // Notifications
    markAllRead: 'تحديد الكل كمقروء',
    noNotifications: 'لا توجد إشعارات',
    noNotificationsSubtext: 'أنت على اطلاع!',
    accept: 'قبول',
    reject: 'رفض',
    acceptShare: 'قبول المشاركة',
    justNow: 'الآن',
    minutesAgo: 'منذ {n} دقيقة',
    hoursAgo: 'منذ {n} ساعة',
    daysAgo: 'منذ {n} يوم',
    
    // Incoming Call
    incoming_call: 'مكالمة واردة',
    incoming_call_from: 'اتصال من',
    answer: 'رد',
    
    // Call Session
    startCall: 'بدء مكالمة',
    joinCall: 'الانضمام لمكالمة',
    sessionId: 'معرّف الجلسة',
    sendLanguage: 'لغة الإرسال',
    receiveLanguage: 'لغة الاستقبال',
    connecting: 'جاري الاتصال...',
    ringing: 'جاري الرنين...',
    connected: 'متصل',
    callEnded: 'انتهت المكالمة',
    holdToSpeak: 'اضغط للتحدث',
    releaseToSend: 'أفلت للإرسال',
    mute: 'كتم',
    unmute: 'إلغاء الكتم',
    speaker: 'مكبر الصوت',
    earpiece: 'السماعة',
    youSpeak: 'أنت تتحدث',
    theyHear: 'يسمعون',
    translating: 'جاري الترجمة...',
  },
  // French
  fr: {
    cancel: 'Annuler', save: 'Enregistrer', delete: 'Supprimer', edit: 'Modifier', close: 'Fermer',
    loading: 'Chargement...', error: 'Erreur', success: 'Succès', confirm: 'Confirmer', phone: 'Téléphone',
    login: 'Connexion', email: 'E-mail', password: 'Mot de passe', register: "S'inscrire", name: 'Nom',
    calls: 'Appels', contacts: 'Contacts', notifications: 'Notifications', wallet: 'Portefeuille', settings: 'Paramètres',
    recentCalls: 'Appels récents', noCalls: 'Pas encore d\'appels', startNewCall: 'Nouvel appel',
    searchContacts: 'Rechercher...', addContact: 'Ajouter', noContacts: 'Aucun contact',
    availableBalance: 'Solde disponible', minutes: 'minutes', min: 'min', addMinutes: 'Ajouter des minutes',
    buyPackages: 'Acheter', autoTopup: 'Recharge auto', inviteEarn: 'Inviter et gagner',
    transactionHistory: 'Historique', noTransactions: 'Aucune transaction', popular: 'POPULAIRE',
    profile: 'Profil', languagePrefs: 'Préférences de langue', iSpeak: 'Je parle', translateTo: 'Traduire en',
    appLanguage: 'Langue de l\'app', about: 'À propos', version: 'Version', logout: 'Déconnexion',
    markAllRead: 'Tout marquer comme lu', noNotifications: 'Aucune notification', accept: 'Accepter', reject: 'Refuser',
    startCall: 'Démarrer', joinCall: 'Rejoindre', connecting: 'Connexion...', connected: 'Connecté', callEnded: 'Appel terminé',
    mute: 'Muet', unmute: 'Son', speaker: 'Haut-parleur', missed: 'Manqué', outgoing: 'Sortant', incoming: 'Entrant',
    yesterday: 'Hier', joinCallTitle: 'Rejoindre un appel', join: 'Rejoindre', selectPackage: 'Choisir un forfait',
    autoTopupSettings: 'Paramètres recharge auto', enableAutoTopup: 'Activer recharge auto',
    inviteAndEarn: 'Inviter et gagner', yourInviteCode: 'Votre code', shareInvite: 'Partager',
    acceptShare: 'Accepter le partage', justNow: 'À l\'instant',
  },
  // Spanish
  es: {
    cancel: 'Cancelar', save: 'Guardar', delete: 'Eliminar', edit: 'Editar', close: 'Cerrar',
    loading: 'Cargando...', error: 'Error', success: 'Éxito', confirm: 'Confirmar', phone: 'Teléfono',
    login: 'Iniciar sesión', email: 'Correo', password: 'Contraseña', register: 'Registrarse', name: 'Nombre',
    calls: 'Llamadas', contacts: 'Contactos', notifications: 'Notificaciones', wallet: 'Cartera', settings: 'Ajustes',
    recentCalls: 'Llamadas recientes', noCalls: 'Sin llamadas', startNewCall: 'Nueva llamada',
    searchContacts: 'Buscar...', addContact: 'Añadir', noContacts: 'Sin contactos',
    availableBalance: 'Saldo disponible', minutes: 'minutos', min: 'min', addMinutes: 'Añadir minutos',
    buyPackages: 'Comprar', autoTopup: 'Recarga auto', inviteEarn: 'Invitar y ganar',
    transactionHistory: 'Historial', noTransactions: 'Sin transacciones', popular: 'POPULAR',
    profile: 'Perfil', languagePrefs: 'Preferencias de idioma', iSpeak: 'Hablo', translateTo: 'Traducir a',
    appLanguage: 'Idioma de la app', about: 'Acerca de', version: 'Versión', logout: 'Cerrar sesión',
    markAllRead: 'Marcar todo como leído', noNotifications: 'Sin notificaciones', accept: 'Aceptar', reject: 'Rechazar',
    startCall: 'Iniciar', joinCall: 'Unirse', connecting: 'Conectando...', connected: 'Conectado', callEnded: 'Llamada finalizada',
    mute: 'Silenciar', unmute: 'Activar', speaker: 'Altavoz', missed: 'Perdida', outgoing: 'Saliente', incoming: 'Entrante',
    yesterday: 'Ayer', joinCallTitle: 'Unirse a llamada', join: 'Unirse', selectPackage: 'Elegir paquete',
    autoTopupSettings: 'Ajustes recarga auto', enableAutoTopup: 'Activar recarga auto',
    inviteAndEarn: 'Invitar y ganar', yourInviteCode: 'Tu código', shareInvite: 'Compartir',
    acceptShare: 'Aceptar compartir', justNow: 'Ahora mismo',
  },
  // German
  de: {
    cancel: 'Abbrechen', save: 'Speichern', delete: 'Löschen', edit: 'Bearbeiten', close: 'Schließen',
    loading: 'Laden...', error: 'Fehler', success: 'Erfolg', confirm: 'Bestätigen', phone: 'Telefon',
    login: 'Anmelden', email: 'E-Mail', password: 'Passwort', register: 'Registrieren', name: 'Name',
    calls: 'Anrufe', contacts: 'Kontakte', notifications: 'Benachrichtigungen', wallet: 'Geldbörse', settings: 'Einstellungen',
    recentCalls: 'Letzte Anrufe', noCalls: 'Keine Anrufe', startNewCall: 'Neuer Anruf',
    searchContacts: 'Suchen...', addContact: 'Hinzufügen', noContacts: 'Keine Kontakte',
    availableBalance: 'Verfügbares Guthaben', minutes: 'Minuten', min: 'Min', addMinutes: 'Minuten hinzufügen',
    buyPackages: 'Kaufen', autoTopup: 'Auto-Aufladung', inviteEarn: 'Einladen & verdienen',
    transactionHistory: 'Verlauf', noTransactions: 'Keine Transaktionen', popular: 'BELIEBT',
    profile: 'Profil', languagePrefs: 'Spracheinstellungen', iSpeak: 'Ich spreche', translateTo: 'Übersetzen nach',
    appLanguage: 'App-Sprache', about: 'Über', version: 'Version', logout: 'Abmelden',
    markAllRead: 'Alle als gelesen markieren', noNotifications: 'Keine Benachrichtigungen', accept: 'Akzeptieren', reject: 'Ablehnen',
    startCall: 'Starten', joinCall: 'Beitreten', connecting: 'Verbinden...', connected: 'Verbunden', callEnded: 'Anruf beendet',
    mute: 'Stumm', unmute: 'Ton an', speaker: 'Lautsprecher', missed: 'Verpasst', outgoing: 'Ausgehend', incoming: 'Eingehend',
    yesterday: 'Gestern', joinCallTitle: 'Anruf beitreten', join: 'Beitreten', selectPackage: 'Paket wählen',
    autoTopupSettings: 'Auto-Aufladung Einstellungen', enableAutoTopup: 'Auto-Aufladung aktivieren',
    inviteAndEarn: 'Einladen & verdienen', yourInviteCode: 'Dein Code', shareInvite: 'Teilen',
    acceptShare: 'Teilen akzeptieren', justNow: 'Gerade eben',
  },
  // Dutch
  nl: {
    cancel: 'Annuleren', save: 'Opslaan', delete: 'Verwijderen', edit: 'Bewerken', close: 'Sluiten',
    loading: 'Laden...', error: 'Fout', success: 'Gelukt', confirm: 'Bevestigen', phone: 'Telefoon',
    login: 'Inloggen', email: 'E-mail', password: 'Wachtwoord', register: 'Registreren', name: 'Naam',
    calls: 'Oproepen', contacts: 'Contacten', notifications: 'Meldingen', wallet: 'Portemonnee', settings: 'Instellingen',
    recentCalls: 'Recente oproepen', noCalls: 'Geen oproepen', startNewCall: 'Nieuwe oproep',
    searchContacts: 'Zoeken...', addContact: 'Toevoegen', noContacts: 'Geen contacten',
    availableBalance: 'Beschikbaar saldo', minutes: 'minuten', min: 'min', addMinutes: 'Minuten toevoegen',
    buyPackages: 'Kopen', autoTopup: 'Auto opwaarderen', inviteEarn: 'Uitnodigen & verdienen',
    transactionHistory: 'Geschiedenis', noTransactions: 'Geen transacties', popular: 'POPULAIR',
    profile: 'Profiel', languagePrefs: 'Taalvoorkeuren', iSpeak: 'Ik spreek', translateTo: 'Vertalen naar',
    appLanguage: 'App-taal', about: 'Over', version: 'Versie', logout: 'Uitloggen',
    markAllRead: 'Alles als gelezen markeren', noNotifications: 'Geen meldingen', accept: 'Accepteren', reject: 'Weigeren',
    startCall: 'Starten', joinCall: 'Deelnemen', connecting: 'Verbinden...', connected: 'Verbonden', callEnded: 'Oproep beëindigd',
    mute: 'Dempen', unmute: 'Geluid aan', speaker: 'Luidspreker', missed: 'Gemist', outgoing: 'Uitgaand', incoming: 'Inkomend',
    yesterday: 'Gisteren', joinCallTitle: 'Deelnemen aan oproep', join: 'Deelnemen', selectPackage: 'Pakket kiezen',
    autoTopupSettings: 'Auto opwaarderen instellingen', enableAutoTopup: 'Auto opwaarderen inschakelen',
    inviteAndEarn: 'Uitnodigen & verdienen', yourInviteCode: 'Jouw code', shareInvite: 'Delen',
    acceptShare: 'Delen accepteren', justNow: 'Zojuist',
  },
  // Turkish
  tr: {
    cancel: 'İptal', save: 'Kaydet', delete: 'Sil', edit: 'Düzenle', close: 'Kapat',
    loading: 'Yükleniyor...', error: 'Hata', success: 'Başarılı', confirm: 'Onayla', phone: 'Telefon',
    login: 'Giriş', email: 'E-posta', password: 'Şifre', register: 'Kayıt ol', name: 'İsim',
    calls: 'Aramalar', contacts: 'Kişiler', notifications: 'Bildirimler', wallet: 'Cüzdan', settings: 'Ayarlar',
    recentCalls: 'Son aramalar', noCalls: 'Arama yok', startNewCall: 'Yeni arama',
    searchContacts: 'Ara...', addContact: 'Ekle', noContacts: 'Kişi yok',
    availableBalance: 'Mevcut bakiye', minutes: 'dakika', min: 'dk', addMinutes: 'Dakika ekle',
    buyPackages: 'Satın al', autoTopup: 'Otomatik yükleme', inviteEarn: 'Davet et & kazan',
    transactionHistory: 'Geçmiş', noTransactions: 'İşlem yok', popular: 'POPÜLER',
    profile: 'Profil', languagePrefs: 'Dil tercihleri', iSpeak: 'Konuştuğum dil', translateTo: 'Çeviri dili',
    appLanguage: 'Uygulama dili', about: 'Hakkında', version: 'Sürüm', logout: 'Çıkış',
    markAllRead: 'Tümünü okundu işaretle', noNotifications: 'Bildirim yok', accept: 'Kabul et', reject: 'Reddet',
    startCall: 'Başlat', joinCall: 'Katıl', connecting: 'Bağlanıyor...', connected: 'Bağlandı', callEnded: 'Arama bitti',
    mute: 'Sessize al', unmute: 'Sesi aç', speaker: 'Hoparlör', missed: 'Cevapsız', outgoing: 'Giden', incoming: 'Gelen',
    yesterday: 'Dün', joinCallTitle: 'Aramaya katıl', join: 'Katıl', selectPackage: 'Paket seç',
    autoTopupSettings: 'Otomatik yükleme ayarları', enableAutoTopup: 'Otomatik yüklemeyi etkinleştir',
    inviteAndEarn: 'Davet et & kazan', yourInviteCode: 'Davet kodun', shareInvite: 'Paylaş',
    acceptShare: 'Paylaşımı kabul et', justNow: 'Şimdi',
  },
  // Portuguese
  pt: {
    cancel: 'Cancelar', save: 'Salvar', delete: 'Excluir', edit: 'Editar', close: 'Fechar',
    loading: 'Carregando...', error: 'Erro', success: 'Sucesso', confirm: 'Confirmar', phone: 'Telefone',
    login: 'Entrar', email: 'E-mail', password: 'Senha', register: 'Registrar', name: 'Nome',
    calls: 'Chamadas', contacts: 'Contatos', notifications: 'Notificações', wallet: 'Carteira', settings: 'Configurações',
    recentCalls: 'Chamadas recentes', noCalls: 'Sem chamadas', startNewCall: 'Nova chamada',
    searchContacts: 'Pesquisar...', addContact: 'Adicionar', noContacts: 'Sem contatos',
    availableBalance: 'Saldo disponível', minutes: 'minutos', min: 'min', addMinutes: 'Adicionar minutos',
    buyPackages: 'Comprar', autoTopup: 'Recarga automática', inviteEarn: 'Convidar e ganhar',
    transactionHistory: 'Histórico', noTransactions: 'Sem transações', popular: 'POPULAR',
    profile: 'Perfil', languagePrefs: 'Preferências de idioma', iSpeak: 'Eu falo', translateTo: 'Traduzir para',
    appLanguage: 'Idioma do app', about: 'Sobre', version: 'Versão', logout: 'Sair',
    markAllRead: 'Marcar tudo como lido', noNotifications: 'Sem notificações', accept: 'Aceitar', reject: 'Recusar',
    startCall: 'Iniciar', joinCall: 'Entrar', connecting: 'Conectando...', connected: 'Conectado', callEnded: 'Chamada encerrada',
    mute: 'Mudo', unmute: 'Ativar som', speaker: 'Alto-falante', missed: 'Perdida', outgoing: 'Saída', incoming: 'Entrada',
    yesterday: 'Ontem', joinCallTitle: 'Entrar na chamada', join: 'Entrar', selectPackage: 'Escolher pacote',
    autoTopupSettings: 'Config. recarga auto', enableAutoTopup: 'Ativar recarga automática',
    inviteAndEarn: 'Convidar e ganhar', yourInviteCode: 'Seu código', shareInvite: 'Compartilhar',
    acceptShare: 'Aceitar compartilhamento', justNow: 'Agora mesmo',
  },
  // Italian
  it: {
    cancel: 'Annulla', save: 'Salva', delete: 'Elimina', edit: 'Modifica', close: 'Chiudi',
    loading: 'Caricamento...', error: 'Errore', success: 'Successo', confirm: 'Conferma', phone: 'Telefono',
    login: 'Accedi', email: 'E-mail', password: 'Password', register: 'Registrati', name: 'Nome',
    calls: 'Chiamate', contacts: 'Contatti', notifications: 'Notifiche', wallet: 'Portafoglio', settings: 'Impostazioni',
    recentCalls: 'Chiamate recenti', noCalls: 'Nessuna chiamata', startNewCall: 'Nuova chiamata',
    searchContacts: 'Cerca...', addContact: 'Aggiungi', noContacts: 'Nessun contatto',
    availableBalance: 'Saldo disponibile', minutes: 'minuti', min: 'min', addMinutes: 'Aggiungi minuti',
    buyPackages: 'Acquista', autoTopup: 'Ricarica automatica', inviteEarn: 'Invita e guadagna',
    transactionHistory: 'Cronologia', noTransactions: 'Nessuna transazione', popular: 'POPOLARE',
    profile: 'Profilo', languagePrefs: 'Preferenze lingua', iSpeak: 'Parlo', translateTo: 'Traduci in',
    appLanguage: 'Lingua app', about: 'Info', version: 'Versione', logout: 'Esci',
    markAllRead: 'Segna tutto come letto', noNotifications: 'Nessuna notifica', accept: 'Accetta', reject: 'Rifiuta',
    startCall: 'Avvia', joinCall: 'Unisciti', connecting: 'Connessione...', connected: 'Connesso', callEnded: 'Chiamata terminata',
    mute: 'Muto', unmute: 'Attiva audio', speaker: 'Altoparlante', missed: 'Persa', outgoing: 'In uscita', incoming: 'In entrata',
    yesterday: 'Ieri', joinCallTitle: 'Unisciti alla chiamata', join: 'Unisciti', selectPackage: 'Scegli pacchetto',
    autoTopupSettings: 'Impost. ricarica auto', enableAutoTopup: 'Attiva ricarica automatica',
    inviteAndEarn: 'Invita e guadagna', yourInviteCode: 'Il tuo codice', shareInvite: 'Condividi',
    acceptShare: 'Accetta condivisione', justNow: 'Proprio ora',
  },
  // Russian
  ru: {
    cancel: 'Отмена', save: 'Сохранить', delete: 'Удалить', edit: 'Изменить', close: 'Закрыть',
    loading: 'Загрузка...', error: 'Ошибка', success: 'Успешно', confirm: 'Подтвердить', phone: 'Телефон',
    login: 'Вход', email: 'Эл. почта', password: 'Пароль', register: 'Регистрация', name: 'Имя',
    calls: 'Звонки', contacts: 'Контакты', notifications: 'Уведомления', wallet: 'Кошелек', settings: 'Настройки',
    recentCalls: 'Последние звонки', noCalls: 'Нет звонков', startNewCall: 'Новый звонок',
    searchContacts: 'Поиск...', addContact: 'Добавить', noContacts: 'Нет контактов',
    availableBalance: 'Доступный баланс', minutes: 'минут', min: 'мин', addMinutes: 'Добавить минуты',
    buyPackages: 'Купить', autoTopup: 'Автопополнение', inviteEarn: 'Пригласи и заработай',
    transactionHistory: 'История', noTransactions: 'Нет транзакций', popular: 'ПОПУЛЯРНО',
    profile: 'Профиль', languagePrefs: 'Языковые настройки', iSpeak: 'Я говорю', translateTo: 'Перевести на',
    appLanguage: 'Язык приложения', about: 'О приложении', version: 'Версия', logout: 'Выход',
    markAllRead: 'Отметить все как прочитанное', noNotifications: 'Нет уведомлений', accept: 'Принять', reject: 'Отклонить',
    startCall: 'Начать', joinCall: 'Присоединиться', connecting: 'Соединение...', connected: 'Подключено', callEnded: 'Звонок завершен',
    mute: 'Выкл. микрофон', unmute: 'Вкл. микрофон', speaker: 'Динамик', missed: 'Пропущен', outgoing: 'Исходящий', incoming: 'Входящий',
    yesterday: 'Вчера', joinCallTitle: 'Присоединиться к звонку', join: 'Присоединиться', selectPackage: 'Выбрать пакет',
    autoTopupSettings: 'Настройки автопополнения', enableAutoTopup: 'Включить автопополнение',
    inviteAndEarn: 'Пригласи и заработай', yourInviteCode: 'Ваш код', shareInvite: 'Поделиться',
    acceptShare: 'Принять долю', justNow: 'Только что',
  },
  // Chinese (Simplified)
  zh: {
    cancel: '取消', save: '保存', delete: '删除', edit: '编辑', close: '关闭',
    loading: '加载中...', error: '错误', success: '成功', confirm: '确认', phone: '电话',
    login: '登录', email: '邮箱', password: '密码', register: '注册', name: '姓名',
    calls: '通话', contacts: '联系人', notifications: '通知', wallet: '钱包', settings: '设置',
    recentCalls: '最近通话', noCalls: '暂无通话', startNewCall: '新建通话',
    searchContacts: '搜索...', addContact: '添加', noContacts: '暂无联系人',
    availableBalance: '可用余额', minutes: '分钟', min: '分', addMinutes: '添加分钟',
    buyPackages: '购买', autoTopup: '自动充值', inviteEarn: '邀请赚取',
    transactionHistory: '历史记录', noTransactions: '暂无交易', popular: '热门',
    profile: '个人资料', languagePrefs: '语言设置', iSpeak: '我说', translateTo: '翻译成',
    appLanguage: '应用语言', about: '关于', version: '版本', logout: '退出',
    markAllRead: '全部标为已读', noNotifications: '暂无通知', accept: '接受', reject: '拒绝',
    startCall: '开始', joinCall: '加入', connecting: '连接中...', connected: '已连接', callEnded: '通话结束',
    mute: '静音', unmute: '取消静音', speaker: '扬声器', missed: '未接', outgoing: '呼出', incoming: '呼入',
    yesterday: '昨天', joinCallTitle: '加入通话', join: '加入', selectPackage: '选择套餐',
    autoTopupSettings: '自动充值设置', enableAutoTopup: '启用自动充值',
    inviteAndEarn: '邀请赚取', yourInviteCode: '您的邀请码', shareInvite: '分享',
    acceptShare: '接受分摊', justNow: '刚刚',
  },
  // Japanese
  ja: {
    cancel: 'キャンセル', save: '保存', delete: '削除', edit: '編集', close: '閉じる',
    loading: '読み込み中...', error: 'エラー', success: '成功', confirm: '確認', phone: '電話',
    login: 'ログイン', email: 'メール', password: 'パスワード', register: '登録', name: '名前',
    calls: '通話', contacts: '連絡先', notifications: '通知', wallet: 'ウォレット', settings: '設定',
    recentCalls: '最近の通話', noCalls: '通話なし', startNewCall: '新規通話',
    searchContacts: '検索...', addContact: '追加', noContacts: '連絡先なし',
    availableBalance: '利用可能残高', minutes: '分', min: '分', addMinutes: '分を追加',
    buyPackages: '購入', autoTopup: '自動チャージ', inviteEarn: '招待して稼ぐ',
    transactionHistory: '履歴', noTransactions: '取引なし', popular: '人気',
    profile: 'プロフィール', languagePrefs: '言語設定', iSpeak: '話す言語', translateTo: '翻訳先',
    appLanguage: 'アプリの言語', about: '情報', version: 'バージョン', logout: 'ログアウト',
    markAllRead: 'すべて既読にする', noNotifications: '通知なし', accept: '承諾', reject: '拒否',
    startCall: '開始', joinCall: '参加', connecting: '接続中...', connected: '接続済み', callEnded: '通話終了',
    mute: 'ミュート', unmute: 'ミュート解除', speaker: 'スピーカー', missed: '不在着信', outgoing: '発信', incoming: '着信',
    yesterday: '昨日', joinCallTitle: '通話に参加', join: '参加', selectPackage: 'パッケージを選択',
    autoTopupSettings: '自動チャージ設定', enableAutoTopup: '自動チャージを有効にする',
    inviteAndEarn: '招待して稼ぐ', yourInviteCode: '招待コード', shareInvite: '共有',
    acceptShare: '分担を承諾', justNow: 'たった今',
  },
  // Korean
  ko: {
    cancel: '취소', save: '저장', delete: '삭제', edit: '편집', close: '닫기',
    loading: '로딩 중...', error: '오류', success: '성공', confirm: '확인', phone: '전화',
    login: '로그인', email: '이메일', password: '비밀번호', register: '회원가입', name: '이름',
    calls: '통화', contacts: '연락처', notifications: '알림', wallet: '지갑', settings: '설정',
    recentCalls: '최근 통화', noCalls: '통화 없음', startNewCall: '새 통화',
    searchContacts: '검색...', addContact: '추가', noContacts: '연락처 없음',
    availableBalance: '사용 가능 잔액', minutes: '분', min: '분', addMinutes: '분 추가',
    buyPackages: '구매', autoTopup: '자동 충전', inviteEarn: '초대하고 적립',
    transactionHistory: '내역', noTransactions: '거래 없음', popular: '인기',
    profile: '프로필', languagePrefs: '언어 설정', iSpeak: '사용 언어', translateTo: '번역 언어',
    appLanguage: '앱 언어', about: '정보', version: '버전', logout: '로그아웃',
    markAllRead: '모두 읽음으로 표시', noNotifications: '알림 없음', accept: '수락', reject: '거절',
    startCall: '시작', joinCall: '참여', connecting: '연결 중...', connected: '연결됨', callEnded: '통화 종료',
    mute: '음소거', unmute: '음소거 해제', speaker: '스피커', missed: '부재중', outgoing: '발신', incoming: '수신',
    yesterday: '어제', joinCallTitle: '통화 참여', join: '참여', selectPackage: '패키지 선택',
    autoTopupSettings: '자동 충전 설정', enableAutoTopup: '자동 충전 활성화',
    inviteAndEarn: '초대하고 적립', yourInviteCode: '초대 코드', shareInvite: '공유',
    acceptShare: '분담 수락', justNow: '방금',
  },
  // Hindi
  hi: {
    cancel: 'रद्द करें', save: 'सहेजें', delete: 'हटाएं', edit: 'संपादित करें', close: 'बंद करें',
    loading: 'लोड हो रहा है...', error: 'त्रुटि', success: 'सफल', confirm: 'पुष्टि करें', phone: 'फोन',
    login: 'लॉग इन', email: 'ईमेल', password: 'पासवर्ड', register: 'रजिस्टर', name: 'नाम',
    calls: 'कॉल', contacts: 'संपर्क', notifications: 'सूचनाएं', wallet: 'वॉलेट', settings: 'सेटिंग्स',
    recentCalls: 'हाल की कॉल', noCalls: 'कोई कॉल नहीं', startNewCall: 'नई कॉल',
    searchContacts: 'खोजें...', addContact: 'जोड़ें', noContacts: 'कोई संपर्क नहीं',
    availableBalance: 'उपलब्ध शेष', minutes: 'मिनट', min: 'मिनट', addMinutes: 'मिनट जोड़ें',
    buyPackages: 'खरीदें', autoTopup: 'ऑटो टॉप-अप', inviteEarn: 'आमंत्रित करें और कमाएं',
    transactionHistory: 'इतिहास', noTransactions: 'कोई लेनदेन नहीं', popular: 'लोकप्रिय',
    profile: 'प्रोफाइल', languagePrefs: 'भाषा सेटिंग्स', iSpeak: 'मैं बोलता हूं', translateTo: 'अनुवाद करें',
    appLanguage: 'ऐप भाषा', about: 'के बारे में', version: 'संस्करण', logout: 'लॉग आउट',
    markAllRead: 'सभी को पढ़ा हुआ मार्क करें', noNotifications: 'कोई सूचना नहीं', accept: 'स्वीकार करें', reject: 'अस्वीकार करें',
    startCall: 'शुरू करें', joinCall: 'शामिल हों', connecting: 'कनेक्ट हो रहा है...', connected: 'कनेक्टेड', callEnded: 'कॉल समाप्त',
    mute: 'म्यूट', unmute: 'अनम्यूट', speaker: 'स्पीकर', missed: 'छूटी हुई', outgoing: 'आउटगोइंग', incoming: 'इनकमिंग',
    yesterday: 'कल', joinCallTitle: 'कॉल में शामिल हों', join: 'शामिल हों', selectPackage: 'पैकेज चुनें',
    autoTopupSettings: 'ऑटो टॉप-अप सेटिंग्स', enableAutoTopup: 'ऑटो टॉप-अप सक्षम करें',
    inviteAndEarn: 'आमंत्रित करें और कमाएं', yourInviteCode: 'आपका कोड', shareInvite: 'शेयर करें',
    acceptShare: 'शेयर स्वीकार करें', justNow: 'अभी',
  },
};

export const i18n = new I18n(translations);

i18n.enableFallback = true;
i18n.defaultLocale = 'en';

// Save language to storage
export async function saveLanguage(lang: string): Promise<void> {
  try {
    await SecureStore.setItemAsync(LANGUAGE_KEY, lang);
    i18n.locale = lang;
  } catch (e) {
    console.error('Failed to save language:', e);
  }
}

// Load saved language from storage
export async function loadSavedLanguage(): Promise<string> {
  try {
    const lang = await SecureStore.getItemAsync(LANGUAGE_KEY);
    if (lang) {
      i18n.locale = lang;
      return lang;
    }
  } catch (e) {
    console.error('Failed to load language:', e);
  }
  return 'en';
}

// Change language and save
export async function changeLanguage(lang: string): Promise<void> {
  i18n.locale = lang;
  await saveLanguage(lang);
}

// Get current language
export function getCurrentLanguage(): string {
  return i18n.locale;
}

// Check if RTL
export function isRTL(): boolean {
  const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
  return rtlLanguages.includes(i18n.locale);
}

export function t(key: string) {
  return i18n.t(key);
}
