-- تحديث أسعار الاشتراكات للتوافق مع الأسعار الأوروبية الجديدة
-- Update Subscription Prices to Match New European Pricing

USE cultural_translate;

-- Customer Plans (EUR Pricing)
UPDATE subscription_plans SET price = 0.00 WHERE slug = 'customer-free' AND account_type = 'customer';
UPDATE subscription_plans SET price = 19.00 WHERE slug = 'customer-starter' AND account_type = 'customer';
UPDATE subscription_plans SET price = 49.00 WHERE slug = 'customer-pro' AND account_type = 'customer';
UPDATE subscription_plans SET price = 99.00 WHERE slug = 'customer-business' AND account_type = 'customer';

-- Partner Plans (EUR Pricing)
UPDATE subscription_plans SET price = 99.00 WHERE slug = 'partner-basic' AND account_type = 'partner';
UPDATE subscription_plans SET price = 249.00 WHERE slug = 'partner-pro' AND account_type = 'partner';
UPDATE subscription_plans SET price = NULL WHERE slug = 'partner-enterprise' AND account_type = 'partner'; -- Custom pricing

-- University Plans (EUR Pricing)
UPDATE subscription_plans SET price = 99.00 WHERE slug = 'university-basic' AND account_type = 'university';
UPDATE subscription_plans SET price = 199.00 WHERE slug = 'university-research' AND account_type = 'university';
UPDATE subscription_plans SET price = 399.00 WHERE slug = 'university-institutional' AND account_type = 'university';

-- Affiliate (Free - Commission Based)
UPDATE subscription_plans SET price = 0.00 WHERE slug = 'affiliate-free' AND account_type = 'affiliate';

-- Translator (Free - Per Job Payment)
UPDATE subscription_plans SET price = 0.00 WHERE slug = 'translator-free' AND account_type = 'translator';

-- Government (Custom Contractual Pricing)
UPDATE subscription_plans SET price = NULL WHERE account_type = 'government';

-- Update currency to EUR if not already set
UPDATE subscription_plans SET currency = 'EUR' WHERE currency IS NULL OR currency = 'USD';

-- Add descriptions where missing

-- Customer Plans
UPDATE subscription_plans 
SET description = 'Perfect for testing - Limited AI translations with basic features and community support'
WHERE slug = 'customer-free';

UPDATE subscription_plans 
SET description = 'For individuals - 50,000 tokens/month with AI translations and email support'
WHERE slug = 'customer-starter';

UPDATE subscription_plans 
SET description = 'For professionals - 200,000 tokens/month with AI + Cultural adaptation and priority support'
WHERE slug = 'customer-pro';

UPDATE subscription_plans 
SET description = 'For small teams - High-volume processing with limited API access and dedicated support'
WHERE slug = 'customer-business';

-- Partner Plans
UPDATE subscription_plans 
SET description = 'Team management with basic reporting'
WHERE slug = 'partner-basic';

UPDATE subscription_plans 
SET description = 'Advanced reporting with API access'
WHERE slug = 'partner-pro';

UPDATE subscription_plans 
SET description = 'Custom enterprise solutions - Contact sales for pricing'
WHERE slug = 'partner-enterprise';

-- University Plans
UPDATE subscription_plans 
SET description = 'For academic departments - Basic access for educational use'
WHERE slug = 'university-basic';

UPDATE subscription_plans 
SET description = 'For research teams - Enhanced access with research-grade features'
WHERE slug = 'university-research';

UPDATE subscription_plans 
SET description = 'Full university access - Institution-wide licensing'
WHERE slug = 'university-institutional';

-- Affiliate
UPDATE subscription_plans 
SET description = 'Free registration - Earn commission by referring customers with no subscription fees'
WHERE slug = 'affiliate-free';

-- Translator
UPDATE subscription_plans 
SET description = 'No subscription fee - Paid per completed review with platform commission'
WHERE slug = 'translator-free';

-- Show updated prices
SELECT 
    account_type,
    name,
    slug,
    CONCAT(COALESCE(price, 'Custom'), ' ', currency) as price_display,
    billing_period,
    description
FROM subscription_plans
ORDER BY 
    FIELD(account_type, 'customer', 'affiliate', 'translator', 'partner', 'university', 'government'),
    price ASC;

-- Summary Report
SELECT 
    account_type,
    COUNT(*) as plan_count,
    MIN(price) as min_price,
    MAX(price) as max_price,
    AVG(price) as avg_price
FROM subscription_plans
WHERE price IS NOT NULL
GROUP BY account_type
ORDER BY account_type;
