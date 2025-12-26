<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <title>Terms & Conditions - Cultural Translate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('components.navigation')
    
    <div class="max-w-5xl mx-auto px-4 py-16">
        <div class="bg-white rounded-lg shadow-lg p-12">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Terms & Conditions</h1>
            <p class="text-gray-600 mb-8">Last Updated: December 12, 2024</p>
            
            <div class="prose prose-lg max-w-none space-y-8">
                
                <!-- Introduction -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Welcome to Cultural Translate ("we," "our," or "us"). These Terms and Conditions govern your access to and use of our professional translation services platform, including certified translation services, physical document delivery, partner programs, affiliate programs, and enterprise solutions. By accessing or using our services, you agree to be bound by these Terms.
                    </p>
                    <p class="text-gray-700 leading-relaxed mt-4">
                        If you do not agree with any part of these Terms, you must not use our services. We reserve the right to modify these Terms at any time, and your continued use of our services constitutes acceptance of any changes.
                    </p>
                </section>

                <!-- Service Description -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">2. Service Description</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">2.1 Translation Services</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We provide professional translation services for documents across 100+ languages and 73+ document types. Our services include standard translation, certified translation, and specialized translation for legal, medical, technical, and academic documents.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">2.2 Certified Translation Services</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Our certified translation service provides official translations with dual stamps from Cultural Translate and our certified partners. These translations include:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                        <li>Dual stamp certification (Cultural Translate + Certified Partner)</li>
                        <li>Unique certificate ID for verification</li>
                        <li>QR code for instant authenticity verification</li>
                        <li>Acceptance by embassies, universities, courts, and government institutions</li>
                        <li>Digital PDF delivery with watermarked stamps</li>
                    </ul>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">2.3 Physical Copy and Shipping Services</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We offer professional printing and worldwide shipping of certified documents. This service includes:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                        <li>Professional printing by certified partners</li>
                        <li>Standard and express shipping options</li>
                        <li>Real-time tracking from printing to delivery</li>
                        <li>Worldwide delivery to any address</li>
                        <li>Pricing: $50 base fee + $2 per page (Express: 2.5x multiplier)</li>
                    </ul>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">2.4 Partner Program</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Certified partners receive access to our platform's professional tools, including document management, stamp application, print queue management, and earnings tracking. Partners earn $5 per stamped document plus additional fees for printing services.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">2.5 Affiliate Program</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Our affiliate program allows individuals and businesses to earn commissions by referring clients to our platform. Affiliates receive unique referral links, tracking tools, marketing materials, and competitive commission rates.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">2.6 Enterprise Solutions</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We provide tailored translation solutions for large organizations, including custom integrations, dedicated support, volume discounts, and API access for automated workflows.
                    </p>
                </section>

                <!-- User Obligations -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">3. User Obligations</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">3.1 Account Registration</h3>
                    <p class="text-gray-700 leading-relaxed">
                        You must provide accurate, current, and complete information during registration. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">3.2 Acceptable Use</h3>
                    <p class="text-gray-700 leading-relaxed">
                        You agree not to use our services for any unlawful purpose or in any way that could damage, disable, overburden, or impair our servers or networks. Prohibited activities include:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                        <li>Uploading malicious code or viruses</li>
                        <li>Attempting to gain unauthorized access to our systems</li>
                        <li>Using automated tools to scrape or harvest data</li>
                        <li>Submitting false or fraudulent documents</li>
                        <li>Violating intellectual property rights</li>
                        <li>Circumventing security measures or rate limiting</li>
                    </ul>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">3.3 Document Submission</h3>
                    <p class="text-gray-700 leading-relaxed">
                        You warrant that you have the legal right to submit all documents for translation and that the documents do not contain illegal, defamatory, or infringing content. You are solely responsible for the accuracy and legality of submitted documents.
                    </p>
                </section>

                <!-- Payment Terms -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">4. Payment Terms</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">4.1 Pricing</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Prices for our services are displayed on our website and may vary based on language pair, document type, turnaround time, and additional services (certification, shipping). All prices are in USD unless otherwise stated.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">4.2 Payment Methods</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We accept payments via credit card, debit card, and other payment methods as indicated on our platform. All payments are processed securely through Stripe payment gateway.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">4.3 Refund Policy</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Refunds are available under the following conditions:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                        <li>Service not delivered within agreed timeframe (excluding delays caused by client)</li>
                        <li>Translation quality does not meet professional standards</li>
                        <li>Technical errors preventing service delivery</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        Refund requests must be submitted within 14 days of service delivery. Refunds are not available for completed and delivered certified translations or shipped physical copies.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">4.4 Partner and Affiliate Payments</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Partners and affiliates will receive payments according to their respective program terms. Payments are processed monthly for earnings exceeding the minimum threshold of $100. Payment methods include bank transfer and PayPal.
                    </p>
                </section>

                <!-- Intellectual Property -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">5. Intellectual Property Rights</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">5.1 Platform Ownership</h3>
                    <p class="text-gray-700 leading-relaxed">
                        All intellectual property rights in our platform, including software, design, trademarks, and content, are owned by Cultural Translate or our licensors. You may not copy, modify, distribute, or reverse engineer any part of our platform.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">5.2 User Content</h3>
                    <p class="text-gray-700 leading-relaxed">
                        You retain all rights to documents you submit for translation. By submitting documents, you grant us a limited license to process, translate, and deliver the documents as part of our services. We do not claim ownership of your documents.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">5.3 Translation Ownership</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Upon full payment, you receive all rights to the translated documents. You may use the translations for any lawful purpose. However, you may not resell our translation services or present our work as your own.
                    </p>
                </section>

                <!-- Privacy and Data Protection -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">6. Privacy and Data Protection</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">6.1 Data Collection</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We collect and process personal data in accordance with our Privacy Policy and applicable data protection laws, including GDPR and CCPA. Data collected includes account information, payment details, uploaded documents, and usage analytics.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">6.2 Data Security</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We implement enterprise-grade security measures to protect your data, including:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                        <li>SSL/TLS encryption for data transmission</li>
                        <li>Encrypted storage for sensitive documents</li>
                        <li>10-layer security protection system</li>
                        <li>Google reCAPTCHA v3 for bot protection</li>
                        <li>Advanced rate limiting and DDoS protection</li>
                        <li>Regular security audits and monitoring</li>
                    </ul>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">6.3 Data Retention</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We retain your documents and personal data for as long as necessary to provide services and comply with legal obligations. You may request deletion of your data at any time, subject to legal retention requirements.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">6.4 Third-Party Sharing</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We share data with certified partners and service providers only as necessary to deliver services. We do not sell your personal data to third parties. All partners are bound by confidentiality agreements.
                    </p>
                </section>

                <!-- Certified Translation Terms -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">7. Certified Translation Specific Terms</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">7.1 Certification Validity</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Our certified translations are performed by licensed and certified translators. While we ensure the highest quality standards, acceptance of certified translations is at the discretion of the receiving institution. We recommend confirming acceptance requirements before ordering.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">7.2 Dual Stamp System</h3>
                    <p class="text-gray-700 leading-relaxed">
                        All certified translations include dual stamps: one from Cultural Translate and one from our certified partner. Both stamps are digitally applied and include unique identifiers for verification purposes.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">7.3 QR Code Verification</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Each certified document includes a QR code linking to our verification system. Scanning the QR code confirms the document's authenticity, certificate ID, translation date, and partner information.
                    </p>
                </section>

                <!-- Shipping Terms -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">8. Physical Copy and Shipping Terms</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">8.1 Shipping Responsibility</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We are responsible for printing and shipping your documents to the provided address. You are responsible for providing accurate shipping information. We are not liable for delivery failures due to incorrect addresses.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">8.2 Delivery Times</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Standard shipping typically takes 7-14 business days depending on destination. Express shipping takes 3-5 business days. Delivery times are estimates and may vary due to customs, holidays, or carrier delays.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">8.3 Customs and Duties</h3>
                    <p class="text-gray-700 leading-relaxed">
                        International shipments may be subject to customs duties and taxes. You are responsible for all customs fees, import duties, and local taxes. We are not responsible for delays caused by customs processing.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">8.4 Lost or Damaged Shipments</h3>
                    <p class="text-gray-700 leading-relaxed">
                        If your shipment is lost or damaged during transit, please contact us within 7 days of the expected delivery date. We will work with the carrier to resolve the issue and may provide a replacement at no additional cost.
                    </p>
                </section>

                <!-- Partner Program Terms -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">9. Partner Program Terms</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">9.1 Partner Eligibility</h3>
                    <p class="text-gray-700 leading-relaxed">
                        To become a certified partner, you must be a licensed translator, translation agency, law firm, or certified professional with relevant qualifications. You must provide proof of certification and undergo our verification process.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">9.2 Partner Responsibilities</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Partners are responsible for:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                        <li>Applying stamps to assigned documents within agreed timeframes</li>
                        <li>Maintaining professional standards and quality</li>
                        <li>Printing physical copies when requested</li>
                        <li>Maintaining confidentiality of client documents</li>
                        <li>Keeping certification and licenses current</li>
                    </ul>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">9.3 Partner Earnings</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Partners earn $5 per stamped document plus additional fees for printing services. Earnings are calculated monthly and paid within 15 days of the month end, provided the minimum threshold of $100 is met.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">9.4 Partner Termination</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Either party may terminate the partnership with 30 days written notice. We reserve the right to immediately terminate partnerships for violations of these Terms, quality issues, or fraudulent activity.
                    </p>
                </section>

                <!-- Affiliate Program Terms -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">10. Affiliate Program Terms</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">10.1 Affiliate Eligibility</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Anyone may join our affiliate program. Affiliates must comply with all applicable laws and regulations, including disclosure requirements for affiliate relationships.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">10.2 Commission Structure</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Affiliates earn commissions on successful referrals according to our published commission rates. Commissions are tracked automatically through unique referral links and QR codes.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">10.3 Prohibited Promotion Methods</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Affiliates may not use the following promotion methods:
                    </p>
                    <ul class="list-disc pl-6 text-gray-700 space-y-2 mt-3">
                        <li>Spam or unsolicited email marketing</li>
                        <li>False or misleading advertising</li>
                        <li>Trademark or brand bidding on search engines</li>
                        <li>Cookie stuffing or fraudulent tracking</li>
                        <li>Self-referrals or fake accounts</li>
                    </ul>
                </section>

                <!-- Limitation of Liability -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">11. Limitation of Liability</h2>
                    
                    <p class="text-gray-700 leading-relaxed">
                        To the maximum extent permitted by law, Cultural Translate shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including loss of profits, data, or business opportunities, arising from your use of our services.
                    </p>
                    
                    <p class="text-gray-700 leading-relaxed mt-4">
                        Our total liability for any claim arising from these Terms or our services shall not exceed the amount you paid for the specific service giving rise to the claim, or $500, whichever is greater.
                    </p>

                    <p class="text-gray-700 leading-relaxed mt-4">
                        We do not guarantee that certified translations will be accepted by all institutions. Acceptance is at the discretion of the receiving party. We recommend confirming requirements before ordering.
                    </p>
                </section>

                <!-- Warranty Disclaimer -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">12. Warranty Disclaimer</h2>
                    
                    <p class="text-gray-700 leading-relaxed">
                        Our services are provided "as is" and "as available" without warranties of any kind, either express or implied. We do not warrant that our services will be uninterrupted, error-free, or secure.
                    </p>
                    
                    <p class="text-gray-700 leading-relaxed mt-4">
                        While we strive for accuracy in all translations, we do not guarantee that translations will be error-free or suitable for all purposes. You are responsible for reviewing translations before use.
                    </p>
                </section>

                <!-- Dispute Resolution -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">13. Dispute Resolution</h2>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">13.1 Informal Resolution</h3>
                    <p class="text-gray-700 leading-relaxed">
                        In the event of any dispute, you agree to first contact us to seek an informal resolution. We commit to working in good faith to resolve disputes amicably.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">13.2 Arbitration</h3>
                    <p class="text-gray-700 leading-relaxed">
                        If informal resolution fails, disputes shall be resolved through binding arbitration in accordance with the rules of the American Arbitration Association. Arbitration shall take place in [Your Jurisdiction].
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-3 mt-6">13.3 Class Action Waiver</h3>
                    <p class="text-gray-700 leading-relaxed">
                        You agree to resolve disputes on an individual basis and waive any right to participate in class actions or class-wide arbitration.
                    </p>
                </section>

                <!-- Governing Law -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">14. Governing Law</h2>
                    
                    <p class="text-gray-700 leading-relaxed">
                        These Terms shall be governed by and construed in accordance with the laws of [Your Jurisdiction], without regard to its conflict of law provisions. You consent to the exclusive jurisdiction of courts in [Your Jurisdiction] for any legal proceedings.
                    </p>
                </section>

                <!-- Changes to Terms -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">15. Changes to Terms</h2>
                    
                    <p class="text-gray-700 leading-relaxed">
                        We reserve the right to modify these Terms at any time. We will notify users of material changes via email or platform notification. Your continued use of our services after changes constitutes acceptance of the modified Terms.
                    </p>
                </section>

                <!-- Termination -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">16. Termination</h2>
                    
                    <p class="text-gray-700 leading-relaxed">
                        We may suspend or terminate your account at any time for violations of these Terms, fraudulent activity, or any other reason at our sole discretion. Upon termination, you will lose access to your account and any pending services.
                    </p>
                    
                    <p class="text-gray-700 leading-relaxed mt-4">
                        You may terminate your account at any time by contacting our support team. Termination does not relieve you of payment obligations for services already rendered.
                    </p>
                </section>

                <!-- Contact Information -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">17. Contact Information</h2>
                    
                    <p class="text-gray-700 leading-relaxed">
                        If you have any questions about these Terms, please contact us:
                    </p>
                    
                    <div class="bg-gray-50 rounded-lg p-6 mt-4">
                        <p class="text-gray-700"><strong>Cultural Translate</strong></p>
                        <p class="text-gray-700 mt-2">Email: <a href="mailto:support@culturaltranslate.com" class="text-indigo-600 hover:text-indigo-700">support@culturaltranslate.com</a></p>
                        <p class="text-gray-700">Website: <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-700">{{ config('app.url') }}</a></p>
                        <p class="text-gray-700 mt-4"><em>Last Updated: December 12, 2024</em></p>
                    </div>
                </section>

                <!-- Acknowledgment -->
                <section class="bg-indigo-50 rounded-lg p-8 mt-8">
                    <h3 class="text-2xl font-bold text-indigo-900 mb-4">Acknowledgment</h3>
                    <p class="text-indigo-800 leading-relaxed">
                        By using Cultural Translate services, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree with these Terms, please discontinue use of our services immediately.
                    </p>
                </section>

            </div>
        </div>
    </div>
    
    @include('components.footer')
</body>
</html>
