<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CulturalTranslate</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .features {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .feature-item {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .feature-item:last-child {
            border-bottom: none;
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🌍 CulturalTranslate</h1>
        </div>
        
        <div class="content">
            <h2>Welcome, {{ $user->name }}! 👋</h2>
            
            <p>Thank you for joining <strong>CulturalTranslate</strong> - the world's most advanced AI-powered cultural translation platform.</p>
            
            <p>We're excited to help you break language barriers while preserving cultural nuances and context.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="button">Get Started</a>
            </div>
            
            <div class="features">
                <h3 style="margin-top: 0; color: #333;">✨ What you can do:</h3>
                
                <div class="feature-item">
                    <strong>🌐 Cultural Translation</strong><br>
                    Translate content while preserving cultural context and nuances
                </div>
                
                <div class="feature-item">
                    <strong>🎙️ Real-Time Voice Translation</strong><br>
                    Live voice translation for meetings and calls
                </div>
                
                <div class="feature-item">
                    <strong>🔗 Integrations</strong><br>
                    Connect with WordPress, GitHub, WooCommerce, and more
                </div>
                
                <div class="feature-item">
                    <strong>📊 Analytics Dashboard</strong><br>
                    Track your translation usage and performance
                </div>
            </div>
            
            <p><strong>Need help?</strong> Our support team is here for you:</p>
            <ul>
                <li>📧 Email: <a href="mailto:support@culturaltranslate.com">support@culturaltranslate.com</a></li>
                <li>📚 Documentation: <a href="{{ url('/docs') }}">culturaltranslate.com/docs</a></li>
                <li>💬 Live Chat: Available in your dashboard</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} CulturalTranslate. All rights reserved.</p>
            <p>
                <a href="{{ url('/') }}">Home</a> | 
                <a href="{{ url('/features') }}">Features</a> | 
                <a href="{{ url('/pricing') }}">Pricing</a> | 
                <a href="{{ url('/contact') }}">Contact</a>
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                You're receiving this email because you signed up for CulturalTranslate.<br>
                <a href="{{ url('/unsubscribe') }}" style="color: #999;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>
