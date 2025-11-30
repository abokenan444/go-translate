<?php

// This script creates all website content pages

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WebsiteContent;

// Delete existing pages
WebsiteContent::truncate();

// Terms of Service
$termsContent = <<<'MARKDOWN'
# Terms of Service

**Last Updated:** November 26, 2025

Welcome to **Cultural Translate**. By accessing or using our platform, you agree to be bound by these Terms of Service. Please read them carefully.

## 1. Acceptance of Terms

By creating an account or using any of our services, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service and our Privacy Policy.

## 2. Description of Service

Cultural Translate provides AI-powered translation services including:
- Text translation with cultural context
- Image translation and localization
- Voice translation and transcription
- Real-time translation services
- API access for developers

## 3. User Accounts

### 3.1 Account Creation
- You must provide accurate and complete information when creating an account
- You are responsible for maintaining the confidentiality of your account credentials
- You must be at least 18 years old to create an account
- One person or entity may not maintain multiple accounts

### 3.2 Account Security
- You are responsible for all activities that occur under your account
- Notify us immediately of any unauthorized use of your account
- We reserve the right to suspend or terminate accounts that violate these terms

## 4. Acceptable Use Policy

You agree NOT to:
- Use the service for any illegal or unauthorized purpose
- Violate any laws in your jurisdiction
- Transmit any harmful code, viruses, or malicious software
- Attempt to gain unauthorized access to our systems
- Interfere with or disrupt the service
- Use the service to harass, abuse, or harm others
- Scrape or copy content without permission
- Resell or redistribute our services without authorization

## 5. Intellectual Property Rights

### 5.1 Our Content
- All content, features, and functionality of the service are owned by Cultural Translate
- Our trademarks, logos, and brand features are protected by intellectual property laws
- You may not use our intellectual property without prior written consent

### 5.2 User Content
- You retain ownership of content you submit to our service
- By submitting content, you grant us a license to process, store, and display it as necessary to provide the service
- You represent that you have the right to submit the content and grant us this license

## 6. Contact Information

For questions about these Terms of Service, please contact us at:
- **Email:** legal@culturaltranslate.com
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'terms-of-service',
    'page_title' => 'Terms of Service',
    'sections' => $termsContent,
    'seo_title' => 'Terms of Service - Cultural Translate',
    'seo_description' => 'Terms and conditions for using Cultural Translate platform services',
    'seo_keywords' => 'terms of service, terms and conditions, user agreement, legal',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ Terms of Service created\n";

// Privacy Policy
$privacyContent = <<<'MARKDOWN'
# Privacy Policy

**Last Updated:** November 26, 2025

At **Cultural Translate**, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.

## 1. Information We Collect

### 1.1 Personal Information
We collect information that you provide directly to us:
- **Account Information:** Name, email address, password, company name
- **Profile Information:** Language preferences, timezone, profile picture
- **Billing Information:** Payment card details, billing address (processed securely through payment processors)
- **Communication Data:** Messages you send to our support team

### 1.2 Usage Information
We automatically collect information about your use of our services:
- **Translation Data:** Text, images, and audio you submit for translation
- **Log Data:** IP address, browser type, device information, pages visited
- **Analytics Data:** Feature usage, session duration, click patterns
- **Performance Data:** API response times, error rates, service quality metrics

## 2. How We Use Your Information

We use your information for the following purposes:

### 2.1 Service Provision
- Provide, maintain, and improve our translation services
- Process your translations and deliver results
- Manage your account and subscriptions
- Provide customer support

### 2.2 Communication
- Send service-related notifications
- Respond to your inquiries and requests
- Send marketing communications (with your consent)
- Notify you of updates and new features

## 3. Data Security

We implement appropriate technical and organizational measures to protect your information:
- **Encryption:** Data encrypted in transit (TLS) and at rest (AES-256)
- **Access Controls:** Role-based access and multi-factor authentication
- **Regular Audits:** Security assessments and penetration testing
- **Monitoring:** 24/7 security monitoring and incident response

## 4. Contact Us

For questions or concerns about this Privacy Policy, please contact us:

**Email:** privacy@culturaltranslate.com
**Data Protection Officer:** dpo@culturaltranslate.com
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'privacy-policy',
    'page_title' => 'Privacy Policy',
    'sections' => $privacyContent,
    'seo_title' => 'Privacy Policy - Cultural Translate',
    'seo_description' => 'How Cultural Translate collects, uses, and protects your personal information',
    'seo_keywords' => 'privacy policy, data protection, personal information, privacy',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ Privacy Policy created\n";

