# Partner Registry API Documentation

## Overview
The Partner Registry API provides public access to certified translation partners.

## Authentication
No authentication required for public endpoints.

## Endpoints

### 1. Get Partner Registry
Get a list of all active, public partners.

**Endpoint:** `GET /api/partners/registry`

**Query Parameters:**
- `certification` (optional): Filter by certification level (gold, silver, bronze)
- `specialization` (optional): Filter by specialization
- `source_language` (optional): Filter by source language
- `target_language` (optional): Filter by target language
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Results per page (default: 20, max: 100)

**Example Request:**
```
GET /api/partners/registry?certification=gold&source_language=ar&target_language=en
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "name": "Elite Translation Services",
      "description": "Professional translation agency",
      "logo": "https://example.com/logo.png",
      "certification_level": "gold",
      "certified_at": "2024-01-15T00:00:00Z",
      "specializations": ["legal", "medical", "technical"],
      "language_pairs": [
        {"source": "ar", "target": "en"},
        {"source": "en", "target": "ar"}
      ],
      "rating": {
        "overall": 4.9,
        "quality": 4.95,
        "speed": 4.85,
        "communication": 4.90,
        "total_reviews": 450
      },
      "stats": {
        "total_projects": 2500,
        "completed_projects": 2450,
        "success_rate": 98.0
      },
      "verified": true,
      "member_since": "2022-03-01T00:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8
  }
}
```

### 2. Get Partner Details
Get detailed information about a specific partner.

**Endpoint:** `GET /api/partners/registry/{partnerId}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 123,
    "name": "Elite Translation Services",
    "description": "Professional translation agency with 10+ years experience",
    "logo": "https://example.com/logo.png",
    "certification_level": "gold",
    "certified_at": "2024-01-15T00:00:00Z",
    "specializations": ["legal", "medical", "technical"],
    "language_pairs": [...],
    "rating": {...},
    "stats": {...},
    "verified": true,
    "member_since": "2022-03-01T00:00:00Z"
  }
}
```

### 3. Get Certified Partners Only
Get only certified partners.

**Endpoint:** `GET /api/partners/certified`

**Query Parameters:**
Same as registry endpoint.

**Response:**
Same structure as registry endpoint, but filtered to certified partners only.

## Certification Levels

- **Gold**: Top-tier partners with exceptional performance
- **Silver**: High-quality partners with proven track record
- **Bronze**: Certified partners meeting quality standards

## Partner Rating System

Partners are rated on:
- **Quality**: Translation accuracy and completeness
- **Speed**: Delivery time performance
- **Communication**: Responsiveness and clarity
- **Overall**: Weighted average of all metrics

## Filtering Examples

**By Certification:**
```
GET /api/partners/registry?certification=gold
```

**By Language Pair:**
```
GET /api/partners/registry?source_language=ar&target_language=en
```

**By Specialization:**
```
GET /api/partners/registry?specialization=legal
```

**Combined Filters:**
```
GET /api/partners/registry?certification=gold&specialization=legal&source_language=ar
```

## Use Cases

1. **Client Partner Selection**: Help clients find qualified partners
2. **Partner Directory**: Display certified partners on your website
3. **Integration**: Build tools that recommend partners based on criteria
4. **Analytics**: Track partner marketplace trends

## Support
For API support, contact: api-support@culturaltranslate.com
