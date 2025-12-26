<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Received Confirmation</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Thank You for Contacting Us</h1>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 30px 20px;">
                <p style="color: #333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">Dear <strong>{{ $contactMessage->name }}</strong>,</p>
                
                <p style="color: #333; font-size: 14px; line-height: 1.6; margin: 0 0 20px;">
                    We have successfully received your message and our team will review it and respond to you as soon as possible.
                </p>

                <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px;">
                    <h3 style="color: #333; font-size: 16px; margin: 0 0 10px;">Your Message Summary:</h3>
                    <table width="100%" cellpadding="5" cellspacing="0">
                        <tr>
                            <td style="color: #6c757d; font-size: 14px; width: 80px;">Type:</td>
                            <td style="color: #495057; font-size: 14px; font-weight: 600;">{{ ucfirst($contactMessage->type) }}</td>
                        </tr>
                        <tr>
                            <td style="color: #6c757d; font-size: 14px;">Subject:</td>
                            <td style="color: #495057; font-size: 14px; font-weight: 600;">{{ $contactMessage->subject }}</td>
                        </tr>
                        <tr>
                            <td style="color: #6c757d; font-size: 14px;">Date:</td>
                            <td style="color: #495057; font-size: 14px;">{{ $contactMessage->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div style="background-color: #e7f3ff; border-left: 4px solid #2196f3; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #0d47a1; font-size: 14px; line-height: 1.6;">
                        <strong>📧 Note:</strong> We will respond to your email at: <strong>{{ $contactMessage->email }}</strong>
                    </p>
                </div>

                <p style="color: #333; font-size: 14px; line-height: 1.6; margin: 0 0 20px;">
                    We appreciate your contact and are committed to providing you with the best service.
                </p>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="https://culturaltranslate.com" 
                       style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; font-size: 14px;">
                        Visit Website
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0 0 10px; color: #333; font-size: 14px; font-weight: 600;">Need Immediate Help?</p>
                <p style="margin: 0; color: #6c757d; font-size: 12px;">
                    Contact us: <a href="mailto:support@culturaltranslate.com" style="color: #667eea; text-decoration: none;">support@culturaltranslate.com</a>
                </p>
                <p style="margin: 15px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
