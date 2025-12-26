<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expiring Soon</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); padding: 30px 20px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 10px;">⚠️</div>
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Your Subscription is Expiring Soon</h1>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 30px 20px;">
                <p style="color: #333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">Hello <strong>{{ $user->name }}</strong>,</p>
                
                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <p style="margin: 0; color: #856404; font-size: 16px; font-weight: 600; line-height: 1.8;">
                        Your subscription will expire in <strong>{{ $daysRemaining }} day{{ $daysRemaining > 1 ? 's' : '' }}</strong> on <strong>{{ $expiryDate->format('F d, Y') }}</strong>.
                    </p>
                </div>

                <p style="color: #333; font-size: 14px; line-height: 1.8; margin: 0 0 25px;">
                    To continue enjoying uninterrupted access to all premium features, please renew your subscription before it expires.
                </p>

                <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <h3 style="color: #333; font-size: 16px; margin: 0 0 15px;">⏰ What happens after expiration?</h3>
                    <ul style="margin: 0; padding: 0 0 0 20px; color: #495057; font-size: 14px; line-height: 1.8;">
                        <li style="margin-bottom: 8px;">Limited access to translation features</li>
                        <li style="margin-bottom: 8px;">Reduced character limits</li>
                        <li style="margin-bottom: 8px;">No API access</li>
                        <li style="margin-bottom: 8px;">Loss of priority support</li>
                        <li>Team collaboration features disabled</li>
                    </ul>
                </div>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="https://culturaltranslate.com/pricing" 
                       style="display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 6px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.3);">
                        Renew Now
                    </a>
                </div>

                <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #0c5460; font-size: 14px; line-height: 1.6;">
                        <strong>💡 Tip:</strong> Enable auto-renewal to never miss out on your subscription benefits!
                    </p>
                </div>

                <p style="color: #6c757d; font-size: 13px; line-height: 1.6; margin: 0;">
                    If you have any questions or need assistance with renewal, our support team is ready to help.
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0 0 10px; color: #333; font-size: 14px; font-weight: 600;">Need Help?</p>
                <p style="margin: 0; color: #6c757d; font-size: 12px;">
                    Contact us: <a href="mailto:billing@culturaltranslate.com" style="color: #667eea; text-decoration: none;">billing@culturaltranslate.com</a>
                </p>
                <p style="margin: 15px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
