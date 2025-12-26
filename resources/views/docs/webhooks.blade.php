@extends('docs.layout')

@section('title', 'Webhooks')

@section('content')
    <h1>Webhooks</h1>
    <p>Receive real-time notifications when events occur in Cultural Translate.</p>

    <h2>Setting Up Webhooks</h2>

    <h3>Via Web Interface</h3>
    <ol>
        <li>Go to Project Settings → Webhooks</li>
        <li>Click "Add Webhook"</li>
        <li>Enter endpoint URL</li>
        <li>Select events to subscribe to</li>
        <li>Save</li>
    </ol>

    <h3>Via API</h3>
    <pre><code>POST /api/v1/webhooks
{
  "url": "https://yourapp.com/webhooks/gotranslate",
  "events": [
    "translation.completed",
    "translation.failed",
    "project.updated"
  ],
  "secret": "your_webhook_secret",
  "active": true
}</code></pre>

    <h2>Available Events</h2>

    <h3>Translation Events</h3>
    <ul>
        <li><code>translation.created</code> - New translation requested</li>
        <li><code>translation.completed</code> - Translation finished</li>
        <li><code>translation.failed</code> - Translation failed</li>
        <li><code>translation.updated</code> - Translation edited</li>
        <li><code>translation.approved</code> - Translation approved</li>
    </ul>

    <h3>Project Events</h3>
    <ul>
        <li><code>project.created</code> - New project created</li>
        <li><code>project.updated</code> - Project settings changed</li>
        <li><code>project.deleted</code> - Project removed</li>
    </ul>

    <h3>File Events</h3>
    <ul>
        <li><code>file.uploaded</code> - File uploaded for translation</li>
        <li><code>file.completed</code> - File translation finished</li>
        <li><code>file.exported</code> - Translated file exported</li>
    </ul>

    <h2>Webhook Payload</h2>
    <p>All webhooks include:</p>
    <pre><code>{
  "event": "translation.completed",
  "timestamp": "2025-12-04T21:30:00Z",
  "data": {
    "id": "trans_123",
    "project_id": "proj_456",
    "source_text": "Hello",
    "translated_text": "مرحباً",
    "source_language": "en",
    "target_language": "ar",
    "status": "completed",
    "engine": "ai",
    "created_at": "2025-12-04T21:29:45Z",
    "completed_at": "2025-12-04T21:30:00Z"
  }
}</code></pre>

    <h2>Security</h2>

    <h3>Signature Verification</h3>
    <p>Verify webhook authenticity using HMAC signatures:</p>
    <pre><code>// PHP Example
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_GOTRANSLATE_SIGNATURE'];
$secret = 'your_webhook_secret';

$computed = hash_hmac('sha256', $payload, $secret);

if (!hash_equals($signature, $computed)) {
    http_response_code(401);
    die('Invalid signature');
}</code></pre>

    <h3>IP Whitelist</h3>
    <p>Cultural Translate webhooks come from these IPs:</p>
    <pre><code>145.14.158.101
185.199.108.0/22
192.30.252.0/22</code></pre>

    <h2>Retry Logic</h2>
    <p>Failed webhooks are retried with exponential backoff:</p>
    <ul>
        <li>1st retry: after 1 minute</li>
        <li>2nd retry: after 5 minutes</li>
        <li>3rd retry: after 15 minutes</li>
        <li>4th retry: after 1 hour</li>
        <li>5th retry: after 6 hours</li>
    </ul>

    <h2>Response Requirements</h2>
    <p>Your endpoint must:</p>
    <ul>
        <li>Return HTTP 2xx status code within 10 seconds</li>
        <li>Accept JSON content type</li>
        <li>Handle duplicate deliveries (idempotent)</li>
    </ul>

    <h2>Testing Webhooks</h2>
    <p>Send test webhook from the dashboard:</p>
    <pre><code>POST /api/v1/webhooks/{webhook_id}/test

Response:
{
  "success": true,
  "status_code": 200,
  "response_time_ms": 145
}</code></pre>

    <h2>Webhook Logs</h2>
    <p>View delivery history and debug failed webhooks:</p>
    <ul>
        <li>Last 30 days of webhook deliveries</li>
        <li>Request/response details</li>
        <li>Retry attempts</li>
        <li>Error messages</li>
    </ul>

    <div class="alert alert-warning">
        <strong>Best Practice:</strong> Always verify webhook signatures and handle duplicate deliveries gracefully.
    </div>
@endsection
