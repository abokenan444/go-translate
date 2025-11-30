# Stripe Setup Guide

This guide will walk you through setting up Stripe for your Cultural Translation platform.

## 🔧 Prerequisites

✅ **Completed:**
- Stripe PHP SDK installed (`stripe/stripe-php v19.0`)
- Database migrations run (subscriptions + payment_transactions tables)
- `.env` file configured with Stripe placeholders
- Payment gateway code implemented (StripeService, Controllers, Webhooks)

## 📋 Step-by-Step Setup

### 1. Create Stripe Account (5 minutes)

1. Go to https://stripe.com and click "Sign up"
2. Fill in your email, password, and company details
3. Verify your email address
4. You'll be in **Test Mode** by default (perfect for development)

### 2. Get Your API Keys (2 minutes)

1. Go to https://dashboard.stripe.com/test/apikeys
2. You'll see two keys:
   - **Publishable key** (starts with `pk_test_`)
   - **Secret key** (click "Reveal test key" - starts with `sk_test_`)

3. Update your `.env` file:
   ```env
   STRIPE_KEY=pk_test_51QW2...your_actual_publishable_key
   STRIPE_SECRET=sk_test_51QW2...your_actual_secret_key
   ```

### 3. Create Products and Prices (10 minutes)

#### Create Basic Plan ($29/month)

1. Go to https://dashboard.stripe.com/test/products
2. Click **"+ Add product"**
3. Fill in:
   - **Name:** Cultural Translation - Basic
   - **Description:** 100,000 tokens per month with cultural AI translation
   - **Pricing model:** Standard pricing
   - **Price:** $29.00 USD
   - **Billing period:** Monthly (Recurring)
4. Click **"Save product"**
5. Copy the **Price ID** (starts with `price_`) from the Pricing section
6. Update `.env`:
   ```env
   STRIPE_PRICE_BASIC=price_1QW2...your_basic_price_id
   ```

#### Create Pro Plan ($99/month)

1. Click **"+ Add product"** again
2. Fill in:
   - **Name:** Cultural Translation - Pro
   - **Description:** 500,000 tokens per month with advanced cultural AI features
   - **Pricing model:** Standard pricing
   - **Price:** $99.00 USD
   - **Billing period:** Monthly (Recurring)
3. Click **"Save product"**
4. Copy the **Price ID** and update `.env`:
   ```env
   STRIPE_PRICE_PRO=price_1QW2...your_pro_price_id
   ```

#### Create Enterprise Plan ($299/month)

1. Click **"+ Add product"** again
2. Fill in:
   - **Name:** Cultural Translation - Enterprise
   - **Description:** Unlimited tokens with enterprise AI features and dedicated support
   - **Pricing model:** Standard pricing
   - **Price:** $299.00 USD
   - **Billing period:** Monthly (Recurring)
3. Click **"Save product"**
4. Copy the **Price ID** and update `.env`:
   ```env
   STRIPE_PRICE_ENTERPRISE=price_1QW2...your_enterprise_price_id
   ```

### 4. Set Up Webhook Endpoint (5 minutes)

1. Go to https://dashboard.stripe.com/test/webhooks
2. Click **"+ Add endpoint"**
3. Enter your webhook URL:
   ```
   https://your-domain.com/stripe/webhook
   ```
   
   **For local testing with ngrok:**
   ```bash
   # Install ngrok: https://ngrok.com/download
   # Start your Laravel app first
   php artisan serve
   
   # In another terminal, start ngrok
   ngrok http 8000
   
   # Use the https URL ngrok provides
   https://abc123.ngrok.io/stripe/webhook
   ```

4. Select events to listen to:
   - ✅ `checkout.session.completed`
   - ✅ `customer.subscription.created`
   - ✅ `customer.subscription.updated`
   - ✅ `customer.subscription.deleted`
   - ✅ `invoice.payment_succeeded`
   - ✅ `invoice.payment_failed`
   - ✅ `customer.subscription.trial_will_end`

5. Click **"Add endpoint"**
6. Click on your newly created endpoint
7. Click **"Reveal"** under "Signing secret" (starts with `whsec_`)
8. Update `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_...your_webhook_secret
   ```

### 5. Final Configuration Check

Your `.env` file should now look like this:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_51QW2ABC123...your_publishable_key
STRIPE_SECRET=sk_test_51QW2ABC123...your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_ABC123...your_webhook_secret

