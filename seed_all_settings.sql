-- System Settings
INSERT INTO system_settings (key, value, type, updated_at) VALUES 
('site_name', 'CulturalTranslate', 'system', datetime('now')),
('site_description', 'AI-powered cultural translation platform', 'system', datetime('now')),
('admin_email', 'admin@culturaltranslate.com', 'email', datetime('now')),
('default_language', 'en', 'system', datetime('now')),
('timezone', 'UTC', 'system', datetime('now')),
('maintenance_mode', '0', 'system', datetime('now')),
('allow_registration', '1', 'system', datetime('now')),
('max_upload_size', '10', 'system', datetime('now')),
('stripe_public_key', '', 'billing', datetime('now')),
('stripe_secret_key', '', 'billing', datetime('now')),
('openai_api_key', '', 'api', datetime('now'));

-- SMTP Settings
INSERT INTO smtp_settings (host, port, username, password, encryption, from_address, from_name, created_at, updated_at) VALUES 
('smtp.gmail.com', 587, '', '', 'tls', 'noreply@culturaltranslate.com', 'CulturalTranslate', datetime('now'), datetime('now'));

-- General Settings
INSERT INTO settings (key, value, created_at, updated_at) VALUES 
('app_name', 'CulturalTranslate', datetime('now'), datetime('now')),
('support_email', 'support@culturaltranslate.com', datetime('now'), datetime('now')),
('default_currency', 'USD', datetime('now'), datetime('now')),
('items_per_page', '20', datetime('now'), datetime('now'));

-- Website Content
INSERT INTO website_content (page_slug, page_title, sections, seo_title, seo_description, status, locale, created_at, updated_at) VALUES 
('home', 'Home', '{"hero":{"title":"Welcome to CulturalTranslate","subtitle":"Break language barriers with AI"}}', 'CulturalTranslate - AI Translation Platform', 'Translate with cultural context and preserve meaning', 'published', 'en', datetime('now'), datetime('now')),
('about', 'About Us', '{"content":"About CulturalTranslate"}', 'About - CulturalTranslate', 'Learn about our AI-powered translation platform', 'published', 'en', datetime('now'), datetime('now')),
('pricing', 'Pricing', '{"plans":[]}', 'Pricing - CulturalTranslate', 'Affordable translation plans for everyone', 'published', 'en', datetime('now'), datetime('now'));
