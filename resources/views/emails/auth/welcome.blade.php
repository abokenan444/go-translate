<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Cultural Translate</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0 0 10px; font-size: 28px;">Welcome to Cultural Translate! 🎉</h1>
                <p style="color: #e0e7ff; margin: 0; font-size: 16px;">Your account has been successfully created</p>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 40px 20px;">
                <p style="color: #333; font-size: 18px; font-weight: 600; margin: 0 0 20px;">Hello {{ $user->name }}! 👋</p>
                
                <p style="color: #333; font-size: 14px; line-height: 1.8; margin: 0 0 25px;">
                    Thank you for joining <strong>Cultural Translate</strong> – your intelligent platform for professional translation services with cultural sensitivity.
                </p>

                <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <h3 style="color: #333; font-size: 16px; margin: 0 0 15px;">🚀 Get Started:</h3>
                    <ul style="margin: 0; padding: 0 0 0 20px; color: #495057; font-size: 14px; line-height: 1.8;">
                        <li style="margin-bottom: 8px;">Access your dashboard to manage translations</li>
                        <li style="margin-bottom: 8px;">Upload documents for instant translation</li>
                        <li style="margin-bottom: 8px;">Choose from multiple subscription plans</li>
                        <li style="margin-bottom: 8px;">Track your translation history and usage</li>
                        <li>Integrate with your favorite tools via API</li>
                    </ul>
                </div>

                <div style="background-color: #e8f5e9; border-left: 4px solid #4caf50; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <h3 style="color: #2e7d32; font-size: 16px; margin: 0 0 10px;">✨ Your Account Details:</h3>
                    <p style="margin: 5px 0; color: #1b5e20; font-size: 14px;"><strong>Email:</strong> {{ $user->email }}</p>
                    <p style="margin: 5px 0; color: #1b5e20; font-size: 14px;"><strong>Account Created:</strong> {{ $user->created_at->format('F d, Y') }}</p>
                </div>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="https://culturaltranslate.com/dashboard" 
                       style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 6px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);">
                        Go to Dashboard
                    </a>
                </div>

                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 25px;">
                    <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                        <strong>💡 Pro Tip:</strong> Upgrade to a premium plan to unlock unlimited translations, priority support, and advanced features!
                    </p>
                </div>

                <p style="color: #6c757d; font-size: 13px; line-height: 1.6; margin: 0;">
                    If you have any questions or need assistance, our support team is here to help. Don't hesitate to reach out!
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 25px 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0 0 15px; color: #333; font-size: 14px; font-weight: 600;">Need Help?</p>
                <p style="margin: 0 0 5px; color: #6c757d; font-size: 13px;">
                    📧 <a href="mailto:support@culturaltranslate.com" style="color: #667eea; text-decoration: none;">support@culturaltranslate.com</a>
                </p>
                <p style="margin: 0 0 5px; color: #6c757d; font-size: 13px;">
                    📖 <a href="https://culturaltranslate.com/docs" style="color: #667eea; text-decoration: none;">Documentation</a>
                </p>
                <p style="margin: 20px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
