<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response to Your Message</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Response to Your Message</h1>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 30px 20px;">
                <p style="color: #333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">Dear <strong>{{ $contactMessage->name }}</strong>,</p>
                
                <p style="color: #333; font-size: 14px; line-height: 1.6; margin: 0 0 20px;">
                    We would like to inform you that we have reviewed your message and here is our response:
                </p>

                <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px;">
                    <h3 style="color: #333; font-size: 16px; margin: 0 0 10px;">Your Original Message:</h3>
                    <p style="color: #6c757d; font-size: 14px; margin: 5px 0;"><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
                    <p style="color: #6c757d; font-size: 14px; margin: 5px 0;"><strong>Date:</strong> {{ $contactMessage->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div style="background-color: #e8f5e9; border-left: 4px solid #4caf50; padding: 20px; margin-bottom: 20px; border-radius: 4px;">
                    <h3 style="color: #2e7d32; font-size: 16px; margin: 0 0 15px;">✉️ Response from Our Team:</h3>
                    <div style="color: #333; font-size: 14px; line-height: 1.8; white-space: pre-wrap;">{{ $responseMessage }}</div>
                </div>

                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                        <strong>💡 Have Additional Questions?</strong><br>
                        You can reply directly to this email and we'll be happy to help you.
                    </p>
                </div>

                <p style="color: #333; font-size: 14px; line-height: 1.6; margin: 0 0 20px;">
                    Thank you for contacting us and we hope we have provided you with the help you needed.
                </p>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="https://culturaltranslate.com/contact" 
                       style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; font-size: 14px; margin-right: 10px;">
                        Send New Message
                    </a>
                    <a href="https://culturaltranslate.com/dashboard" 
                       style="display: inline-block; background-color: #28a745; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; font-size: 14px;">
                        Dashboard
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0 0 10px; color: #333; font-size: 14px; font-weight: 600;">We're Here to Serve You</p>
                <p style="margin: 0; color: #6c757d; font-size: 12px;">
                    Support Team: <a href="mailto:support@culturaltranslate.com" style="color: #667eea; text-decoration: none;">support@culturaltranslate.com</a>
                </p>
                <p style="margin: 15px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
