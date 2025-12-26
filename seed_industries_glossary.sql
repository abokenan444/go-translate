-- Add sample industries
INSERT OR IGNORE INTO industries (key, name, description, created_at, updated_at) VALUES 
('technology', 'Technology', 'Tech and software companies', datetime('now'), datetime('now')),
('healthcare', 'Healthcare', 'Medical and healthcare services', datetime('now'), datetime('now')),
('finance', 'Finance', 'Banking and financial services', datetime('now'), datetime('now')),
('ecommerce', 'E-commerce', 'Online retail and marketplaces', datetime('now'), datetime('now')),
('education', 'Education', 'Educational institutions and services', datetime('now'), datetime('now'));

-- Add sample glossary terms
INSERT OR IGNORE INTO glossary_terms (language, term, preferred, context, created_at, updated_at) VALUES 
('ar', 'Welcome', 'مرحبا', 'greeting', datetime('now'), datetime('now')),
('ar', 'Thank you', 'شكرا لك', 'greeting', datetime('now'), datetime('now')),
('es', 'Hello', 'Hola', 'greeting', datetime('now'), datetime('now')),
('fr', 'Goodbye', 'Au revoir', 'greeting', datetime('now'), datetime('now')),
('ar', 'Privacy Policy', 'سياسة الخصوصية', 'legal', datetime('now'), datetime('now'));