# Stripe Price IDs
STRIPE_PRICE_BASIC=price_1QW2ABC123...basic_plan_price_id
STRIPE_PRICE_PRO=price_1QW2ABC123...pro_plan_price_id
STRIPE_PRICE_ENTERPRISE=price_1QW2ABC123...enterprise_plan_price_id
```

**Important:** Clear your config cache after updating `.env`:
```bash
php artisan config:clear
php artisan cache:clear
```

## 🧪 Testing Payment Flow

### Test Card Numbers (Stripe Provides These)

| Card Number         | Scenario                  |
|---------------------|---------------------------|
| 4242 4242 4242 4242 | ✅ Successful payment     |
| 4000 0000 0000 0002 | ❌ Card declined          |
| 4000 0025 0000 3155 | 🔒 Requires authentication|

**Use any:**
- Future expiry date (e.g., 12/34)
- Any 3-digit CVC (e.g., 123)
- Any valid ZIP code (e.g., 12345)

### Testing Steps

1. **Start your Laravel application:**
   ```bash
   php artisan serve
   ```

2. **Visit the pricing page:**
   ```
   http://localhost:8000/pricing
   ```

3. **Create a test user** (if you haven't already):
   ```bash
   php artisan tinker
   ```
   ```php
   $user = \App\Models\User::factory()->create([
       'email' => 'test@example.com',
       'password' => bcrypt('password')
   ]);
   ```

4. **Login** with your test user

5. **Click "Get Started"** on any plan

6. **Complete checkout** with test card `4242 4242 4242 4242`

7. **Verify subscription** in database:
   ```bash
   php artisan tinker
   ```
   ```php
   \App\Models\Subscription::latest()->first();
   \App\Models\PaymentTransaction::latest()->first();
   ```

8. **Check Stripe Dashboard:**
   - https://dashboard.stripe.com/test/payments (see payment)
   - https://dashboard.stripe.com/test/subscriptions (see subscription)

### Test Webhook Events

1. **Trigger a test webhook** from Stripe Dashboard:
   - Go to https://dashboard.stripe.com/test/webhooks
   - Click on your webhook endpoint
   - Click **"Send test webhook"**
   - Select event: `checkout.session.completed`
   - Click **"Send test webhook"**

2. **Check logs** for webhook processing:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## 🔍 Troubleshooting

### Issue: "Invalid API Key"
**Solution:** Double-check your `STRIPE_SECRET` in `.env` and run `php artisan config:clear`

### Issue: "No such price"
**Solution:** Verify price IDs in `.env` match those in Stripe Dashboard

### Issue: "Webhook signature verification failed"
**Solution:** Ensure `STRIPE_WEBHOOK_SECRET` is correct and the endpoint URL in Stripe matches your actual URL

### Issue: "CSRF token mismatch"
**Solution:** This is normal for webhooks. Verify `/stripe/webhook` is in `app/Http/Middleware/VerifyCsrfToken.php` `$except` array

### Issue: Local webhook testing not working
**Solution:** Use ngrok to expose your local server:
```bash
ngrok http 8000
# Update Stripe webhook URL to: https://abc123.ngrok.io/stripe/webhook
```

## 📊 Monitoring

### View Subscription Status
```bash
php artisan tinker
```
```php
// Get all active subscriptions
\App\Models\Subscription::where('status', 'active')->get();

// Check user's subscription
$user = \App\Models\User::find(1);
$user->subscription; // Current subscription
$user->subscription->isActive(); // true/false
$user->subscription->hasTokensAvailable(); // true/false
```

### View Payment Transactions
```php
// Latest payments
\App\Models\PaymentTransaction::latest()->take(10)->get();

// Successful payments
\App\Models\PaymentTransaction::where('status', 'succeeded')->get();
```

## 🚀 Going Live (Production)

When ready to accept real payments:

1. **Complete Stripe account verification**
   - Business details
   - Bank account information
   - Identity verification

2. **Switch to Live Mode** in Stripe Dashboard

3. **Get Live API keys:**
   - https://dashboard.stripe.com/apikeys
   - Keys will start with `pk_live_` and `sk_live_`

4. **Create products** in Live Mode (repeat Step 3 above)

5. **Update production `.env`:**
   ```env
   STRIPE_KEY=pk_live_...
   STRIPE_SECRET=sk_live_...
   STRIPE_WEBHOOK_SECRET=whsec_... (create new webhook in live mode)
   STRIPE_PRICE_BASIC=price_...
   STRIPE_PRICE_PRO=price_...
   STRIPE_PRICE_ENTERPRISE=price_...
   ```

6. **Set up webhook** for production domain

7. **Test thoroughly** with a small real payment before launching

## 📚 Additional Resources

- [Stripe Testing Guide](https://stripe.com/docs/testing)
- [Stripe Webhooks Documentation](https://stripe.com/docs/webhooks)
- [Stripe PHP SDK](https://github.com/stripe/stripe-php)
- [Stripe Customer Portal](https://stripe.com/docs/billing/subscriptions/integrating-customer-portal)

## ✅ Checklist

- [ ] Stripe account created
- [ ] API keys added to `.env`
- [ ] Three products created (Basic, Pro, Enterprise)
- [ ] Price IDs added to `.env`
- [ ] Webhook endpoint configured
- [ ] Webhook secret added to `.env`
- [ ] Config cache cleared (`php artisan config:clear`)
- [ ] Test payment completed successfully
- [ ] Subscription created in database
- [ ] Webhook events received and processed
- [ ] Customer portal tested

---

**Need help?** Check the implementation code in:
- `app/Services/Payment/StripeService.php`
- `app/Http/Controllers/StripeController.php`
- `app/Http/Controllers/StripeWebhookController.php`
