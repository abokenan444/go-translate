# Cultural Translate — AI_CONTEXT

## Purpose of This File
This document is the **single source of truth** for all AI-assisted development, architecture, governance, and testing decisions related to the Cultural Translate platform.

Any AI system (ChatGPT-5.x or others) **must read this file first** before producing code, architecture, tests, or documentation.

---

## 1. Platform Identity & Vision

### Platform Name
**Cultural Translate**

### Core Identity
Cultural Translate is **not** a traditional translation company.

It is a:

> **Governed Cultural Intelligence & Certified Communication Platform**

The platform operates as a **trust infrastructure** for cross-border, institutional, and legally sensitive communication.

---

## 2. Dual Operating Model (Critical)

### A) Open Cultural Translation (Non-Regulated)
- Available to all users
- AI-driven cultural translation
- For:
  - Marketing
  - Websites
  - Content
  - Non-official documents
- No certification
- No legal liability
- Web-first

This model **remains active** and is considered a secondary, volume-based service.

---

### B) Governed & Certified Translation (Regulated Workflow)
- Used for:
  - Legal documents
  - Immigration documents
  - Government-related content
  - Institutional communication
- AI translation + mandatory human review
- Identity-bound reviewer
- Audit trail
- Certified PDF output
- Verification via QR + public verification page

This model defines the **future and strategic core** of the platform.

---

## 3. Governance Principles (Non-Negotiable)

1. The platform **does NOT claim sovereign authority**
2. The platform **does NOT replace governments**
3. Certification authority is delegated to:
   - Verified partners
   - Licensed sworn/legal translators
4. Cultural Translate:
   - Orchestrates
   - Verifies
   - Documents
   - Audits
   - Does NOT self-certify

This separation is mandatory for legal protection and scalability.

---

## 4. CTS™ — Cultural Translation Standard

CTS™ is an internal governance framework, not a legal license.

It defines:
- Context validation
- Cultural risk mitigation
- Quality scoring
- Mandatory review checkpoints
- Certificate structure

CTS™ versions:
- v1: Operational
- v2+: Expanded governance & reporting

CTS™ is extensible but must remain **internally controlled**.

---

## 5. Partner Governance Model (Critical)

### Partner Types
- Certified Translators
- Legal Translation Agencies
- Court/Sworn Translators
- Institutional Language Partners

### Mandatory Partner Requirements
- Identity verification (KYC)
- License number
- Issuing authority
- License issue & expiry dates
- License document upload
- Country & jurisdiction binding
- Manual admin verification

No partner receives:
- Seal access
- Review permissions
- Certification rights

Without verification.

---

## 6. Smart Assignment Engine

### Assignment Logic
- Country-based
- Jurisdiction-based
- Language-pair matching
- SLA-aware

### Rules
- Parallel offer: **2 partners**
- Acceptance window: **60 minutes**
- Max reassignment attempts: **7**
- First acceptance timestamp wins
- Automatic rejection notice for second accepter
- Timeout → reassignment

All actions are logged and immutable.

---

## 7. Human Review Model

- AI performs translation
- Humans review, approve, reject, or request revision
- Humans do NOT:
  - Edit original AI text directly
  - Generate seals
  - Issue certificates manually

Human decisions are:
- Timestamped
- Device-bound
- Identity-bound
- Legally attributable

---

## 8. Certification & Seals

### Seals
- CTS™ Seal (platform)
- Partner Seal (licensed entity)

### Technical Rules
- Seals are **dynamic SVG**, not static images
- Injected server-side only
- Contain:
  - Certificate ID
  - Partner reference
  - Date
  - Verification URL (QR)

No seal generation occurs on client or mobile apps.

---

## 9. Mobile Applications Strategy

### No Consumer Mobile App
- Users use web platform only

### Partner Mobile App (Operational Tool)
Mobile apps are used **only** for:
- Certified partners
- Human reviewers

Purpose:
- Push notifications
- Time-bound accept/reject
- Secure confirmation
- Biometric identity validation

The app is:
- Task-based
- Minimal
- No admin
- No settings
- No document uploads
- No certificate generation

---

## 10. Technology Stack (Guidelines)

### Backend
- Laravel (v12+)
- Web-first
- Multi-subdomain aware
- API-driven

### Frontend
- Web: Blade / Inertia / SPA (project choice)
- Mobile: Flutter or React Native (future)

### Security
- reCAPTCHA (prod only)
- Rate limiting
- Input sanitization
- Full audit logs
- Device binding
- 2FA for partners

---

## 11. Testing Strategy (Mandatory)

### Test Layers
- Unit tests
- Feature tests (Laravel)
- Integration tests (PDF, seals, verification)
- E2E tests (Playwright)
- Load tests (k6)

### Rules
- No real LLM calls in tests
- Translation service must be mockable
- reCAPTCHA disabled in testing
- Stripe test mode only

### Coverage Targets
- User registration/login
- Partner onboarding
- Assignment flow
- Parallel acceptance logic
- Certification generation
- Verification page
- Permission boundaries

---

## 12. Legal Positioning

- Platform acts as:
  - Technical orchestrator
  - Audit provider
  - Trust layer
- NOT as:
  - Sworn authority
  - Government agency
  - Legal certifier by itself

All legal responsibility is:
- Shared
- Documented
- Delegated to verified partners

---

## 13. Launch Status

Current phase:
- **Advanced Pre-Launch**

Allowed:
- Partner outreach
- Pilot programs
- Government discussions
- NDA-based demos

Not allowed:
- Claims of global authority recognition
- Unverified numbers
- Sovereign representation claims

---

## 14. Instruction to AI Assistants

When working on this project, you MUST:
1. Respect governance separation
2. Avoid legal overclaims
3. Maintain auditability
4. Prefer explicit workflows over automation
5. Assume institutional scrutiny

Any suggestion violating these principles must be rejected.

---

## End of Context
