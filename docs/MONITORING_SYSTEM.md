# Real-Time Monitoring System

## Overview
Comprehensive real-time monitoring system for tracking platform health, performance, and usage metrics.

## Features

### 1. System Health Monitoring
- Database connectivity and performance
- Redis cache status
- Queue worker status
- Storage availability
- External API health (OpenAI, Claude, etc.)

### 2. Performance Metrics
- Request/response times
- Database query performance
- API endpoint latency
- Queue job processing times

### 3. Usage Metrics
- Active users count
- Active translations
- API usage statistics
- Resource utilization

### 4. Error Tracking
- Error rate monitoring
- Failed job tracking
- Exception logging
- Alert thresholds

## Configuration

### Environment Variables
```env
MONITORING_ENABLED=true
ALERTS_ENABLED=true
SLACK_WEBHOOK_URL=https://hooks.slack.com/...
ALERT_EMAIL_RECIPIENTS=admin@example.com
TRACK_QUERIES=true
TRACK_REQUESTS=true
SLOW_QUERY_THRESHOLD=1000
```

### Thresholds Configuration
Edit `config/monitoring.php`:

```php
'thresholds' => [
    'response_time' => ['max' => 1000], // ms
    'error_rate' => ['max' => 5], // percentage
    'cpu_usage' => ['max' => 80], // percentage
    'memory_usage' => ['max' => 85], // percentage
    'disk_usage' => ['max' => 90], // percentage
    'queue_size' => ['max' => 1000], // jobs
    'failed_jobs' => ['max' => 100],
],
```

## Usage

### In Code
```php
use App\Services\RealTimeMonitoringService;

// Track a metric
$monitoring = app(RealTimeMonitoringService::class);
$monitoring->trackMetric('translation_completed', 1, [
    'language_pair' => 'ar-en',
    'duration' => 245
]);

// Get system status
$status = $monitoring->getSystemStatus();

// Get metrics for last hour
$metrics = $monitoring->getMetrics('response_time', 60);
```

### API Endpoints
```
GET /api/v1/health - System health check
```

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2024-12-19T10:30:00Z",
  "services": {
    "database": {
      "status": "up",
      "latency_ms": 2.5,
      "connections": 45
    },
    "redis": {
      "status": "up",
      "latency_ms": 1.2,
      "used_memory": "128MB"
    },
    "queue": {
      "status": "up",
      "pending_jobs": 15,
      "failed_jobs": 2
    },
    "storage": {
      "status": "up",
      "free_space_gb": 250.5,
      "used_percent": 45.2
    }
  },
  "metrics": {
    "active_users": 234,
    "active_translations": 45,
    "avg_response_time": 125,
    "error_rate": 0.5
  }
}
```

### Dashboard
Access the monitoring dashboard at:
```
/admin/monitoring
```

Features:
- Real-time metrics display
- Interactive charts
- Service health status
- Alert history
- Performance trends

### Scheduled Tasks
Metrics are collected automatically:
```
* * * * * php artisan schedule:run
```

Collects:
- System metrics every minute
- Performance data every 5 minutes
- Health checks every minute
- Cleanup old metrics hourly

## Alerts

### Alert Channels
- Slack notifications
- Email alerts
- In-app notifications
- SMS (coming soon)

### Alert Types
- **Critical**: Immediate action required
- **Warning**: Attention needed
- **Info**: Informational only

### Alert Conditions
```php
// Automatic alerts when:
- Response time > 1000ms
- Error rate > 5%
- CPU usage > 80%
- Memory usage > 85%
- Disk usage > 90%
- Queue size > 1000
- Failed jobs > 100
```

## Real-Time Updates

### WebSocket Integration
```javascript
import { monitor } from '@/monitoring';

// Subscribe to metric updates
monitor.subscribe('metric.updated', (metric) => {
    console.log('Metric updated:', metric);
});

// Subscribe to system events
monitor.subscribe('document.status', (event) => {
    console.log('Document status changed:', event);
});
```

### Broadcasting
Uses Laravel Broadcasting with Pusher/Redis:
```
System Metrics Channel: system-metrics
User Private Channel: user.{userId}
Translator Channel: translator.{userId}
Partner Channel: partner.{partnerId}
```

## Maintenance

### Cleanup Old Data
```bash
# Cleanup metrics older than 7 days
php artisan monitoring:cleanup --days=7

# Cleanup evidence chain
php artisan evidence:cleanup-old --days=365
```

### Optimize Performance
```bash
# Clear monitoring cache
php artisan cache:forget monitoring:*

# Rebuild metrics indexes
php artisan monitoring:rebuild-indexes
```

## Best Practices

1. **Set Appropriate Thresholds**: Adjust based on your platform's normal behavior
2. **Monitor Trends**: Look for patterns over time, not just current values
3. **Alert Fatigue**: Don't over-alert; focus on actionable issues
4. **Regular Reviews**: Review metrics weekly to identify improvement opportunities
5. **Capacity Planning**: Use metrics to plan infrastructure scaling

## Troubleshooting

### High Memory Usage
```bash
# Check Redis memory
php artisan redis:info

# Clear unnecessary caches
php artisan cache:clear
php artisan view:clear
```

### Slow Queries
```bash
# Enable query logging
TRACK_QUERIES=true

# Check slow query log
tail -f storage/logs/queries.log
```

### Failed Jobs
```bash
# Retry failed jobs
php artisan queue:retry all

# Check failed jobs
php artisan queue:failed
```

## Support
For monitoring support, contact: monitoring@culturaltranslate.com
