-- Sample integrations data
INSERT INTO integrations (name, slug, type, description, category, is_active, requires_auth, install_count, created_at, updated_at) VALUES 
('Stripe', 'stripe', 'api', 'Payment processing integration', 'payment', 1, 1, 150, datetime('now'), datetime('now')),
('PayPal', 'paypal', 'api', 'PayPal payment gateway', 'payment', 1, 1, 120, datetime('now'), datetime('now')),
('Slack', 'slack', 'webhook', 'Team communication platform', 'communication', 1, 1, 200, datetime('now'), datetime('now')),
('Zapier', 'zapier', 'webhook', 'Workflow automation', 'automation', 1, 1, 300, datetime('now'), datetime('now')),
('Google Analytics', 'google-analytics', 'api', 'Website analytics', 'analytics', 1, 1, 250, datetime('now'), datetime('now')),
('Salesforce', 'salesforce', 'oauth', 'CRM integration', 'crm', 1, 1, 80, datetime('now'), datetime('now')),
('HubSpot', 'hubspot', 'oauth', 'Marketing automation', 'marketing', 1, 1, 95, datetime('now'), datetime('now')),
('Mailchimp', 'mailchimp', 'api', 'Email marketing', 'marketing', 1, 1, 180, datetime('now'), datetime('now')),
('WordPress', 'wordpress', 'api', 'WordPress plugin integration', 'cms', 1, 0, 220, datetime('now'), datetime('now')),
('Shopify', 'shopify', 'oauth', 'E-commerce platform', 'ecommerce', 1, 1, 160, datetime('now'), datetime('now'));