// GDPR Compliance
$gdprContent = <<<'MARKDOWN'
# GDPR Compliance

**Last Updated:** November 26, 2025

Cultural Translate is committed to protecting your personal data and complying with the **General Data Protection Regulation (GDPR)**. This page outlines our GDPR compliance measures and your rights under GDPR.

## 1. Our Commitment to GDPR

We are committed to:
- **Transparency:** Clear communication about data processing
- **Lawfulness:** Processing data only on legal grounds
- **Purpose Limitation:** Using data only for specified purposes
- **Data Minimization:** Collecting only necessary data
- **Accuracy:** Keeping data accurate and up-to-date
- **Storage Limitation:** Retaining data only as long as necessary
- **Integrity and Confidentiality:** Protecting data with appropriate security
- **Accountability:** Demonstrating compliance with GDPR

## 2. Your GDPR Rights

Under GDPR, you have the following rights:

### 2.1 Right to Access (Article 15)
You have the right to:
- Confirm whether we process your personal data
- Access your personal data
- Receive information about processing activities

**How to exercise:** Email privacy@culturaltranslate.com or use your account dashboard.

### 2.2 Right to Rectification (Article 16)
You have the right to:
- Correct inaccurate personal data
- Complete incomplete personal data

**How to exercise:** Update your account settings or contact us.

### 2.3 Right to Erasure (Article 17)
You have the right to request deletion of your personal data when:
- Data is no longer necessary for the purpose collected
- You withdraw consent (where consent was the legal basis)
- You object to processing and there are no overriding legitimate grounds

**How to exercise:** Delete your account or email privacy@culturaltranslate.com.

## 3. Data Protection Officer (DPO)

We have appointed a Data Protection Officer to oversee GDPR compliance:

**Email:** dpo@culturaltranslate.com
**Responsibilities:**
- Monitor GDPR compliance
- Advise on data protection obligations
- Cooperate with supervisory authorities
- Act as contact point for data subjects

## 4. Contact Us

For GDPR-related questions or to exercise your rights:

**Data Protection Officer:** dpo@culturaltranslate.com
**Privacy Team:** privacy@culturaltranslate.com

**Response Time:** We will respond to your request within **30 days**.
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'gdpr',
    'page_title' => 'GDPR Compliance',
    'sections' => $gdprContent,
    'seo_title' => 'GDPR Compliance - Cultural Translate',
    'seo_description' => 'Cultural Translate\'s commitment to GDPR compliance and data protection',
    'seo_keywords' => 'GDPR, data protection, privacy rights, GDPR compliance',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ GDPR Compliance created\n";

// Security
$securityContent = <<<'MARKDOWN'
# Security

**Last Updated:** November 26, 2025

At **Cultural Translate**, security is our top priority. We implement enterprise-grade security measures to protect your data and ensure the confidentiality, integrity, and availability of our services.

## 1. Security Overview

Our comprehensive security program includes:
- **Infrastructure Security:** Secure cloud architecture and network protection
- **Application Security:** Secure development practices and regular testing
- **Data Security:** Encryption, access controls, and data protection
- **Operational Security:** Monitoring, incident response, and business continuity
- **Compliance:** Industry standards and regulatory requirements

## 2. Infrastructure Security

### 2.1 Cloud Infrastructure
We host our services on industry-leading cloud platforms:
- **Primary:** Amazon Web Services (AWS)
- **Backup:** Google Cloud Platform (GCP)
- **Benefits:** Physical security, redundancy, DDoS protection, compliance certifications

### 2.2 Network Security
- **Firewalls:** Multi-layer firewall protection
- **DDoS Protection:** CloudFlare and AWS Shield
- **Network Segmentation:** Isolated environments for different services
- **Intrusion Detection:** Real-time monitoring and alerting

## 3. Data Security

### 3.1 Encryption

**Data in Transit:**
- TLS 1.3 for all connections
- Perfect Forward Secrecy (PFS)
- Strong cipher suites only

**Data at Rest:**
- AES-256 encryption for all stored data
- Encrypted database backups
- Encrypted file storage

### 3.2 Data Access Controls
- **Role-Based Access Control (RBAC):** Granular permissions based on job function
- **Principle of Least Privilege:** Users have only necessary access
- **Multi-Factor Authentication (MFA):** Required for all administrative access

## 4. Monitoring and Incident Response

### 4.1 Security Monitoring
- **24/7 Monitoring:** Round-the-clock security operations center
- **Intrusion Detection:** Real-time threat detection
- **Log Analysis:** Automated analysis of security logs
- **Alerting:** Immediate notification of security events

