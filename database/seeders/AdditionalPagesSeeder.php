<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdditionalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = Carbon::now();
        
        $pages = [
            [
                'page_slug' => 'features',
                'page_title' => 'Features',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'Powerful Translation Features',
                        'subtitle' => 'Discover the advanced features that make CulturalTranslate the most sophisticated AI-powered translation platform.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'AI-Powered Translation Engine',
                        'content' => 'Our cutting-edge AI translation engine goes beyond simple word-for-word translation to deliver contextually accurate and culturally appropriate results. Powered by advanced neural networks trained on billions of multilingual documents, our system understands nuance, idioms, and cultural context in ways traditional translation tools cannot match. The engine continuously learns from user feedback and new content, improving accuracy over time. It handles complex sentence structures, technical terminology, and creative content with equal proficiency, making it ideal for everything from casual conversation to professional documentation.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Cultural Context Adaptation',
                        'content' => 'What truly sets CulturalTranslate apart is our proprietary cultural context adaptation technology. We understand that effective translation requires more than linguistic accuracy - it requires cultural intelligence. Our system automatically adjusts tone, formality levels, idiomatic expressions, and even content structure to match the cultural expectations of your target audience. Whether you\'re translating marketing materials for different markets, localizing software for global users, or communicating with international partners, our cultural adaptation ensures your message resonates authentically in every language and culture.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Real-Time Translation API',
                        'content' => 'Integrate powerful translation capabilities directly into your applications, websites, and workflows with our RESTful API. Our API delivers lightning-fast translations with sub-second response times, supports batch processing for high-volume needs, includes automatic language detection, provides confidence scores for quality assessment, and offers webhook notifications for asynchronous processing. With comprehensive documentation, SDKs in multiple programming languages, and generous rate limits even on free plans, getting started is quick and easy. Enterprise customers benefit from dedicated infrastructure, guaranteed uptime SLAs, and priority support.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Document Translation',
                        'content' => 'Translate entire documents while preserving formatting, layout, and structure. We support a wide range of file formats including PDF, Microsoft Word (DOCX), PowerPoint (PPTX), Excel (XLSX), plain text (TXT), rich text format (RTF), and markdown (MD). Our intelligent document processing maintains tables, images, charts, headers, footers, and other formatting elements. For PDFs, we use advanced OCR technology to extract text from scanned documents. Upload multiple files for batch processing, and download translated documents in their original format ready to use immediately.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Translation Memory',
                        'content' => 'Save time and ensure consistency with our translation memory feature. The system automatically stores your previous translations and suggests them when you translate similar content in the future. This is particularly valuable for technical documentation, legal documents, marketing materials, and any content where consistent terminology is important. Translation memory works across all your projects, learns from your corrections and preferences, reduces translation costs by reusing previous work, ensures brand consistency across all content, and can be exported and imported for team collaboration. You maintain full control over your translation memory with options to add, edit, or delete entries.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Industry-Specific Terminology',
                        'content' => 'Get accurate translations for specialized content with our industry-specific terminology databases. We maintain curated glossaries for healthcare and medical terminology, legal and regulatory language, financial and banking terms, technical and engineering documentation, marketing and advertising copy, e-commerce and retail, scientific research, and information technology. Select your industry when translating, and our system automatically applies the appropriate terminology and style conventions. Enterprise customers can create and maintain custom glossaries tailored to their specific business needs, ensuring perfect accuracy for company-specific terms and product names.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Tone and Formality Control',
                        'content' => 'Adjust the tone and formality of your translations to match your specific needs and audience expectations. Choose from formal business communication for professional correspondence, casual conversational tone for friendly messaging, technical and precise language for documentation, marketing and persuasive copy for promotional content, neutral and objective for news and information. Our AI understands the subtle differences in how languages express formality and adapts not just vocabulary but also sentence structure, pronouns, and honorifics to match your selected tone. This ensures your translated content feels natural and appropriate for its intended context.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Multi-Language Support',
                        'content' => 'Communicate in over 100 languages including all major world languages and many regional dialects. Our platform supports European languages like Spanish, French, German, Italian, Portuguese, and dozens more, Asian languages including Chinese (Simplified and Traditional), Japanese, Korean, Hindi, Arabic, and Southeast Asian languages, African languages, Middle Eastern languages, and Latin American Spanish variants. We continuously add new languages and improve support for existing ones based on user demand. Each language pair is individually optimized for maximum accuracy and cultural appropriateness.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Quality Scoring and Confidence Indicators',
                        'content' => 'Make informed decisions about your translations with our quality scoring system. Every translation includes a confidence score indicating the system\'s certainty about the accuracy, alternative translation suggestions for ambiguous phrases, flagged terms that may need human review, and contextual notes explaining translation choices. This transparency helps you understand the translation process and identify content that may benefit from additional review or refinement. Professional translators can use these insights to focus their efforts on the portions of content that need the most attention.',
                    ],
                ]),
                'seo_title' => 'Features - AI Translation Platform | CulturalTranslate',
                'seo_description' => 'Explore CulturalTranslate\'s powerful features: AI translation, cultural adaptation, API integration, document translation, and more.',
                'seo_keywords' => 'translation features, AI translation, cultural adaptation, translation API, document translation',
                'status' => 'published',
                'locale' => 'en',
            ],
            [
                'page_slug' => 'about',
                'page_title' => 'About Us',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'About CulturalTranslate',
                        'subtitle' => 'Breaking down language barriers with AI-powered cultural intelligence.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Our Mission',
                        'content' => 'At CulturalTranslate, we believe that effective communication transcends mere word-for-word translation. Our mission is to enable authentic, culturally-aware communication across languages and cultures, making the world more connected and understanding. We combine cutting-edge artificial intelligence with deep cultural knowledge to deliver translations that don\'t just convert languages - they bridge cultures. Founded in 2023 by a team of linguists, AI researchers, and cultural experts, we\'ve grown to serve thousands of businesses and millions of users worldwide, processing billions of words across over 100 languages.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'What Makes Us Different',
                        'content' => 'Traditional translation tools focus solely on linguistic accuracy, often producing translations that are technically correct but culturally awkward or inappropriate. CulturalTranslate goes further by understanding and adapting to cultural context. Our proprietary AI models are trained not just on parallel text corpora, but on cultural knowledge bases that encode how different cultures communicate, express emotions, show respect, and convey meaning. This cultural intelligence allows us to adapt tone, formality, idioms, and even content structure to match the expectations of your target audience, ensuring your message resonates authentically in every culture.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Our Technology',
                        'content' => 'We leverage the latest advances in natural language processing, neural machine translation, and cultural computing to deliver superior results. Our translation engine combines transformer-based neural networks for linguistic processing, cultural knowledge graphs encoding cross-cultural communication patterns, context-aware adaptation algorithms, continuous learning from user feedback, and ensemble methods combining multiple AI models for maximum accuracy. All of this runs on secure, scalable cloud infrastructure that delivers fast results while protecting your data privacy. We invest heavily in research and development, collaborating with leading universities and publishing our findings in peer-reviewed journals.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Our Team',
                        'content' => 'CulturalTranslate is powered by a diverse, global team passionate about language and technology. Our team includes computational linguists with PhDs from top universities, AI researchers specializing in NLP and machine learning, native speakers and cultural experts for every language we support, software engineers building scalable, secure systems, and customer success specialists helping users achieve their goals. We\'re a fully remote company with team members across six continents, giving us firsthand understanding of the cross-cultural communication challenges our customers face. This diversity isn\'t just a value - it\'s essential to building a truly global translation platform.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Our Values',
                        'content' => 'Everything we do is guided by our core values: Cultural Respect - We honor the richness and diversity of human cultures and ensure our technology treats all languages and cultures with equal care and respect. Privacy & Security - We protect our users\' data with enterprise-grade security and never use customer content to train our models without explicit permission. Accuracy & Quality - We\'re committed to delivering the highest quality translations and continuously improving our technology. Accessibility - We believe powerful translation technology should be available to everyone, from individual users to large enterprises. Transparency - We\'re open about how our technology works, its capabilities and limitations, and how we use data.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Serving Global Businesses',
                        'content' => 'Thousands of businesses worldwide trust CulturalTranslate for their translation needs. Our customers range from startups expanding globally to Fortune 500 enterprises managing multilingual content at scale. We serve diverse industries including e-commerce companies localizing product descriptions and marketing, technology companies translating documentation and UI, healthcare providers delivering multilingual patient care, legal firms handling international cases, educational institutions reaching global students, and marketing agencies creating culturally-adapted campaigns. Each industry has unique requirements, and our platform is flexible enough to meet them all while maintaining the highest standards of accuracy and cultural appropriateness.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Innovation and Research',
                        'content' => 'We\'re committed to advancing the state of the art in AI translation and cultural computing. Our research team regularly publishes papers on neural machine translation, cultural adaptation algorithms, low-resource language support, bias detection and mitigation, and translation quality assessment. We collaborate with academic institutions, participate in industry conferences, contribute to open-source projects, and share our findings with the broader research community. This commitment to innovation ensures our customers always have access to the most advanced translation technology available.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Join Our Journey',
                        'content' => 'We\'re always looking for talented individuals who share our passion for language, technology, and cultural understanding. Whether you\'re a researcher, engineer, linguist, or business professional, we invite you to explore career opportunities at CulturalTranslate. Visit our careers page to see current openings. Even if we don\'t have an open position that matches your skills right now, we encourage you to reach out - we\'re always interested in connecting with exceptional people. Together, we can build a more connected, understanding world.',
                    ],
                ]),
                'seo_title' => 'About Us - CulturalTranslate',
                'seo_description' => 'Learn about CulturalTranslate\'s mission to enable authentic cross-cultural communication through AI-powered translation technology.',
                'seo_keywords' => 'about us, company mission, translation technology, AI innovation, team',
                'status' => 'published',
                'locale' => 'en',
            ],
            [
                'page_slug' => 'contact',
                'page_title' => 'Contact Us',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'Get in Touch',
                        'subtitle' => 'Have questions? We\'re here to help. Contact our team for support, sales inquiries, or partnerships.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Contact Information',
                        'content' => 'Reach out to us through any of the following channels: Email: support@culturaltranslate.com for general inquiries and support, sales@culturaltranslate.com for sales and partnership inquiries, privacy@culturaltranslate.com for privacy and data protection questions, security@culturaltranslate.com for security issues and vulnerability reports. Office Address: CulturalTranslate Inc., 123 Translation Avenue, San Francisco, CA 94102, United States. Business Hours: Monday - Friday, 9:00 AM - 6:00 PM PST. For urgent support requests, Enterprise customers can contact our 24/7 support hotline.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Support',
                        'content' => 'Our support team is ready to help you succeed with CulturalTranslate. Free plan users receive email support with responses typically within 24-48 hours. Paid subscribers get priority support with faster response times. Professional and Enterprise customers have access to live chat support during business hours and phone support for urgent issues. Before contacting support, check our Help Center and FAQ for answers to common questions. Our comprehensive documentation covers getting started, API integration, troubleshooting, and advanced features.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Sales and Partnerships',
                        'content' => 'Interested in CulturalTranslate for your business? Our sales team can help you choose the right plan and answer questions about enterprise features, custom integrations, volume pricing, and service level agreements. We also partner with agencies, resellers, and technology companies to bring our translation capabilities to more users. If you\'re interested in a partnership opportunity, please reach out to our business development team at sales@culturaltranslate.com.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Media and Press',
                        'content' => 'For media inquiries, press releases, or interview requests, please contact our communications team at press@culturaltranslate.com. We\'re always happy to discuss our technology, share insights about the translation industry, or provide expert commentary on AI and natural language processing. You can find our official press kit, logo assets, and recent news on our media page.',
                    ],
                ]),
                'seo_title' => 'Contact Us - CulturalTranslate Support',
                'seo_description' => 'Contact CulturalTranslate for support, sales inquiries, partnerships, or general questions. Multiple ways to reach our team.',
                'seo_keywords' => 'contact, support, customer service, sales, partnerships',
                'status' => 'published',
                'locale' => 'en',
            ],
            [
                'page_slug' => 'guides',
                'page_title' => 'Translation Guides',
                'sections' => json_encode([
                    [
                        'type' => 'hero',
                        'title' => 'Translation Guides',
                        'subtitle' => 'Learn best practices for effective translation and localization with our comprehensive guides.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Getting Started with Translation',
                        'content' => 'New to professional translation? This guide covers the fundamentals. Understanding the difference between translation (converting text between languages) and localization (adapting content for cultural context) is crucial. Start by identifying your target audience and their language preferences. Consider regional variations - for example, Spanish in Spain differs from Mexican Spanish. Determine whether you need formal or casual tone. For business content, formal is usually safer. For marketing and social media, casual may be more engaging. Always provide context to translators or AI systems - the more information about purpose and audience, the better the results.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Best Practices for Quality Translation',
                        'content' => 'Achieving high-quality translations requires following proven best practices. Write clear, concise source content - ambiguous or overly complex text is difficult to translate accurately. Avoid idioms and cultural references that don\'t translate well unless you\'re prepared to adapt them. Use consistent terminology throughout your content. Create a glossary of key terms and their preferred translations. Break long content into logical sections for easier translation and review. Provide visual context when translating UI elements or marketing materials. Always have native speakers review translations before publication. Use translation memory to maintain consistency across projects.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Website and App Localization',
                        'content' => 'Localizing websites and applications goes beyond translating text. Consider text expansion - translations can be 30% longer than English, affecting layout and design. Account for right-to-left languages like Arabic and Hebrew which require mirrored layouts. Translate and localize images containing text. Adapt colors, symbols, and icons for cultural appropriateness. Localize dates, times, currencies, and number formats. Translate meta tags, alt text, and URLs for SEO benefits. Test your localized content thoroughly with native speakers to ensure proper display and functionality. Consider local payment methods, shipping options, and legal requirements.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'SEO for Multilingual Content',
                        'content' => 'Proper SEO implementation is essential for multilingual websites to reach global audiences. Use hreflang tags to indicate language and regional targeting to search engines. Create unique, high-quality content for each language rather than relying solely on translation. Conduct keyword research in each target language - direct translations of keywords may not match local search behavior. Use localized URLs (subdirectories, subdomains, or country-specific domains). Build backlinks from local websites in each target market. Optimize meta titles and descriptions for each language. Submit separate sitemaps for each language to Google Search Console.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Technical Documentation Translation',
                        'content' => 'Technical content requires special consideration for accurate translation. Maintain consistency in technical terminology - use the same translation for each technical term throughout. Consider whether to translate technical terms at all - some industries prefer English technical terms even in other languages. Include screenshots and diagrams to provide visual context. Translate UI elements consistently with the actual software interface. Use specialized translators with technical knowledge in your field. Create a comprehensive glossary of technical terms before starting translation. Break documentation into modules for easier updating when software changes.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Marketing Content Localization',
                        'content' => 'Marketing content needs cultural adaptation beyond literal translation. Understand cultural values and preferences in your target market - what resonates in one culture may fall flat or offend in another. Adapt slogans and taglines rather than translating directly - wordplay and rhymes rarely work across languages. Localize examples, case studies, and testimonials to feature local customers and scenarios. Consider cultural sensitivities around colors, images, humor, and topics. Research local competitors to understand market expectations. Test marketing messages with focus groups in each market before wide release. Work with in-market copywriters who understand local cultural nuances.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Legal and Compliance Translation',
                        'content' => 'Legal translation demands the highest accuracy and often requires certified translators. Use professional legal translators with expertise in both source and target legal systems. Understand that legal concepts may not have direct equivalents across jurisdictions. Maintain the exact meaning and intent of legal text - creative localization is not appropriate. Consider whether to keep certain terms in the original language with explanations. Have translations reviewed by legal professionals in the target jurisdiction. Be aware of local regulations requiring official translations for contracts, terms of service, privacy policies, and other legal documents. Keep records of who translated what and when for audit purposes.',
                    ],
                    [
                        'type' => 'content',
                        'title' => 'Managing Translation Projects',
                        'content' => 'Effective project management is key to successful translation initiatives. Define clear goals, timelines, and quality standards upfront. Create a detailed brief including target audience, tone, style preferences, and any taboos or sensitivities. Establish a review and approval process with stakeholders. Use translation management systems to track progress and maintain version control. Build in time for quality assurance and revisions. Communicate regularly with translators and provide prompt feedback. Maintain a translation memory and terminology database for consistency across projects. Plan for ongoing updates and new content translation rather than one-time efforts.',
                    ],
                ]),
                'seo_title' => 'Translation Guides - Best Practices | CulturalTranslate',
                'seo_description' => 'Learn translation and localization best practices with our comprehensive guides covering websites, apps, marketing, and more.',
                'seo_keywords' => 'translation guide, localization, best practices, SEO, multilingual content',
                'status' => 'published',
                'locale' => 'en',
            ],
        ];

        foreach ($pages as $page) {
            // Check if page already exists
            $exists = DB::table('website_content')
                ->where('page_slug', $page['page_slug'])
                ->where('locale', $page['locale'])
                ->exists();
            
            if (!$exists) {
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
        }

        $this->command->info('Created additional website content pages successfully!');
    }
}
