-- Clear existing footer items
DELETE FROM menu_items WHERE location = 'footer';

-- Product Menu Items
INSERT INTO menu_items (title, url, is_active, location, "order", created_at, updated_at) VALUES
('Features', '/features', 1, 'footer', 1, datetime('now'), datetime('now')),
('Pricing', '/pricing', 1, 'footer', 2, datetime('now'), datetime('now')),
('API Docs', '/api-docs', 1, 'footer', 3, datetime('now'), datetime('now')),
('Live Demo', '/demo', 1, 'footer', 4, datetime('now'), datetime('now')),
('Integrations', '/integrations', 1, 'footer', 5, datetime('now'), datetime('now'));

-- Documentation Menu Items
INSERT INTO menu_items (title, url, is_active, location, "order", created_at, updated_at) VALUES
('Getting Started', '/docs', 1, 'footer', 10, datetime('now'), datetime('now')),
('Installation', '/docs/installation', 1, 'footer', 11, datetime('now'), datetime('now')),
('API Reference', '/docs/api', 1, 'footer', 12, datetime('now'), datetime('now')),
('Webhooks', '/docs/webhooks', 1, 'footer', 13, datetime('now'), datetime('now')),
('CLI Tool', '/docs/cli', 1, 'footer', 14, datetime('now'), datetime('now')),
('Glossary', '/docs/glossary', 1, 'footer', 15, datetime('now'), datetime('now'));

-- Company Menu Items
INSERT INTO menu_items (title, url, is_active, location, "order", created_at, updated_at) VALUES
('About Us', '/about', 1, 'footer', 20, datetime('now'), datetime('now')),
('Blog', '/blog', 1, 'footer', 21, datetime('now'), datetime('now')),
('Careers', '/careers', 1, 'footer', 22, datetime('now'), datetime('now')),
('Contact Us', '/contact', 1, 'footer', 23, datetime('now'), datetime('now')),
('Press Kit', '/press', 1, 'footer', 24, datetime('now'), datetime('now'));

-- Resources Menu Items
INSERT INTO menu_items (title, url, is_active, location, "order", created_at, updated_at) VALUES
('Help Center', '/help-center', 1, 'footer', 30, datetime('now'), datetime('now')),
('Guides', '/guides', 1, 'footer', 31, datetime('now'), datetime('now')),
('Case Studies', '/use-cases', 1, 'footer', 32, datetime('now'), datetime('now')),
('System Status', '/status', 1, 'footer', 33, datetime('now'), datetime('now')),
('Community', '/community', 1, 'footer', 34, datetime('now'), datetime('now'));

-- Affiliate Program Menu Items  
INSERT INTO menu_items (title, url, is_active, location, "order", created_at, updated_at) VALUES
('Affiliate Dashboard', '/admin/affiliates', 1, 'footer', 40, datetime('now'), datetime('now')),
('API Access', '/api/affiliates/me', 1, 'footer', 41, datetime('now'), datetime('now')),
('Documentation', '/docs/affiliate-program', 1, 'footer', 42, datetime('now'), datetime('now')),
('Join Program', '/affiliate/signup', 1, 'footer', 43, datetime('now'), datetime('now')),
('Payouts', '/admin/payouts', 1, 'footer', 44, datetime('now'), datetime('now'));

-- Legal Menu Items
INSERT INTO menu_items (title, url, is_active, location, "order", created_at, updated_at) VALUES
('Privacy Policy', '/privacy-policy', 1, 'footer', 50, datetime('now'), datetime('now')),
('Terms of Service', '/terms-of-service', 1, 'footer', 51, datetime('now'), datetime('now')),
('Security', '/security', 1, 'footer', 52, datetime('now'), datetime('now')),
('GDPR Compliance', '/gdpr', 1, 'footer', 53, datetime('now'), datetime('now')),
('Cookie Policy', '/cookies', 1, 'footer', 54, datetime('now'), datetime('now'));
