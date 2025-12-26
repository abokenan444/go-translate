# Government API Documentation

## Overview
The Government API allows government entities to verify and track translation documents in real-time.

## Authentication
All requests require:
- `X-API-Key`: Your government API key
- `X-Government-ID`: Your government entity ID

## Rate Limiting
- 1000 requests per hour per government entity
- Rate limit headers included in responses

## Endpoints

### 1. Verify Document
Verify a completed translation document.

**Endpoint:** `POST /api/government/verify-document`

**Request Body:**
```json
{
  "document_id": "DOC-2024-001",
  "government_id": "GOV_SA_001",
  "api_key": "your_api_key",
  "verification_data": {
    "verified_by": "Ministry of Justice",
    "verification_method": "digital_signature",
    "notes": "Document verified successfully"
  }
}
```

**Response:**
```json
{
  "success": true,
  "verification_id": 123,
  "document_id": "DOC-2024-001",
  "status": "verified",
  "verified_at": "2024-12-19T10:30:00Z"
}
```

### 2. Get Document Status
Check the status of a translation document.

**Endpoint:** `GET /api/government/document/{documentId}/status`

**Headers:**
- `X-API-Key`: your_api_key
- `X-Government-ID`: GOV_SA_001

**Response:**
```json
{
  "success": true,
  "document_id": "DOC-2024-001",
  "status": "completed",
  "government_verified": true,
  "submitted_at": "2024-12-18T08:00:00Z",
  "completed_at": "2024-12-18T16:30:00Z",
  "translator_id": 456
}
```

### 3. Get Statistics
Get verification statistics for your government entity.

**Endpoint:** `GET /api/government/stats`

**Headers:**
- `X-API-Key`: your_api_key
- `X-Government-ID`: GOV_SA_001

**Response:**
```json
{
  "success": true,
  "stats": {
    "total_verifications": 1250,
    "verified_today": 45,
    "verified_this_month": 890,
    "pending_verifications": 12
  }
}
```

## Error Codes

- **401 Unauthorized**: Invalid API key or Government ID
- **404 Not Found**: Document not found
- **422 Validation Error**: Invalid request data
- **429 Too Many Requests**: Rate limit exceeded
- **500 Server Error**: Internal server error

## Webhooks (Coming Soon)
Register webhook URLs to receive real-time notifications for document events.

## Support
For API support, contact: api-support@culturaltranslate.com
