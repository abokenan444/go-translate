<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expired</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 30px 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Your Subscription Has Expired</h1>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 30px 20px;">
                <p style="color: #333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">Hello <strong>{{ $user->name }}</strong>,</p>
                
                <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <p style="margin: 0; color: #721c24; font-size: 16px; line-height: 1.8;">
                        Your subscription has expired and your account has been downgraded to the <strong>Free Plan</strong>.
                    </p>
                </div>

                <p style="color: #333; font-size: 14px; line-height: 1.8; margin: 0 0 25px;">
                    Don't worry! Your data is safe and you can still access basic features. Renew your subscription anytime to restore full access.
                </p>

                <div style="background-color: #f8f9fa; border-left: 4px solid #6c757d; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <h3 style="color: #333; font-size: 16px; margin: 0 0 15px;">⚠️ Current Limitations:</h3>
                    <ul style="margin: 0; padding: 0 0 0 20px; color: #495057; font-size: 14px; line-height: 1.8;">
                        <li style="margin-bottom: 8px;">❌ Limited translation characters per month</li>
                        <li style="margin-bottom: 8px;">❌ No API access</li>
                        <li style="margin-bottom: 8px;">❌ No priority support</li>
                        <li style="margin-bottom: 8px;">❌ Team collaboration disabled</li>
                        <li>❌ Advanced features locked</li>
                    </ul>
                </div>

                <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <h3 style="color: #155724; font-size: 16px; margin: 0 0 15px;">✨ Renew to Get Back:</h3>
                    <ul style="margin: 0; padding: 0 0 0 20px; color: #155724; font-size: 14px; line-height: 1.8;">
                        <li style="margin-bottom: 8px;">✅ Unlimited translations</li>
                        <li style="margin-bottom: 8px;">✅ Full API access</li>
                        <li style="margin-bottom: 8px;">✅ Priority customer support</li>
                        <li style="margin-bottom: 8px;">✅ Team collaboration tools</li>
                        <li>✅ All premium features</li>
                    </ul>
                </div>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="https://culturaltranslate.com/pricing" 
                       style="display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 6px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(40, 167, 69, 0.3);">
                        Renew Subscription
                    </a>
                </div>

                <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #0c5460; font-size: 14px; line-height: 1.6;">
                        <strong>💰 Special Offer:</strong> Renew within 7 days and get 10% off your next subscription!
                    </p>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0 0 10px; color: #333; font-size: 14px; font-weight: 600;">Questions About Renewal?</p>
                <p style="margin: 0; color: #6c757d; font-size: 12px;">
                    Contact us: <a href="mailto:billing@culturaltranslate.com" style="color: #667eea; text-decoration: none;">billing@culturaltranslate.com</a>
                </p>
                <p style="margin: 15px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
