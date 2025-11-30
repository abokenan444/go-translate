<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmation</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .plan-details {
            background: #fffbeb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #fef3c7;
        }
        .detail-row:last-child {
            border-bottom: none;
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
            <h1>🎉 Subscription Confirmed!</h1>
        </div>
        
        <div class="content">
            <h2>Thank you, {{ $user->name }}!</h2>
            
            <p>Your subscription to <strong>{{ $plan->name }}</strong> has been confirmed.</p>
            
            <div class="plan-details">
                <h3 style="margin-top: 0; color: #d97706;">📦 Subscription Details:</h3>
                
                <div class="detail-row">
                    <span><strong>Plan:</strong></span>
                    <span>{{ $plan->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Price:</strong></span>
                    <span>${{ $plan->price }} / {{ $plan->interval }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Characters:</strong></span>
                    <span>{{ number_format($plan->character_limit) }} / month</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>API Calls:</strong></span>
                    <span>{{ number_format($plan->api_limit) }} / month</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Next Billing:</strong></span>
                    <span>{{ $nextBillingDate->format('M d, Y') }}</span>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/dashboard/subscription') }}" class="button">Manage Subscription</a>
            </div>
            
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <strong>📧 Receipt:</strong> A detailed receipt has been sent to your email.<br>
                <strong>💳 Payment Method:</strong> {{ $paymentMethod }}
            </p>
            
            <p><strong>Questions?</strong> Contact our billing team at <a href="mailto:billing@culturaltranslate.com">billing@culturaltranslate.com</a></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} CulturalTranslate. All rights reserved.</p>
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                <a href="{{ url('/unsubscribe') }}" style="color: #999;">Unsubscribe</a> | 
                <a href="{{ url('/dashboard/subscription') }}" style="color: #999;">Manage Subscription</a>
            </p>
        </div>
    </div>
</body>
</html>
