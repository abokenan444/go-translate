# Cultural Translate Platform - API Reference

## Government Verification API

### Base URL
```
https://api.culturaltranslate.com/api/government
```

### Authentication
All requests require the following headers:
```
X-API-Key: your_government_api_key
X-Government-ID: your_government_id
```

### Rate Limits
- 1000 requests per hour per government entity

---

## Endpoints

### 1. Verify Document
**POST** `/verify-document`

Verify a completed translation document.

#### Request Body
```json
{
  "document_id": "DOC-2024-001",
  "government_id": "GOV_SA_001",
  "api_key": "your_api_key",
  "verification_data": {
    "verified_by": "Ministry of Justice",
    "verification_method": "digital_signature",
    "notes": "Document verified and approved"
  }
}
```

#### Response
```json
{
  "success": true,
  "verification_id": 12345,
  "document_id": "DOC-2024-001",
  "status": "verified",
  "verified_at": "2024-12-19T10:30:00Z"
}
```

---

### 2. Get Document Status
**GET** `/document/{documentId}/status`

Get the current status of a document.

#### Headers
```
X-API-Key: your_api_key
X-Government-ID: your_government_id
```

#### Response
```json
{
  "success": true,
  "document_id": "DOC-2024-001",
  "status": "completed",
  "government_verified": true,
  "submitted_at": "2024-12-19T08:00:00Z",
  "completed_at": "2024-12-19T10:00:00Z",
  "translator_id": 123
}
```

---

### 3. Get Verification Statistics
**GET** `/stats`

Get verification statistics for your government entity.

#### Response
```json
{
  "success": true,
  "stats": {
    "total_verifications": 1500,
    "verified_today": 25,
    "verified_this_month": 450,
    "pending_verifications": 10
  }
}
```

---

## Partner Registry API

### Base URL
```
https://api.culturaltranslate.com/api/partners
```

### No Authentication Required (Public API)

---

### 1. Get Partner Registry
**GET** `/registry`

Get list of public partners.

#### Query Parameters
- `certification` - Filter by certification level (bronze, silver, gold, platinum)
- `specialization` - Filter by specialization (legal, medical, technical, business)
- `source_language` - Filter by source language
- `target_language` - Filter by target language

#### Example
```
GET /registry?certification=gold&specialization=legal
```

#### Response
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Legal Translations Pro",
      "certification_level": "gold",
      "overall_rating": 4.85,
      "specializations": ["legal", "business"],
      "language_pairs": [
        {"source": "en", "target": "ar"},
        {"source": "ar", "target": "en"}
      ],
      "total_projects": 500,
      "success_rate": 98.5
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 50,
    "last_page": 3
  }
}
```

---

### 2. Get Partner Details
**GET** `/registry/{partnerId}`

Get detailed information about a specific partner.

#### Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Legal Translations Pro",
    "description": "Specialized in legal document translations",
    "certification_level": "gold",
    "certified_at": "2024-01-15T00:00:00Z",
    "rating": {
      "overall": 4.85,
      "quality": 4.90,
      "speed": 4.80,
      "communication": 4.85,
      "total_reviews": 250
    },
    "stats": {
      "total_projects": 500,
      "completed_projects": 493,
      "success_rate": 98.6
    },
    "verified": true,
    "member_since": "2023-06-01T00:00:00Z"
  }
}
```

---

### 3. Get Certified Partners Only
**GET** `/certified`

Get list of certified partners only.

#### Response
Same structure as `/registry` but only includes partners with certification levels.

---

## Monitoring API

### Base URL
```
https://api.culturaltranslate.com/api/v1
```

### Authentication
Requires authentication via Sanctum token.

---

### Get System Health
**GET** `/health`

Get current system health status.

#### Response
```json
{
  "timestamp": "2024-12-19T10:30:00Z",
  "health": "healthy",
  "services": {
    "database": {
      "status": "up",
      "latency_ms": 5.2,
      "connections": 10
    },
    "redis": {
      "status": "up",
      "latency_ms": 1.5,
      "used_memory": "250MB"
    },
    "queue": {
      "status": "up",
      "pending_jobs": 25,
      "failed_jobs": 2
    },
    "storage": {
      "status": "up",
      "free_space_gb": 150.5,
      "used_percent": 45.2
    }
  },
  "metrics": {
    "active_users": 120,
    "active_translations": 45,
    "avg_response_time": 250,
    "error_rate": 0.5
  }
}
```

---

## Error Responses

### Authentication Error
```json
{
  "success": false,
  "message": "Invalid API key",
  "code": 401
}
```

### Rate Limit Exceeded
```json
{
  "success": false,
  "message": "Too many requests",
  "retry_after": 3600,
  "code": 429
}
```

### Validation Error
```json
{
  "success": false,
  "errors": {
    "document_id": ["The document id field is required."]
  },
  "code": 422
}
```

### Not Found
```json
{
  "success": false,
  "message": "Document not found",
  "code": 404
}
```

### Server Error
```json
{
  "success": false,
  "message": "Internal server error",
  "code": 500
}
```

---

## Best Practices

### 1. Error Handling
Always check the `success` field in responses:
```javascript
if (response.success) {
  // Handle success
} else {
  // Handle error
  console.error(response.message);
}
```

### 2. Rate Limiting
Monitor rate limit headers:
```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 995
```

### 3. Caching
- Cache partner registry data for 1 hour
- Cache verification statistics for 5 minutes
- Don't cache document status

### 4. Retry Logic
Implement exponential backoff for failed requests:
```javascript
const delay = Math.min(1000 * Math.pow(2, retryCount), 30000);
```

---

## Support

For API support or to request access:
- Email: api@culturaltranslate.com
- Documentation: https://docs.culturaltranslate.com
- Status: https://status.culturaltranslate.com
