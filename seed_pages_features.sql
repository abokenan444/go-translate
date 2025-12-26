-- Add sample pages if none exist
INSERT OR IGNORE INTO pages (slug, title, content, status, created_at, updated_at) VALUES 
('terms', 'Terms of Service', '<h1>Terms of Service</h1><p>Terms content here...</p>', 'published', datetime('now'), datetime('now')),
('privacy', 'Privacy Policy', '<h1>Privacy Policy</h1><p>Privacy content here...</p>', 'published', datetime('now'), datetime('now')),
('about', 'About Us', '<h1>About CulturalTranslate</h1><p>About content here...</p>', 'published', datetime('now'), datetime('now'));

-- Add sample feature flags
INSERT OR IGNORE INTO feature_flags (key, name, description, status, rollout_percentage, created_at, updated_at) VALUES 
('realtime_translation', 'Real-time Translation', 'Enable real-time voice translation feature', 'enabled', 100, datetime('now'), datetime('now')),
('ai_agent', 'AI Agent', 'Enable AI development agent', 'enabled', 100, datetime('now'), datetime('now')),
('cultural_memory', 'Cultural Memory', 'Enable cultural memory graph', 'enabled', 100, datetime('now'), datetime('now'));

-- Add sample API providers
INSERT OR IGNORE INTO api_providers (name, slug, type, status, key_count, created_at, updated_at) VALUES 
('OpenAI', 'openai', 'text', 'enabled', 1, datetime('now'), datetime('now')),
('Google Translate', 'google-translate', 'text', 'enabled', 0, datetime('now'), datetime('now')),
('Google Vision', 'google-vision', 'vision', 'enabled', 0, datetime('now'), datetime('now'));
