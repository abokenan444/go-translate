<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Message</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">New Contact Form Message</h1>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 30px 20px;">
                <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #6c757d; font-size: 14px;">Type: <strong style="color: #495057;">{{ ucfirst($contactMessage->type) }}</strong></p>
                    <p style="margin: 10px 0 0; color: #6c757d; font-size: 14px;">Priority: 
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;
                            @if($contactMessage->priority === 'urgent') background-color: #dc3545; color: #fff;
                            @elseif($contactMessage->priority === 'high') background-color: #fd7e14; color: #fff;
                            @elseif($contactMessage->priority === 'medium') background-color: #ffc107; color: #000;
                            @else background-color: #28a745; color: #fff;
                            @endif">
                            {{ strtoupper($contactMessage->priority) }}
                        </span>
                    </p>
                </div>

                <h2 style="color: #333; font-size: 18px; margin-bottom: 15px; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Sender Information</h2>
                
                <table width="100%" cellpadding="8" cellspacing="0" style="margin-bottom: 20px;">
                    <tr>
                        <td style="color: #6c757d; font-size: 14px; width: 100px;">Name:</td>
                        <td style="color: #333; font-size: 14px; font-weight: 600;">{{ $contactMessage->name }}</td>
                    </tr>
                    <tr>
                        <td style="color: #6c757d; font-size: 14px;">Email:</td>
                        <td style="color: #333; font-size: 14px; font-weight: 600;">
                            <a href="mailto:{{ $contactMessage->email }}" style="color: #667eea; text-decoration: none;">{{ $contactMessage->email }}</a>
                        </td>
                    </tr>
                    @if($contactMessage->phone)
                    <tr>
                        <td style="color: #6c757d; font-size: 14px;">Phone:</td>
                        <td style="color: #333; font-size: 14px; font-weight: 600;">{{ $contactMessage->phone }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: #6c757d; font-size: 14px;">Date:</td>
                        <td style="color: #333; font-size: 14px;">{{ $contactMessage->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </table>

                <h2 style="color: #333; font-size: 18px; margin-bottom: 15px; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Subject</h2>
                <p style="color: #333; font-size: 16px; font-weight: 600; margin: 0 0 20px;">{{ $contactMessage->subject }}</p>

                <h2 style="color: #333; font-size: 18px; margin-bottom: 15px; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Message</h2>
                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; color: #495057; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $contactMessage->message }}</div>

                @if($contactMessage->ip_address || $contactMessage->user_agent)
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                    <h3 style="color: #6c757d; font-size: 12px; margin-bottom: 10px; text-transform: uppercase;">Additional Information</h3>
                    @if($contactMessage->ip_address)
                    <p style="margin: 5px 0; color: #6c757d; font-size: 12px;">IP Address: {{ $contactMessage->ip_address }}</p>
                    @endif
                    @if($contactMessage->user_agent)
                    <p style="margin: 5px 0; color: #6c757d; font-size: 12px;">Browser: {{ $contactMessage->user_agent }}</p>
                    @endif
                </div>
                @endif

                <div style="text-align: center; margin-top: 30px;">
                    <a href="https://admin.culturaltranslate.com/admin/contact-messages/{{ $contactMessage->id }}" 
                       style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; font-size: 14px;">
                        View in Admin Panel
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0; color: #6c757d; font-size: 12px;">This is an automated message from Cultural Translate</p>
                <p style="margin: 5px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
