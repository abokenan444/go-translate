<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Translation Complete</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .stats {
            background: #f0fdf4;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>✅ Translation Complete!</h1>
        </div>
        
        <div class="content">
            <h2>Your translation is ready, {{ $user->name }}!</h2>
            
            <p>Great news! Your translation has been completed successfully.</p>
            
            <div class="stats">
                <h3 style="margin-top: 0; color: #059669;">📊 Translation Details:</h3>
                
                <div class="stat-item">
                    <span><strong>From:</strong></span>
                    <span>{{ $translation->source_language }}</span>
                </div>
                
                <div class="stat-item">
                    <span><strong>To:</strong></span>
                    <span>{{ $translation->target_language }}</span>
                </div>
                
                <div class="stat-item">
                    <span><strong>Characters:</strong></span>
                    <span>{{ number_format($translation->character_count) }}</span>
                </div>
                
                <div class="stat-item">
                    <span><strong>Model:</strong></span>
                    <span>{{ $translation->model }}</span>
                </div>
                
                <div class="stat-item">
                    <span><strong>Completed:</strong></span>
                    <span>{{ $translation->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/dashboard/history') }}" class="button">View Translation</a>
            </div>
            
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <strong>💡 Tip:</strong> You can download, share, or continue editing your translation from your dashboard.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} CulturalTranslate. All rights reserved.</p>
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                <a href="{{ url('/unsubscribe') }}" style="color: #999;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>
