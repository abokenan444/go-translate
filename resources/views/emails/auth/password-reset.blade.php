<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Password Reset Request</h1>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 30px 20px;">
                <p style="color: #333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">Hello <strong>{{ $userName }}</strong>,</p>
                
                <p style="color: #333; font-size: 14px; line-height: 1.6; margin: 0 0 20px;">
                    We received a request to reset your password for your Cultural Translate account. Click the button below to create a new password:
                </p>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ $resetUrl }}" 
                       style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 6px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);">
                        Reset Password
                    </a>
                </div>

                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                        <strong>⚠️ Security Note:</strong> This link will expire in 60 minutes for your security.
                    </p>
                </div>

                <p style="color: #333; font-size: 14px; line-height: 1.6; margin: 0 0 15px;">
                    If the button doesn't work, copy and paste this URL into your browser:
                </p>

                <div style="background-color: #f8f9fa; padding: 12px; border-radius: 4px; word-break: break-all; margin-bottom: 20px;">
                    <p style="margin: 0; color: #667eea; font-size: 13px; font-family: monospace;">{{ $resetUrl }}</p>
                </div>

                <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #721c24; font-size: 14px; line-height: 1.6;">
                        <strong>🛡️ Didn't request this?</strong><br>
                        If you didn't request a password reset, please ignore this email and your password will remain unchanged. You may also want to secure your account by changing your password.
                    </p>
                </div>

                <p style="color: #6c757d; font-size: 13px; line-height: 1.6; margin: 0;">
                    For security reasons, never share this email or link with anyone.
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0 0 10px; color: #333; font-size: 14px; font-weight: 600;">Need Help?</p>
                <p style="margin: 0; color: #6c757d; font-size: 12px;">
                    Contact us: <a href="mailto:support@culturaltranslate.com" style="color: #667eea; text-decoration: none;">support@culturaltranslate.com</a>
                </p>
                <p style="margin: 15px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