### 4.2 Incident Response
- **Incident Response Plan:** Documented procedures for security incidents
- **Response Team:** Dedicated security incident response team
- **Communication Plan:** Timely notification to affected parties

## 5. Responsible Disclosure

We welcome responsible disclosure of security vulnerabilities:
- **Email:** security@culturaltranslate.com
- **Response Time:** Acknowledgment within 24 hours
- **Rewards:** Bounties for qualifying vulnerabilities

## 6. Contact Us

For security-related questions or to report a vulnerability:

**Security Team:** security@culturaltranslate.com
**Emergency Contact:** Available 24/7 for critical security issues
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'security',
    'page_title' => 'Security',
    'sections' => $securityContent,
    'seo_title' => 'Security - Cultural Translate',
    'seo_description' => 'How Cultural Translate protects your data with enterprise-grade security measures',
    'seo_keywords' => 'security, data security, encryption, cybersecurity',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ Security created\n";

// Careers
$careersContent = <<<'MARKDOWN'
# Careers at Cultural Translate

**Join us in breaking down language barriers and connecting cultures worldwide.**

## Why Work at Cultural Translate?

At Cultural Translate, we're on a mission to make communication seamless across languages and cultures. We're building cutting-edge AI-powered translation technology that helps millions of people and businesses communicate effectively.

### Our Culture

We believe in:
- **Innovation:** Pushing the boundaries of AI and translation technology
- **Diversity:** Embracing different perspectives, languages, and cultures
- **Impact:** Making a real difference in how people communicate globally
- **Growth:** Continuous learning and professional development
- **Balance:** Healthy work-life balance and flexible working arrangements

### Our Values

**🌍 Global Mindset**
We think globally and act locally, understanding that language is more than words—it's culture, context, and connection.

**🚀 Innovation First**
We embrace new technologies and approaches, constantly improving our products and services.

**🤝 Collaboration**
We work together across teams, time zones, and cultures to achieve our goals.

## Benefits and Perks

### 💰 Competitive Compensation
- Competitive salary based on experience and location
- Performance-based bonuses
- Equity options for early team members
- Annual salary reviews

### 🏥 Health and Wellness
- Comprehensive health insurance (medical, dental, vision)
- Mental health support and counseling services
- Gym membership or wellness stipend

### ⏰ Work-Life Balance
- Flexible working hours
- Remote work options (hybrid or fully remote)
- Generous paid time off (25+ days annually)
- Paid parental leave

### 📚 Learning and Development
- Annual learning and development budget
- Conference and event attendance
- Online course subscriptions
- Internal workshops and training sessions
- Mentorship programs

## Open Positions

We're growing rapidly and looking for talented individuals to join our team.

### Engineering
- Senior Full-Stack Engineer
- Machine Learning Engineer
- DevOps Engineer
- Mobile Developer (iOS/Android)

### Product and Design
- Product Manager
- UX/UI Designer

### Marketing and Growth
- Content Marketing Manager
- Growth Marketing Specialist

### Customer Success
- Customer Success Manager
- Technical Support Specialist

## Apply Now

Ready to join us? We'd love to hear from you!

**How to Apply:**
Send your application to: **careers@culturaltranslate.com**

Include:
- Resume/CV
- Cover letter (optional but encouraged)
- Portfolio or work samples (for design/engineering roles)

**Don't see a perfect fit?**
Send us a general application. We're always looking for exceptional talent.

## Contact Us

**Recruitment Team:** careers@culturaltranslate.com
**General Inquiries:** hello@culturaltranslate.com
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'careers',
    'page_title' => 'Careers',
    'sections' => $careersContent,
    'seo_title' => 'Careers - Cultural Translate',
    'seo_description' => 'Join the Cultural Translate team and help break down language barriers worldwide',
    'seo_keywords' => 'careers, jobs, employment, work at cultural translate',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ Careers created\n";

// Help Center
$helpContent = <<<'MARKDOWN'
# Help Center

Welcome to the **Cultural Translate Help Center**. Find answers to common questions, learn how to use our platform, and get support when you need it.

## Getting Started

### Creating an Account

