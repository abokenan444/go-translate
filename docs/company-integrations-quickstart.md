# Company Integrations Quickstart

## Overview
Enable per-company integrations, API keys, webhooks, and feature flags from the admin panel.

## Admin Setup
- Companies: `Admin → إدارة الشركات → الشركات`
- Integrations: `Admin → إدارة الشركات → تكاملات الشركات`
- API Keys: `Admin → إدارة الشركات → مفاتيح API للشركات`

## Create API Key
1. Go to `مفاتيح API للشركات` → Create.
2. Select company, set name, scopes (e.g., `translate:write`), rate limit.
3. Copy the generated `key`.

## Call Company API
- Header: `X-Company-Key: <key>`
- Translate:
```
POST /api/company/{companyId}/translate
Content-Type: application/json
X-Company-Key: YOUR_KEY
{
  "text": "Hello",
  "source_language": "en",
  "target_language": "ar"
}
```

## Webhooks
- Endpoint per provider:
```
POST /api/company/{companyId}/webhooks/{provider}
X-Integration-Signature: HMAC-SHA256(body, api_secret)
```
- Configure `webhook_url` and `api_secret` in Integration.

## Feature Flags
- Company settings (`company_settings`): `enabled_features`, `allowed_models`, `rate_limit_per_minute`.
- Used by services to tailor behavior per company.

## Security
- Keys can be revoked / expire.
- Requests without valid key receive 401.

## Troubleshooting
- 401: Check key validity/revocation/expiry.
- 404 Webhook: Ensure integration exists and is active.
- Rate limit: Adjust per company in API key or settings.
