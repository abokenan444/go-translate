-- Update existing SMTP settings with proper configuration
UPDATE smtp_settings SET 
    name = 'Gmail SMTP',
    host = 'smtp.gmail.com',
    port = 587,
    encryption = 'tls',
    from_address = 'noreply@culturaltranslate.com',
    from_name = 'CulturalTranslate',
    is_active = 1,
    is_default = 1
WHERE id = 1;

-- Add alternative SMTP configurations
INSERT OR IGNORE INTO smtp_settings (name, host, port, username, password, encryption, from_address, from_name, is_active, is_default, created_at, updated_at) VALUES 
('SendGrid SMTP', 'smtp.sendgrid.net', 587, 'apikey', '', 'tls', 'noreply@culturaltranslate.com', 'CulturalTranslate', 0, 0, datetime('now'), datetime('now')),
('Mailgun SMTP', 'smtp.mailgun.org', 587, '', '', 'tls', 'noreply@culturaltranslate.com', 'CulturalTranslate', 0, 0, datetime('now'), datetime('now')),
('AWS SES', 'email-smtp.us-east-1.amazonaws.com', 587, '', '', 'tls', 'noreply@culturaltranslate.com', 'CulturalTranslate', 0, 0, datetime('now'), datetime('now'));