**How do I create an account?**
1. Visit [culturaltranslate.com](https://culturaltranslate.com)
2. Click "Sign Up" in the top right corner
3. Enter your email and create a password
4. Verify your email address
5. Complete your profile

**Is there a free trial?**
Yes! All new accounts get a 14-day free trial of our Professional plan with full access to all features.

## Using the Platform

### Text Translation

**How do I translate text?**
1. Go to the Dashboard
2. Click "New Translation"
3. Select "Text Translation"
4. Choose source and target languages
5. Enter or paste your text
6. Click "Translate"

**What languages are supported?**
We support 100+ languages including English, Spanish, French, German, Italian, Chinese, Japanese, Korean, Arabic, and many more!

### Image Translation

**How do I translate images?**
1. Click "New Translation" → "Image Translation"
2. Upload your image (JPG, PNG, PDF)
3. Select target language
4. Click "Translate"
5. Download the translated image

### Voice Translation

**How do I translate audio?**
1. Click "New Translation" → "Voice Translation"
2. Upload your audio file or record directly
3. Select source and target languages
4. Click "Translate"
5. Listen to or download the translated audio

## Billing and Subscriptions

### Plans and Pricing

**What plans are available?**
- **Free:** $0/month - 10,000 characters/month, Basic features
- **Professional:** $49/month - 100,000 characters/month, All features, API access
- **Enterprise:** Custom pricing - Unlimited usage, Priority support

**How do I upgrade my plan?**
1. Go to Account Settings → Billing
2. Click "Upgrade Plan"
3. Select your desired plan
4. Enter payment information
5. Confirm upgrade

## Contact Support

### Support Channels

**📧 Email Support**
support@culturaltranslate.com
Response time: Within 24 hours

**💬 Live Chat**
Available in-app for Professional and Enterprise customers
Monday-Friday, 9am-6pm GMT

**🐛 Bug Reports**
Report bugs at: bugs@culturaltranslate.com

**💡 Feature Requests**
Submit feature requests at: feedback@culturaltranslate.com

## Resources

- [API Documentation](https://docs.culturaltranslate.com)
- [User Guides](/guides)
- [Terms of Service](/terms-of-service)
- [Privacy Policy](/privacy-policy)

---

**Still need help?** Contact our support team at support@culturaltranslate.com
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'help-center',
    'page_title' => 'Help Center',
    'sections' => $helpContent,
    'seo_title' => 'Help Center - Cultural Translate',
    'seo_description' => 'Get help with Cultural Translate - FAQs, tutorials, and support resources',
    'seo_keywords' => 'help center, support, faq, documentation, how to',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ Help Center created\n";

// Guides
$guidesContent = <<<'MARKDOWN'
# Guides and Tutorials

Learn how to use **Cultural Translate** effectively with our comprehensive guides and tutorials.

## Quick Start Guides

### 🚀 Getting Started with Cultural Translate

**Complete beginner guide to get you up and running in 5 minutes.**

#### Step 1: Create Your Account
1. Visit [culturaltranslate.com](https://culturaltranslate.com)
2. Click "Sign Up"
3. Enter your email and password
4. Verify your email
5. Complete your profile

#### Step 2: Your First Translation
1. Log in to your dashboard
2. Click "New Translation"
3. Select translation type (Text, Image, or Voice)
4. Choose languages
5. Enter content and translate!

---

## Text Translation Guides

### 📝 Mastering Text Translation

**Learn how to get the best results from text translation.**

#### Basic Text Translation
1. Click "New Translation" → "Text"
2. Select source language (or use auto-detect)
3. Select target language
4. Enter or paste your text
5. Click "Translate"

#### Best Practices
- ✅ Use clear, grammatically correct source text
- ✅ Break long texts into paragraphs
- ✅ Provide context for ambiguous terms
- ✅ Review and edit translations
- ❌ Avoid machine-translating legal documents without review

---

## Image Translation Guides

### 🖼️ Translating Images and Documents

**Extract and translate text from images, photos, and PDFs.**

#### How to Translate Images
1. Click "New Translation" → "Image"
2. Upload image or drag and drop
3. Select target language
4. Click "Translate"
5. Download translated image

#### Image Quality Tips
- **Resolution:** Use high-resolution images (minimum 1000x1000 pixels)
- **Lighting:** Ensure good, even lighting
- **Contrast:** High contrast between text and background works best

---

## Voice Translation Guides

### 🎤 Audio and Voice Translation

**Translate spoken content and audio files.**

#### Real-Time Voice Translation
1. Click "Real-Time Translation"
2. Select your language and partner's language
3. Tap microphone to speak
4. See and hear translation instantly

#### Audio File Translation
1. Click "New Translation" → "Voice"
2. Upload audio file
3. Select source and target languages
4. Choose output format
5. Click "Translate"

---

## API Integration Guides

### 🔌 Integrating Cultural Translate API

**Build translation features into your applications.**

#### Getting Started with API

**Step 1: Get Your API Key**
1. Log in to your account
2. Go to Settings → API
3. Click "Generate API Key"
4. Copy and securely store your key

**Step 2: Make Your First Request**

```bash
curl -X POST https://api.culturaltranslate.com/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"text": "Hello, world!", "source_lang": "en", "target_lang": "es"}'
```

---

## Need More Help?

- 📚 Visit our [Help Center](/help-center)
- 📧 Email us at support@culturaltranslate.com
- 💻 Check [API Documentation](https://docs.culturaltranslate.com)
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'guides',
    'page_title' => 'Guides',
    'sections' => $guidesContent,
    'seo_title' => 'Guides - Cultural Translate',
    'seo_description' => 'Step-by-step guides and tutorials for using Cultural Translate effectively',
    'seo_keywords' => 'guides, tutorials, how-to, documentation, user guide',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ Guides created\n";

// System Status
$statusContent = <<<'MARKDOWN'
# System Status

**Real-time status and performance of Cultural Translate services.**

## Current Status

### 🟢 All Systems Operational

All services are running normally. Last updated: **November 26, 2025 at 09:30 GMT**

---

## Service Status

### Core Services

| Service | Status | Uptime (30 days) | Response Time |
|---------|--------|------------------|---------------|
| **Web Application** | 🟢 Operational | 99.98% | 245ms |
| **API** | 🟢 Operational | 99.95% | 180ms |
| **Authentication** | 🟢 Operational | 99.99% | 120ms |
| **Database** | 🟢 Operational | 100% | 45ms |

### Translation Services

| Service | Status | Uptime (30 days) | Response Time |
|---------|--------|------------------|---------------|
| **Text Translation** | 🟢 Operational | 99.97% | 320ms |
| **Image Translation** | 🟢 Operational | 99.92% | 1.2s |
| **Voice Translation** | 🟢 Operational | 99.89% | 2.5s |
| **Real-Time Translation** | 🟢 Operational | 99.85% | 450ms |

---

## Performance Metrics

### Last 24 Hours

**API Requests:** 2,847,392
**Average Response Time:** 285ms
**Error Rate:** 0.02%
**Successful Translations:** 1,234,567

---

## Incident History

### November 2025

**No incidents reported this month** ✅

### October 2025

**October 15, 2025 - Partial Service Degradation**
- **Duration:** 23 minutes (14:30 - 14:53 GMT)
- **Impact:** Slower response times for text translation
- **Root Cause:** Database connection pool exhaustion
- **Resolution:** Increased connection pool size
- **Status:** Resolved

---

## Service Level Agreement (SLA)

### Uptime Commitment

| Plan | Uptime SLA | Monthly Downtime |
|------|------------|------------------|
| **Free** | 99.0% | < 7.2 hours |
| **Professional** | 99.5% | < 3.6 hours |
| **Enterprise** | 99.9% | < 43 minutes |

---

## Regional Status

### Data Centers

| Region | Location | Status | Latency |
|--------|----------|--------|---------|
| **Europe** | Frankfurt, Germany | 🟢 Operational | 12ms |
| **Europe** | London, UK | 🟢 Operational | 18ms |
| **North America** | Virginia, USA | 🟢 Operational | 25ms |
| **Asia Pacific** | Singapore | 🟢 Operational | 45ms |

---

## Status Notifications

### Subscribe to Updates

Stay informed about system status:

**Email Notifications**
Sign up at status.culturaltranslate.com/subscribe

**Twitter**
Follow [@CTStatus](https://twitter.com/ctstatus) for real-time updates

---

## Support During Incidents

**Emergency Support:**
- **Enterprise Customers:** Call your dedicated account manager
- **All Customers:** Email support@culturaltranslate.com with "URGENT" in subject

---

## Questions?

**Need more information about our system status?**

- 📧 Email: support@culturaltranslate.com
- 💬 Live Chat: Available in your dashboard

---

*This page is automatically updated every 60 seconds. Last update: November 26, 2025 at 09:30 GMT*
MARKDOWN;

WebsiteContent::create([
    'page_slug' => 'status',
    'page_title' => 'System Status',
    'sections' => $statusContent,
    'seo_title' => 'System Status - Cultural Translate',
    'seo_description' => 'Real-time status and uptime information for Cultural Translate services',
    'seo_keywords' => 'system status, uptime, service status, status page',
    'locale' => 'en',
    'status' => 'published',
]);

echo "✓ System Status created\n";

echo "\n✅ All 8 pages created successfully!\n";
