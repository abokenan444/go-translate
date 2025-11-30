<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .warning-box {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
            <h1>🔒 Reset Your Password</h1>
        </div>
        
        <div class="content">
            <h2>Hello, {{ $user->name }}</h2>
            
            <p>We received a request to reset your password for your CulturalTranslate account.</p>
            
            <p>Click the button below to reset your password:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>
            
            <p style="color: #999; font-size: 14px; margin-top: 20px;">
                Or copy and paste this link into your browser:<br>
                <a href="{{ $resetUrl }}" style="color: #667eea; word-break: break-all;">{{ $resetUrl }}</a>
            </p>
            
            <div class="warning-box">
                <strong>⚠️ Security Notice:</strong><br>
                This password reset link will expire in <strong>60 minutes</strong>.<br>
                If you didn't request this, please ignore this email or contact support if you have concerns.
            </div>
            
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <strong>Need help?</strong><br>
                Contact us at <a href="mailto:support@culturaltranslate.com">support@culturaltranslate.com</a>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} CulturalTranslate. All rights reserved.</p>
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                This is an automated security email. Please do not reply.
            </p>
        </div>
    </div>
</body>
</html>
