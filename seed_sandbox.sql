-- Insert demo sandbox instance (user_id=1 must exist)
INSERT INTO sandbox_instances (user_id, company_name, company_slug, subdomain, status, brand_profile_id, industry_profile, target_markets, tone, preview_url, is_public_preview, rate_limit_profile, expires_at, created_at, updated_at) 
VALUES (1, 'Demo Company', 'demo-company', 'demo', 'active', NULL, 'technology', '["us","uk","ae"]', 'professional', 'https://culturaltranslate.com/sandbox/preview/demo', 1, 'sandbox_basic', datetime('now', '+30 days'), datetime('now'), datetime('now'));

-- Insert demo sandbox pages
INSERT INTO sandbox_pages (sandbox_instance_id, template_id, path, original_content, translated_content, locale, market, last_translation_job_id, created_at, updated_at)
VALUES 
(1, NULL, '/', '{"hero_title":"Welcome to Demo Company","hero_subtitle":"Transform your business with our AI-powered solutions","cta":"Get Started","features":["Feature 1","Feature 2","Feature 3"]}', '{"hero_title":"مرحباً بك في شركة العرض التوضيحي","hero_subtitle":"حول عملك مع حلولنا المدعومة بالذكاء الاصطناعي","cta":"ابدأ الآن","features":["الميزة 1","الميزة 2","الميزة 3"]}', 'ar', 'ae', NULL, datetime('now'), datetime('now')),
(1, NULL, '/about', '{"title":"About Us","content":"Our company has been serving customers since 2020. We specialize in AI translation and localization.","mission":"Making global communication seamless"}', '{"title":"من نحن","content":"شركتنا تخدم العملاء منذ 2020. نحن متخصصون في الترجمة والتوطين بالذكاء الاصطناعي.","mission":"جعل التواصل العالمي سلساً"}', 'ar', 'ae', NULL, datetime('now'), datetime('now')),
(1, NULL, '/products', '{"title":"Our Products","subtitle":"Industry-leading solutions","items":["AI Translation API","Cultural Localization Engine","Real-time Translation Widget"]}', '{"title":"منتجاتنا","subtitle":"حلول رائدة في الصناعة","items":["واجهة برمجة الترجمة بالذكاء الاصطناعي","محرك التوطين الثقافي","أداة الترجمة الفورية"]}', 'ar', 'ae', NULL, datetime('now'), datetime('now')),
(1, NULL, '/contact', '{"title":"Contact Us","email":"demo@example.com","phone":"+1234567890","address":"123 Tech Street, Silicon Valley"}', '{"title":"اتصل بنا","email":"demo@example.com","phone":"+1234567890","address":"123 شارع التقنية، وادي السيليكون"}', 'ar', 'ae', NULL, datetime('now'), datetime('now'));

-- Insert API key
INSERT INTO sandbox_api_keys (sandbox_instance_id, key, name, scopes, rate_limit_profile, last_used_at, created_at, updated_at)
VALUES (1, 'sk_sandbox_demo_123456789abcdef', 'Demo API Key', '["translate","webhook","glossary"]', 'sandbox_basic', NULL, datetime('now'), datetime('now'));
