-- Insert demo affiliate account
INSERT INTO affiliates (user_id, code, name, status, api_key, country, payout_method, payout_details, current_rate, created_at, updated_at)
VALUES (1, 'DEMO2024', 'Demo Affiliate Partner', 'active', 'ak_demo_affiliate_12345', 'US', 'paypal', '{"email":"affiliate@example.com"}', 0.20, datetime('now'), datetime('now'));

-- Insert referral links for the affiliate
INSERT INTO referral_links (affiliate_id, slug, destination_url, utm_source, utm_medium, utm_campaign, metadata, created_at, updated_at)
VALUES 
(1, 'demo-affiliate', 'https://culturaltranslate.com/register', 'demo_affiliate', 'referral', 'demo_campaign', '{"title":"Demo Registration Link"}', datetime('now'), datetime('now')),
(1, 'demo-pricing', 'https://culturaltranslate.com/pricing', 'demo_affiliate', 'referral', 'pricing_campaign', '{"title":"Pricing Page Link"}', datetime('now'), datetime('now')),
(1, 'demo-features', 'https://culturaltranslate.com/features', 'demo_affiliate', 'referral', 'features_campaign', '{"title":"Features Page Link"}', datetime('now'), datetime('now'));

-- Insert sample clicks
INSERT INTO clicks (referral_link_id, ip, user_agent, country, referer, session_id, clicked_at)
VALUES 
(1, '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'US', 'https://google.com', 'session_1', datetime('now', '-5 days')),
(1, '192.168.1.2', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', 'UK', 'https://facebook.com', 'session_2', datetime('now', '-3 days')),
(2, '192.168.1.3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)', 'AE', 'https://twitter.com', 'session_3', datetime('now', '-1 days')),
(3, '192.168.1.4', 'Mozilla/5.0 (Linux; Android 10)', 'SA', 'https://linkedin.com', 'session_4', datetime('now'));

-- Insert sample conversions (successful referrals that became customers)
INSERT INTO conversions (affiliate_id, referral_link_id, type, user_id, order_id, amount, currency, converted_at, metadata, created_at, updated_at)
VALUES 
(1, 1, 'subscription', 2, 'ORD-001', 29.99, 'EUR', datetime('now', '-2 days'), '{"plan":"Professional"}', datetime('now', '-2 days'), datetime('now', '-2 days')),
(1, 2, 'subscription', 3, 'ORD-002', 99.99, 'EUR', datetime('now', '-1 days'), '{"plan":"Business"}', datetime('now', '-1 days'), datetime('now', '-1 days'));

-- Insert sample commission/payout record
INSERT INTO commissions (affiliate_id, conversion_id, rate, amount, currency, status, eligible_at, paid_at, metadata, created_at, updated_at)
VALUES 
(1, 1, 0.20, 5.99, 'EUR', 'paid', datetime('now', '-2 days'), datetime('now', '-1 days'), '{"payment_method":"paypal"}', datetime('now', '-2 days'), datetime('now', '-1 days')),
(1, 2, 0.20, 19.99, 'EUR', 'pending', datetime('now', '-1 days'), NULL, '{}', datetime('now', '-1 days'), datetime('now', '-1 days'));
