INSERT INTO contact_settings ("group", type, label, value, "order", created_at, updated_at) VALUES 
('general', 'email', 'Support Email', 'support@culturaltranslate.com', 1, datetime('now'), datetime('now')),
('general', 'email', 'Sales Email', 'sales@culturaltranslate.com', 2, datetime('now'), datetime('now')),
('general', 'text', 'Phone', '+1 (555) 123-4567', 3, datetime('now'), datetime('now')),
('general', 'textarea', 'Address', '123 Translation St, Language City, LC 12345', 4, datetime('now'), datetime('now')),
('smtp', 'text', 'SMTP Host', 'smtp.gmail.com', 10, datetime('now'), datetime('now')),
('smtp', 'text', 'SMTP Port', '587', 11, datetime('now'), datetime('now')),
('smtp', 'text', 'SMTP Username', '', 12, datetime('now'), datetime('now')),
('smtp', 'password', 'SMTP Password', '', 13, datetime('now'), datetime('now'));
