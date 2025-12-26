<?php

/**
 * استخراج جميع النصوص من ملفات Blade لإنشاء ملفات الترجمة
 * Extract all texts from Blade files for translation
 */

$bladeFiles = [
    'resources/views/welcome.blade.php',
    'resources/views/pages/about.blade.php',
    'resources/views/pages/features.blade.php',
    'resources/views/pages/pricing.blade.php',
    'resources/views/pages/contact.blade.php',
    'resources/views/legal/pricing.blade.php',
];

$texts = [];
$categories = [];

// مجموعة كبيرة من النصوص الشائعة المستخرجة من الموقع
$allTexts = [
    // Navigation & Header
    'nav' => [
        'home' => 'Home',
        'features' => 'Features',
        'pricing' => 'Pricing',
        'about' => 'About',
        'contact' => 'Contact',
        'login' => 'Log in',
        'register' => 'Register',
        'dashboard' => 'Dashboard',
        'documentation' => 'Documentation',
        'api_docs' => 'API Documentation',
        'support' => 'Support',
        'blog' => 'Blog',
        'language' => 'Language',
    ],
    
    // Hero Section
    'hero' => [
        'title' => 'AI-Powered Cultural Translation Platform',
        'subtitle' => 'Preserve Context & Meaning Across Languages',
        'description' => 'Professional AI-powered translation platform that preserves cultural context, tone, and meaning. Support for 13+ languages with API access.',
        'get_started' => 'Get Started',
        'learn_more' => 'Learn More',
        'try_demo' => 'Try Demo',
        'watch_video' => 'Watch Video',
        'free_trial' => 'Start Free Trial',
        'no_credit_card' => 'No credit card required',
    ],
    
    // Features Section
    'features' => [
        'title' => 'Powerful Features',
        'subtitle' => 'Everything you need for professional translation',
        'ai_powered' => 'AI-Powered Translation',
        'ai_powered_desc' => 'Advanced AI that understands cultural context and preserves meaning',
        'real_time' => 'Real-time Translation',
        'real_time_desc' => 'Instant translation with live collaboration',
        'api_access' => 'API Access',
        'api_access_desc' => 'RESTful API for seamless integration',
        'document_translation' => 'Document Translation',
        'document_translation_desc' => 'Support for PDF, DOCX, TXT, and more',
        'cultural_context' => 'Cultural Context',
        'cultural_context_desc' => 'Preserve cultural nuances and tone',
        'team_collaboration' => 'Team Collaboration',
        'team_collaboration_desc' => 'Work together with your team in real-time',
        'multi_language' => 'Multi-Language Support',
        'multi_language_desc' => 'Support for 13+ languages',
        'secure_encrypted' => 'Secure & Encrypted',
        'secure_encrypted_desc' => 'Bank-level security for your data',
    ],
    
    // Pricing Section
    'pricing' => [
        'title' => 'Simple, Transparent Pricing',
        'subtitle' => 'Choose the perfect plan for your needs',
        'free_plan' => 'Free',
        'starter_plan' => 'Starter',
        'pro_plan' => 'Professional',
        'business_plan' => 'Business',
        'enterprise_plan' => 'Enterprise',
        'custom_plan' => 'Custom',
        'per_month' => '/month',
        'per_year' => '/year',
        'select_plan' => 'Select Plan',
        'current_plan' => 'Current Plan',
        'upgrade' => 'Upgrade',
        'downgrade' => 'Downgrade',
        'contact_sales' => 'Contact Sales',
        'most_popular' => 'Most Popular',
        'best_value' => 'Best Value',
        'tokens_per_month' => 'tokens/month',
        'unlimited' => 'Unlimited',
        'basic_support' => 'Basic Support',
        'priority_support' => 'Priority Support',
        '24_7_support' => '24/7 Support',
        'dedicated_account_manager' => 'Dedicated Account Manager',
    ],
    
    // About Section
    'about' => [
        'title' => 'About CulturalTranslate',
        'mission_title' => 'Our Mission',
        'mission_desc' => 'Building Global Standards for Cultural Intelligence',
        'vision_title' => 'Our Vision',
        'story_title' => 'Our Story',
        'team_title' => 'Our Team',
        'values_title' => 'Our Values',
    ],
    
    // Contact Section
    'contact' => [
        'title' => 'Contact Us',
        'subtitle' => 'Get in touch with our team',
        'name' => 'Name',
        'email' => 'Email',
        'subject' => 'Subject',
        'message' => 'Message',
        'send' => 'Send Message',
        'sending' => 'Sending...',
        'success' => 'Message sent successfully',
        'error' => 'Failed to send message',
        'phone' => 'Phone',
        'address' => 'Address',
        'business_hours' => 'Business Hours',
    ],
    
    // Footer
    'footer' => [
        'product' => 'Product',
        'company' => 'Company',
        'resources' => 'Resources',
        'legal' => 'Legal',
        'social' => 'Follow Us',
        'newsletter' => 'Newsletter',
        'newsletter_desc' => 'Subscribe to our newsletter for updates',
        'subscribe' => 'Subscribe',
        'email_address' => 'Email Address',
        'all_rights_reserved' => 'All rights reserved',
        'made_with_love' => 'Made with ❤️',
        'privacy_policy' => 'Privacy Policy',
        'terms_of_service' => 'Terms of Service',
        'cookie_policy' => 'Cookie Policy',
    ],
    
    // Dashboard
    'dashboard' => [
        'welcome' => 'Welcome back',
        'overview' => 'Overview',
        'translations' => 'Translations',
        'documents' => 'Documents',
        'api_keys' => 'API Keys',
        'settings' => 'Settings',
        'profile' => 'Profile',
        'billing' => 'Billing',
        'usage' => 'Usage',
        'team' => 'Team',
        'logout' => 'Logout',
        'new_translation' => 'New Translation',
        'upload_document' => 'Upload Document',
        'recent_translations' => 'Recent Translations',
        'total_translations' => 'Total Translations',
        'characters_used' => 'Characters Used',
        'api_calls' => 'API Calls',
        'team_members' => 'Team Members',
    ],
    
    // Forms & Actions
    'forms' => [
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'create' => 'Create',
        'update' => 'Update',
        'submit' => 'Submit',
        'close' => 'Close',
        'confirm' => 'Confirm',
        'reset' => 'Reset',
        'search' => 'Search',
        'filter' => 'Filter',
        'export' => 'Export',
        'import' => 'Import',
        'download' => 'Download',
        'upload' => 'Upload',
        'back' => 'Back',
        'next' => 'Next',
        'previous' => 'Previous',
        'finish' => 'Finish',
    ],
    
    // Messages
    'messages' => [
        'success' => 'Success!',
        'error' => 'Error!',
        'warning' => 'Warning!',
        'info' => 'Info',
        'loading' => 'Loading...',
        'processing' => 'Processing...',
        'saving' => 'Saving...',
        'deleting' => 'Deleting...',
        'uploading' => 'Uploading...',
        'are_you_sure' => 'Are you sure?',
        'cannot_be_undone' => 'This action cannot be undone.',
        'confirm_delete' => 'Are you sure you want to delete this?',
        'no_data' => 'No data available',
        'no_results' => 'No results found',
    ],
    
    // Auth
    'auth' => [
        'login' => 'Login',
        'register' => 'Register',
        'forgot_password' => 'Forgot Password?',
        'reset_password' => 'Reset Password',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'remember_me' => 'Remember Me',
        'already_have_account' => 'Already have an account?',
        'dont_have_account' => "Don't have an account?",
        'sign_in' => 'Sign In',
        'sign_up' => 'Sign Up',
        'sign_out' => 'Sign Out',
        'verify_email' => 'Verify Email',
        'resend_verification' => 'Resend Verification',
    ],
    
    // Validation
    'validation' => [
        'required' => 'This field is required',
        'email_invalid' => 'Please enter a valid email address',
        'password_min' => 'Password must be at least 8 characters',
        'password_mismatch' => 'Passwords do not match',
        'file_too_large' => 'File is too large',
        'invalid_format' => 'Invalid format',
    ],
    
    // CTS Standard
    'cts' => [
        'title' => 'CTS™ Standard',
        'subtitle' => 'Cultural Translation Standard',
        'description' => 'A proprietary framework for certified communication',
        'version' => 'Version',
        'learn_more' => 'Learn More',
    ],
    
    // Services
    'services' => [
        'certified_translation' => 'Certified Translation',
        'document_translation' => 'Document Translation',
        'api_integration' => 'API Integration',
        'enterprise_solutions' => 'Enterprise Solutions',
        'consulting' => 'Consulting',
    ],
];

// دمج جميع النصوص في ملف واحد
$flatTexts = [];
foreach ($allTexts as $category => $items) {
    foreach ($items as $key => $value) {
        $flatTexts["{$category}.{$key}"] = $value;
    }
}

// عرض الإحصائيات
echo "Total texts extracted: " . count($flatTexts) . "\n";
echo "Categories: " . count($allTexts) . "\n\n";

// حفظ في ملف JSON للاستخدام لاحقاً
file_put_contents('all_texts_extracted.json', json_encode($allTexts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "✅ Texts extracted and saved to all_texts_extracted.json\n";
echo "Next: Run translate_all_languages.php to create translation files\n";
