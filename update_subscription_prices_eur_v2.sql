-- تحديث أسعار الاشتراكات للتوافق مع الأسعار الأوروبية الجديدة  
-- Update Subscription Prices to Match New European Pricing

USE cultural_translate;

-- التحقق من الخطط الحالية
SELECT '=== Current Plans ===' as info;
SELECT id, name, slug, price, currency, billing_period FROM subscription_plans ORDER BY price;

-- تحديث العملة إلى EUR
UPDATE subscription_plans SET currency = 'EUR' WHERE currency = 'USD' OR currency IS NULL;

-- تحديث الأسعار الأساسية
UPDATE subscription_plans SET price = 0.00 WHERE slug = 'free';
UPDATE subscription_plans SET price = 19.00 WHERE slug = 'basic';
UPDATE subscription_plans SET price = 49.00 WHERE slug IN ('professional', 'pro');
UPDATE subscription_plans SET price = 99.00 WHERE slug IN ('enterprise', 'business');

-- إضافة خطط جديدة إذا لم تكن موجودة

-- Customer Starter (€19)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, tokens_limit, is_active, sort_order)
VALUES ('Starter', 'starter', 19.00, 'EUR', 'monthly', 'For individuals - 50,000 tokens/month with AI translations', 50000, 1, 2)
ON DUPLICATE KEY UPDATE price = 19.00, currency = 'EUR';

-- Customer Pro (€49)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, tokens_limit, is_active, sort_order, is_popular)
VALUES ('Pro', 'pro', 49.00, 'EUR', 'monthly', 'For professionals - 200,000 tokens/month with AI + Cultural adaptation', 200000, 1, 3, 1)
ON DUPLICATE KEY UPDATE price = 49.00, currency = 'EUR', is_popular = 1;

-- Customer Business (€99)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, tokens_limit, is_active, sort_order, api_access)
VALUES ('Business', 'business', 99.00, 'EUR', 'monthly', 'For small teams - High-volume processing with limited API', 500000, 1, 4, 1)
ON DUPLICATE KEY UPDATE price = 99.00, currency = 'EUR', api_access = 1;

-- Partner Basic (€99)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order, team_members_limit)
VALUES ('Partner Basic', 'partner-basic', 99.00, 'EUR', 'monthly', 'Team management with basic reporting', 1, 5, 5)
ON DUPLICATE KEY UPDATE price = 99.00, currency = 'EUR';

-- Partner Pro (€249)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order, team_members_limit, api_access)
VALUES ('Partner Pro', 'partner-pro', 249.00, 'EUR', 'monthly', 'Advanced reporting with API access', 1, 6, 15, 1)
ON DUPLICATE KEY UPDATE price = 249.00, currency = 'EUR', api_access = 1;

-- Partner Enterprise (Custom)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order, is_custom)
VALUES ('Enterprise Partner', 'partner-enterprise', NULL, 'EUR', 'custom', 'Custom enterprise solutions - Contact sales', 1, 7, 1)
ON DUPLICATE KEY UPDATE is_custom = 1;

-- University Basic (€99)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order)
VALUES ('Academic Basic', 'university-basic', 99.00, 'EUR', 'monthly', 'For academic departments - Basic access', 1, 8)
ON DUPLICATE KEY UPDATE price = 99.00, currency = 'EUR';

-- University Research (€199)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order)
VALUES ('Research', 'university-research', 199.00, 'EUR', 'monthly', 'For research teams - Enhanced access', 1, 9)
ON DUPLICATE KEY UPDATE price = 199.00, currency = 'EUR';

-- University Institutional (€399)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order)
VALUES ('Institutional', 'university-institutional', 399.00, 'EUR', 'monthly', 'Full university access - Institution-wide', 1, 10)
ON DUPLICATE KEY UPDATE price = 399.00, currency = 'EUR';

-- Affiliate (Free)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order)
VALUES ('Affiliate', 'affiliate', 0.00, 'EUR', 'free', 'Free registration - Commission-based earnings', 1, 11)
ON DUPLICATE KEY UPDATE price = 0.00, currency = 'EUR';

-- Translator (Free)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order)
VALUES ('Translator', 'translator', 0.00, 'EUR', 'free', 'No subscription - Paid per completed review', 1, 12)
ON DUPLICATE KEY UPDATE price = 0.00, currency = 'EUR';

-- Government (Custom)
INSERT IGNORE INTO subscription_plans (name, slug, price, currency, billing_period, description, is_active, sort_order, is_custom)
VALUES ('Government', 'government', NULL, 'EUR', 'custom', 'By invitation only - Custom contractual pricing', 1, 13, 1)
ON DUPLICATE KEY UPDATE is_custom = 1;

-- عرض الخطط المحدثة
SELECT '=== Updated Plans ===' as info;
SELECT 
    id,
    name,
    slug,
    CASE 
        WHEN price IS NULL THEN 'Custom'
        ELSE CONCAT(price, ' ', currency)
    END as price_display,
    billing_period,
    description,
    tokens_limit,
    is_popular,
    is_custom,
    is_active
FROM subscription_plans
ORDER BY sort_order, price;

-- ملخص التغييرات
SELECT '=== Price Summary by Category ===' as info;
SELECT 
    CASE 
        WHEN slug LIKE '%free%' OR slug LIKE '%affiliate%' OR slug LIKE '%translator%' THEN 'Free Accounts'
        WHEN slug LIKE '%partner%' THEN 'Partner Plans'
        WHEN slug LIKE '%university%' THEN 'University Plans'
        WHEN slug LIKE '%government%' THEN 'Government Plans'
        ELSE 'Customer Plans'
    END as category,
    COUNT(*) as plan_count,
    MIN(price) as min_price,
    MAX(price) as max_price
FROM subscription_plans
WHERE is_active = 1
GROUP BY category
ORDER BY category;
