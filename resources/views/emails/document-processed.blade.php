<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Document Translation is Ready!</title>
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
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
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
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .stats {
            background: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
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
        .certificate-badge {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Your Certified Translation is Ready!</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $userName }}!</h2>
            
            <p>Great news! Your official document translation has been completed and certified.</p>
            
            <div class="stats">
                <h3 style="margin-top: 0; color: #1d4ed8;">📄 Document Details:</h3>
                
                <div class="stat-item">
                    <span><strong>Document Type:</strong></span>
                    <span>{{ ucwords(str_replace('_', ' ', $documentType)) }}</span>
                </div>
                
                <div class="stat-item">
                    <span><strong>Original File:</strong></span>
                    <span>{{ $documentName }}</span>
                </div>
                
                <div class="stat-item">
                    <span><strong>From Language:</strong></span>
                    <span>{{ strtoupper($sourceLang) }}</span>
                </div>
                
                <div class="stat-item">
                    <span><strong>To Language:</strong></span>
                    <span>{{ strtoupper($targetLang) }}</span>
                </div>
            </div>
            
            <div class="certificate-badge">
                <strong>🔐 Certificate ID:</strong> {{ $certificateId }}<br>
                <small style="color: #92400e;">Use this ID to verify your document's authenticity</small>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $downloadUrl }}" class="button">📥 Download Certified Translation</a>
            </div>
            
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <strong>💡 Important:</strong> Your certified translation includes:
            </p>
            <ul style="color: #666; line-height: 1.8;">
                <li>Official certification seal</li>
                <li>QR code for verification</li>
                <li>Unique certificate ID</li>
                <li>Legal statement of accuracy</li>
            </ul>
            
            <p style="margin-top: 20px;">
                You can verify your document anytime at: 
                <a href="{{ url('/verify/' . $certificateId) }}" style="color: #2563eb;">
                    {{ url('/verify/' . $certificateId) }}
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} CulturalTranslate. All rights reserved.</p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/official-documents/my-documents') }}" style="color: #2563eb; text-decoration: none;">View All My Documents</a>
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                If you have any questions, please contact us at support@culturaltranslate.com
            </p>
        </div>
    </div>
</body>
</html>
