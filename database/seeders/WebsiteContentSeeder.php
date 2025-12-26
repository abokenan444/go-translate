<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = Carbon::now();
        
        $pages = [
            [
                'page_slug' => 'privacy-policy',
                'page_title' => 'Privacy Policy',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'Privacy Policy',
                        'subtitle' => 'Your privacy is important to us. This Privacy Policy explains how CulturalTranslate collects, uses, and protects your personal information.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Information We Collect',
                        'content' => 'We collect information that you provide directly to us, including your name, email address, company information, and translation preferences. We also automatically collect certain information about your device and how you interact with our services, such as IP address, browser type, operating system, and usage patterns. This information helps us improve our translation services and provide you with a better user experience. We use cookies and similar tracking technologies to collect this information and enhance your experience on our platform.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'How We Use Your Information',
                        'content' => 'We use the information we collect to provide, maintain, and improve our translation services. This includes processing your translation requests, personalizing your experience, communicating with you about our services, analyzing usage patterns to enhance our AI algorithms, and protecting against fraud and unauthorized access. We may also use your information to send you marketing communications, but you can opt out of these at any time. Our cultural translation engine uses anonymized data to improve accuracy and cultural sensitivity across different languages and regions.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Data Security',
                        'content' => 'We implement industry-standard security measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. This includes encryption of data in transit and at rest, regular security audits, access controls and authentication mechanisms, secure data centers with physical security measures, and regular backup and disaster recovery procedures. All translation data is processed through secure APIs and stored using enterprise-grade encryption. We continuously monitor our systems for potential vulnerabilities and threats to ensure the highest level of data protection.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Data Retention',
                        'content' => 'We retain your personal information for as long as necessary to provide our services and fulfill the purposes outlined in this Privacy Policy. Translation history is stored for 90 days by default, after which it is automatically deleted unless you choose to save it. Account information is retained until you request deletion or close your account. We may retain certain information for longer periods if required by law or for legitimate business purposes, such as fraud prevention and regulatory compliance. You have the right to request deletion of your data at any time through your account settings or by contacting our support team.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Your Rights',
                        'content' => 'You have certain rights regarding your personal information under data protection laws, including the General Data Protection Regulation (GDPR) and California Consumer Privacy Act (CCPA). These rights include: the right to access and obtain a copy of your personal data, the right to rectify inaccurate or incomplete information, the right to delete your personal information (right to be forgotten), the right to restrict or object to processing of your data, the right to data portability, and the right to withdraw consent at any time. To exercise these rights, please contact us at privacy@culturaltranslate.com. We will respond to your request within 30 days.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Third-Party Services',
                        'content' => 'We may use third-party service providers to help us operate our business and provide our services. These providers may have access to your personal information only to perform specific tasks on our behalf and are obligated to protect your information and use it only for the purposes for which it was disclosed. Our third-party partners include cloud hosting providers, payment processors, analytics services, and customer support platforms. We carefully vet all third-party providers to ensure they meet our security and privacy standards. We do not sell your personal information to third parties.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'International Data Transfers',
                        'content' => 'As a global translation service, we may transfer your personal information to countries other than your own, including the United States and European Union member states. We ensure that such transfers comply with applicable data protection laws and implement appropriate safeguards, such as Standard Contractual Clauses approved by the European Commission. When we transfer data internationally, we ensure that the receiving country provides an adequate level of data protection or that appropriate contractual arrangements are in place to protect your information.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Contact Us',
                        'content' => 'If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us at: Email: privacy@culturaltranslate.com, Address: CulturalTranslate Privacy Team, 123 Translation Avenue, San Francisco, CA 94102, United States. We take all privacy inquiries seriously and will respond to your questions as quickly as possible. Last updated: December 3, 2025.',
                    ],
                ]),
                'seo_title' => 'Privacy Policy - CulturalTranslate',
                'seo_description' => 'Learn how CulturalTranslate protects your privacy and handles your personal information. Read our comprehensive privacy policy.',
                'seo_keywords' => 'privacy policy, data protection, GDPR, personal information, data security',
                'status' => 'published',
                'locale' => 'en',
            ],
            [
                'page_slug' => 'terms-of-service',
                'page_title' => 'Terms of Service',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'Terms of Service',
                        'subtitle' => 'Please read these Terms of Service carefully before using CulturalTranslate services.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Acceptance of Terms',
                        'content' => 'By accessing or using CulturalTranslate services, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any part of these terms, you may not use our services. These terms apply to all users of the service, including browsers, vendors, customers, merchants, and content contributors. We reserve the right to update, change, or replace any part of these Terms of Service by posting updates on our website. It is your responsibility to check this page periodically for changes. Your continued use of the service following the posting of any changes constitutes acceptance of those changes.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Service Description',
                        'content' => 'CulturalTranslate provides AI-powered translation services with cultural context adaptation. Our services include text translation, document translation, API access for developers, real-time translation capabilities, cultural tone adjustment, and industry-specific translation templates. We strive to provide accurate and culturally appropriate translations, but we do not guarantee that translations will be error-free or suitable for all purposes. The quality of translations may vary depending on language pairs, content complexity, and cultural context. Users are responsible for reviewing and verifying translations before use in critical applications.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'User Accounts',
                        'content' => 'To access certain features of our service, you must create an account. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You agree to provide accurate, current, and complete information during registration and to update such information to keep it accurate. You must immediately notify us of any unauthorized use of your account or any other breach of security. We reserve the right to suspend or terminate accounts that violate these terms or engage in fraudulent or abusive behavior. You may not use another person\'s account without permission or create multiple accounts to circumvent restrictions.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Acceptable Use',
                        'content' => 'You agree not to use our services for any unlawful purpose or in any way that could damage, disable, or impair our services. Prohibited activities include: translating content that violates intellectual property rights, uploading malicious code or viruses, attempting to gain unauthorized access to our systems, using automated tools to access the service without permission, translating illegal, harmful, or offensive content, and reselling or redistributing our services without authorization. We reserve the right to investigate and take appropriate legal action against anyone who violates these provisions, including removing content and terminating accounts.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Intellectual Property',
                        'content' => 'The CulturalTranslate service and its original content, features, and functionality are owned by CulturalTranslate and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws. You retain ownership of content you submit for translation, but you grant us a limited license to process, store, and translate your content as necessary to provide our services. Our AI models, algorithms, cultural databases, and translation engines are proprietary and protected by intellectual property laws. You may not copy, modify, distribute, or reverse engineer any part of our services without explicit written permission.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Payment and Subscription',
                        'content' => 'Certain features of our service require payment. By subscribing to a paid plan, you agree to pay all fees associated with your subscription. Subscription fees are billed in advance on a recurring basis (monthly or annually) and are non-refundable except as required by law. We reserve the right to change our pricing at any time, but price changes will not affect your current billing cycle. If you fail to pay fees when due, we may suspend or terminate your access to paid features. You are responsible for all taxes associated with your subscription. Auto-renewal can be disabled in your account settings at any time before the renewal date.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Limitation of Liability',
                        'content' => 'CulturalTranslate shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use the service. This includes damages for loss of profits, data, or other intangible losses, even if we have been advised of the possibility of such damages. Our total liability for any claims arising from or related to these terms or our services shall not exceed the amount you paid us in the twelve months preceding the claim. Some jurisdictions do not allow the exclusion of certain warranties or limitations on liability, so some of the above limitations may not apply to you.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Termination',
                        'content' => 'We may terminate or suspend your account and access to our services immediately, without prior notice or liability, for any reason, including if you breach these Terms of Service. Upon termination, your right to use the service will immediately cease. You may also terminate your account at any time by contacting our support team or through your account settings. All provisions of these Terms which by their nature should survive termination shall survive, including ownership provisions, warranty disclaimers, indemnity, and limitations of liability. Termination does not relieve you of any obligations to pay outstanding fees.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Contact Information',
                        'content' => 'For questions about these Terms of Service, please contact us at: Email: legal@culturaltranslate.com, Address: CulturalTranslate Legal Department, 123 Translation Avenue, San Francisco, CA 94102, United States. Last updated: December 3, 2025.',
                    ],
                ]),
                'seo_title' => 'Terms of Service - CulturalTranslate',
                'seo_description' => 'Read the Terms of Service for using CulturalTranslate translation platform and API services.',
                'seo_keywords' => 'terms of service, legal agreement, user agreement, service terms',
                'status' => 'published',
                'locale' => 'en',
            ],
            [
                'page_slug' => 'security',
                'page_title' => 'Security',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'Security at CulturalTranslate',
                        'subtitle' => 'We take the security of your data seriously. Learn about our comprehensive security measures and practices.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Enterprise-Grade Security',
                        'content' => 'CulturalTranslate implements industry-leading security practices to protect your translation data and personal information. Our security infrastructure is built on multiple layers of protection, including encryption, access controls, monitoring, and regular security audits. We follow the principle of defense in depth, ensuring that even if one security layer is compromised, multiple other protections remain in place. Our security team continuously monitors for threats and vulnerabilities, and we maintain compliance with international security standards including SOC 2, ISO 27001, and GDPR requirements.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Data Encryption',
                        'content' => 'All data transmitted to and from CulturalTranslate is encrypted using TLS 1.3 or higher, the same encryption standard used by banks and financial institutions. Data at rest is encrypted using AES-256 encryption, ensuring that your translation content and personal information remain secure even in the unlikely event of a physical security breach. Our encryption keys are managed using hardware security modules (HSMs) and are regularly rotated according to industry best practices. We never store translation content in plain text, and all database connections use encrypted channels. API communications are secured with industry-standard authentication tokens and OAuth 2.0 protocols.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Access Controls',
                        'content' => 'We implement strict access controls to ensure that only authorized personnel can access sensitive systems and data. All employee access is based on the principle of least privilege, meaning team members only have access to the data and systems necessary for their specific roles. Multi-factor authentication (MFA) is required for all administrative access to our systems. We maintain detailed audit logs of all access to sensitive data and systems, and these logs are regularly reviewed for suspicious activity. All access to production systems is monitored and requires approval through our security review process.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Infrastructure Security',
                        'content' => 'Our infrastructure is hosted in secure, SOC 2 Type II certified data centers with physical security measures including 24/7 surveillance, biometric access controls, and environmental monitoring. We use distributed architecture across multiple availability zones to ensure high availability and resilience against attacks. All servers are hardened according to CIS benchmarks and regularly patched with security updates. We employ advanced firewalls, intrusion detection systems (IDS), and intrusion prevention systems (IPS) to protect against network-based attacks. Our infrastructure undergoes regular vulnerability scanning and penetration testing by independent security firms.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Application Security',
                        'content' => 'Security is integrated into every stage of our software development lifecycle. Our developers follow secure coding practices and receive regular security training. All code undergoes automated security scanning and manual code reviews before deployment. We implement protection against common web vulnerabilities including SQL injection, cross-site scripting (XSS), cross-site request forgery (CSRF), and other OWASP Top 10 threats. Our API endpoints implement rate limiting, input validation, and authentication to prevent abuse. We maintain a responsible disclosure program and work with security researchers to identify and fix vulnerabilities quickly.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Monitoring and Incident Response',
                        'content' => 'We maintain 24/7 security monitoring with automated alerting for suspicious activities and potential security incidents. Our Security Operations Center (SOC) continuously analyzes logs, traffic patterns, and system behaviors to detect and respond to threats in real-time. We have a comprehensive incident response plan that includes identification, containment, eradication, recovery, and post-incident analysis procedures. In the unlikely event of a security incident affecting customer data, we follow our breach notification procedures in accordance with applicable laws and regulations, ensuring transparent communication with affected users.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Compliance and Certifications',
                        'content' => 'CulturalTranslate maintains compliance with major international security and privacy standards. We are certified for SOC 2 Type II, demonstrating our commitment to maintaining secure systems. Our practices align with ISO 27001 information security management standards. We are fully compliant with GDPR (General Data Protection Regulation) for handling European user data and CCPA (California Consumer Privacy Act) for California residents. Our payment processing is PCI DSS compliant through certified third-party payment processors. We undergo annual third-party security audits and assessments to verify our security controls and compliance.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Employee Security',
                        'content' => 'All employees and contractors undergo background checks before being granted access to our systems. Security awareness training is mandatory for all team members and is conducted regularly to ensure everyone understands their role in maintaining security. Employees sign confidentiality agreements and are trained on data handling procedures. Access credentials are immediately revoked when an employee leaves the company. We maintain strict separation of duties to prevent any single individual from having excessive system access. Our security team conducts regular phishing simulations to test and improve employee awareness.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Report a Security Issue',
                        'content' => 'If you discover a security vulnerability in our services, please report it to our security team immediately. We appreciate the security research community\'s efforts to help keep CulturalTranslate secure. Contact our security team at: security@culturaltranslate.com. We commit to acknowledging your report within 24 hours and providing regular updates on our progress toward resolution. We maintain a responsible disclosure policy and will work with you to understand and resolve the issue promptly. Last updated: December 3, 2025.',
                    ],
                ]),
                'seo_title' => 'Security - CulturalTranslate',
                'seo_description' => 'Learn about CulturalTranslate\'s comprehensive security measures, encryption, compliance, and data protection practices.',
                'seo_keywords' => 'security, data protection, encryption, compliance, SOC 2, ISO 27001, GDPR',
                'status' => 'published',
                'locale' => 'en',
            ],
            [
                'page_slug' => 'help-center',
                'page_title' => 'Help Center',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'Help Center',
                        'subtitle' => 'Find answers to common questions and learn how to get the most out of CulturalTranslate.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Getting Started',
                        'content' => 'Welcome to CulturalTranslate! Getting started with our platform is quick and easy. First, create your free account by clicking the "Sign Up" button and providing your email address and basic information. Once registered, you can immediately start translating text by pasting it into our translation interface. For document translation, simply upload your file (we support PDF, DOCX, TXT, and more) and select your target language. If you need API access for integration with your applications, navigate to your dashboard and generate your API keys. Our Quick Start Guide provides detailed instructions for each feature, and our tutorial videos walk you through common workflows step by step.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Translation Features',
                        'content' => 'CulturalTranslate offers a comprehensive set of translation features designed for accuracy and cultural relevance. Our AI-powered engine supports over 100 languages with cultural context adaptation, ensuring your translations are not just linguistically accurate but also culturally appropriate. Key features include: real-time text translation with instant results, batch document translation for multiple files, API integration for developers and businesses, cultural tone adjustment for different audiences, industry-specific terminology support, translation memory for consistent terminology, and quality scoring to help you assess translation confidence. You can also save frequently used phrases and customize your translation preferences in your account settings.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'API Integration',
                        'content' => 'Our RESTful API makes it easy to integrate CulturalTranslate into your applications, websites, or workflows. To get started with the API, generate your API key from your dashboard settings. Our comprehensive API documentation includes code examples in multiple programming languages including Python, JavaScript, PHP, Ruby, and Java. The API supports all translation features available in the web interface, including text translation, document translation, language detection, and cultural tone adjustment. Rate limits depend on your subscription plan, with higher tiers offering more requests per minute. We provide SDKs and client libraries to simplify integration, and our support team is available to help with implementation questions.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Subscription Plans',
                        'content' => 'CulturalTranslate offers flexible subscription plans to meet different needs and budgets. Our Free plan includes 10,000 characters per month, perfect for personal use or testing our service. The Starter plan provides 100,000 characters monthly with priority support. Professional plans offer unlimited translations, advanced features like cultural tone adjustment, API access with higher rate limits, and dedicated support. Enterprise plans include custom solutions, dedicated account management, SLA guarantees, and volume discounts. You can upgrade, downgrade, or cancel your subscription at any time from your account settings. All paid plans come with a 14-day money-back guarantee, and we offer annual billing with two months free.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Cultural Context Features',
                        'content' => 'What sets CulturalTranslate apart is our advanced cultural context adaptation technology. Unlike standard translation tools that only convert words between languages, our AI understands cultural nuances, idioms, formal versus informal speech patterns, regional variations within languages, and industry-specific terminology. When translating, you can specify the cultural context such as business formal, casual conversation, marketing copy, technical documentation, or legal content. Our system automatically adjusts the translation style, tone, and vocabulary to match the cultural expectations of your target audience. This ensures your message resonates appropriately and avoids unintentional cultural faux pas or misunderstandings.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Troubleshooting Common Issues',
                        'content' => 'If you encounter issues while using CulturalTranslate, here are solutions to common problems. Translation quality issues: ensure you\'ve selected the correct source and target languages, try providing more context for ambiguous phrases, use our cultural tone settings for better results. API errors: check that your API key is valid and hasn\'t expired, verify you haven\'t exceeded your rate limits, ensure your request format matches our API documentation. File upload problems: confirm your file is in a supported format (PDF, DOCX, TXT, etc.), check that file size is under your plan\'s limit, try clearing your browser cache and cookies. Account access issues: use the "Forgot Password" link to reset your password, check that you\'re using the correct email address, contact support if you continue experiencing problems.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Best Practices',
                        'content' => 'To get the best results from CulturalTranslate, follow these best practices: For text translation, provide complete sentences rather than fragments for better context understanding. Break very long texts into logical paragraphs for more accurate processing. For technical or specialized content, specify the industry context in your settings to ensure appropriate terminology. Review translations before using them in critical applications, especially for legal or medical content. Use our API\'s batch endpoints for translating multiple items efficiently rather than making individual requests. Save frequently used phrases in your translation memory for consistent terminology across projects. When working with culturally sensitive content, use our cultural tone adjustment features to ensure appropriate formality and style.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Account Management',
                        'content' => 'Managing your CulturalTranslate account is simple and straightforward. Access your account settings by clicking your profile icon in the top right corner. From there you can: update your profile information and password, manage your subscription and billing information, view your usage statistics and translation history, generate and revoke API keys, set notification preferences, configure default language pairs and cultural settings, export your data, and delete your account if needed. We support multiple payment methods including credit cards, PayPal, and wire transfer for enterprise accounts. Invoices are automatically generated and can be downloaded from your billing section. If you need to update your payment method, you can do so without interrupting your service.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Contact Support',
                        'content' => 'Our support team is here to help you succeed with CulturalTranslate. Free plan users have access to email support with responses typically within 24-48 hours. Paid plan subscribers receive priority support with faster response times. Professional and Enterprise customers can access live chat support during business hours and phone support for urgent issues. Before contacting support, check our FAQ section and documentation for quick answers to common questions. When reaching out to support, please include your account email, a detailed description of the issue, any error messages you\'ve received, and steps to reproduce the problem. This helps our team resolve your issue more quickly. Contact us at: support@culturaltranslate.com or through the chat widget in your dashboard.',
                    ],
                ]),
                'seo_title' => 'Help Center - CulturalTranslate Support',
                'seo_description' => 'Get help with CulturalTranslate. Find answers to FAQs, learn about features, API integration, and contact support.',
                'seo_keywords' => 'help center, support, FAQ, documentation, user guide, tutorial',
                'status' => 'published',
                'locale' => 'en',
            ],
            [
                'page_slug' => 'gdpr',
                'page_title' => 'GDPR Compliance',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'GDPR Compliance',
                        'subtitle' => 'CulturalTranslate is committed to protecting your privacy rights under the General Data Protection Regulation (GDPR).',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Our GDPR Commitment',
                        'content' => 'As a global translation service provider, CulturalTranslate is fully committed to compliance with the European Union\'s General Data Protection Regulation (GDPR). We recognize the importance of protecting personal data and respecting the privacy rights of individuals within the European Economic Area (EEA) and beyond. Our GDPR compliance program encompasses all aspects of our operations, from data collection and processing to storage, security, and deletion. We have implemented comprehensive policies, procedures, and technical measures to ensure that we handle personal data lawfully, fairly, and transparently. This commitment extends to our employees, contractors, and third-party service providers, all of whom are required to adhere to GDPR principles.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Legal Basis for Processing',
                        'content' => 'Under GDPR, we must have a legal basis to process your personal data. The legal bases we rely on include: Consent - when you explicitly agree to our processing of your data for specific purposes, such as marketing communications. Contractual Necessity - when processing is necessary to fulfill our contract with you to provide translation services. Legitimate Interests - when we have a legitimate business reason to process your data that does not override your privacy rights, such as fraud prevention and service improvement. Legal Obligation - when we must process data to comply with laws and regulations. We clearly identify the legal basis for each processing activity and provide you with information about your rights in each case.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Your Rights Under GDPR',
                        'content' => 'As a data subject under GDPR, you have several important rights regarding your personal data. Right to Access: You can request a copy of the personal data we hold about you. Right to Rectification: You can ask us to correct inaccurate or incomplete data. Right to Erasure (Right to be Forgotten): You can request deletion of your personal data under certain circumstances. Right to Restrict Processing: You can ask us to limit how we use your data. Right to Data Portability: You can receive your data in a structured, machine-readable format and transmit it to another service provider. Right to Object: You can object to certain types of processing, including direct marketing. Rights Related to Automated Decision-Making: You have rights regarding decisions made solely by automated processing that significantly affect you.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Data Protection Officer',
                        'content' => 'We have appointed a Data Protection Officer (DPO) who is responsible for overseeing our GDPR compliance and data protection strategy. Our DPO works to ensure that we process personal data in accordance with GDPR requirements and serves as a point of contact for data subjects who wish to exercise their rights or have questions about data processing. The DPO also coordinates with supervisory authorities and conducts regular assessments of our data protection practices. You can contact our Data Protection Officer directly for any GDPR-related inquiries or concerns at: dpo@culturaltranslate.com. Our DPO will respond to all inquiries within the timeframes required by GDPR, typically within one month of receipt.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Data Processing Records',
                        'content' => 'In compliance with GDPR Article 30, we maintain detailed records of our data processing activities. These records document the purposes of processing, categories of personal data processed, categories of data subjects and recipients, international data transfers and safeguards, retention periods for different categories of data, and technical and organizational security measures. We regularly review and update these records to ensure they accurately reflect our current data processing practices. These records are available for review by supervisory authorities upon request and can be provided to data subjects in appropriate circumstances to help them understand how their data is being processed.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Data Breach Notification',
                        'content' => 'We have implemented comprehensive procedures for detecting, investigating, and reporting personal data breaches as required by GDPR. In the event of a breach that poses a risk to individuals\' rights and freedoms, we will notify the relevant supervisory authority within 72 hours of becoming aware of the breach. If the breach is likely to result in a high risk to affected individuals, we will also notify those individuals directly without undue delay. Our breach notification will include a description of the nature of the breach, the categories and approximate number of affected data subjects and records, likely consequences of the breach, measures taken or proposed to address the breach and mitigate its effects, and contact details for obtaining more information.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'International Data Transfers',
                        'content' => 'As a global service, we may transfer personal data outside the European Economic Area (EEA). When we do so, we ensure appropriate safeguards are in place to protect your data in accordance with GDPR requirements. These safeguards include: Standard Contractual Clauses (SCCs) approved by the European Commission for transfers to countries without adequacy decisions, adequacy decisions recognizing that certain countries provide adequate data protection, and additional security measures such as encryption and access controls. We conduct transfer impact assessments to evaluate the legal framework and practices in destination countries and implement supplementary measures where necessary to ensure GDPR-level protection. Details of our international transfers and safeguards are available upon request.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Data Protection by Design and Default',
                        'content' => 'We implement the GDPR principles of data protection by design and by default throughout our operations. This means privacy and data protection considerations are integrated into all our systems, processes, and services from the outset. Our approach includes: minimizing data collection to only what is necessary for specified purposes, implementing pseudonymization and anonymization where appropriate, ensuring data accuracy and enabling easy corrections, limiting data access to authorized personnel only, implementing strong encryption and security measures, ensuring data is only retained as long as necessary, and providing clear, user-friendly privacy controls and settings. Regular privacy impact assessments help us identify and mitigate privacy risks in new projects and system changes.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Exercising Your Rights',
                        'content' => 'To exercise any of your GDPR rights, please contact us at privacy@culturaltranslate.com or through your account settings for certain requests such as data access or deletion. When you make a request, we may ask you to verify your identity to protect your personal data from unauthorized access. We will respond to your request within one month, though this may be extended by two additional months in complex cases - we will inform you if an extension is needed. There is no fee for exercising your rights unless your request is clearly unfounded, repetitive, or excessive. We will explain our reasons if we cannot fulfill your request. If you are not satisfied with our response, you have the right to lodge a complaint with your local supervisory authority.',
                    ],
                ]),
                'seo_title' => 'GDPR Compliance - CulturalTranslate',
                'seo_description' => 'Learn about CulturalTranslate\'s GDPR compliance, data protection practices, and your privacy rights under EU law.',
                'seo_keywords' => 'GDPR, data protection, privacy rights, EU compliance, personal data',
                'status' => 'published',
                'locale' => 'en',
            ],
        ];

        foreach ($pages as $page) {
            DB::table('website_content')->insert([
                'page_slug' => $page['page_slug'],
                'page_title' => $page['page_title'],
                'sections' => $page['sections'],
                'seo_title' => $page['seo_title'],
                'seo_description' => $page['seo_description'],
                'seo_keywords' => $page['seo_keywords'],
                'status' => $page['status'],
                'locale' => $page['locale'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        $this->command->info('Created ' . count($pages) . ' website content pages successfully!');
    }
}
