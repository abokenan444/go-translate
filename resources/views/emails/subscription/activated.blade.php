<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Activated</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 20px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 10px;">🎊</div>
                <h1 style="color: #ffffff; margin: 0 0 10px; font-size: 28px;">Subscription Activated!</h1>
                <p style="color: #d4edda; margin: 0; font-size: 16px;">Welcome to {{ $plan->name }} Plan</p>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 40px 20px;">
                <p style="color: #333; font-size: 18px; font-weight: 600; margin: 0 0 20px;">Congratulations {{ $user->name }}! 🎉</p>
                
                <p style="color: #333; font-size: 14px; line-height: 1.8; margin: 0 0 25px;">
                    Your <strong>{{ $plan->name }}</strong> subscription has been successfully activated. You now have access to all premium features!
                </p>

                <div style="background-color: #e8f5e9; border-left: 4px solid #28a745; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <h3 style="color: #2e7d32; font-size: 18px; margin: 0 0 15px;">📋 Your Plan Details:</h3>
                    <table width="100%" cellpadding="8" cellspacing="0">
                        <tr>
                            <td style="color: #1b5e20; font-size: 14px; width: 150px;">Plan:</td>
                            <td style="color: #1b5e20; font-size: 14px; font-weight: 700;">{{ $plan->name }}</td>
                        </tr>
                        <tr>
                            <td style="color: #1b5e20; font-size: 14px;">Price:</td>
                            <td style="color: #1b5e20; font-size: 14px; font-weight: 700;">${{ number_format($plan->price, 2) }}/{{ $plan->billing_period }}</td>
                        </tr>
                        <tr>
                            <td style="color: #1b5e20; font-size: 14px;">Characters Limit:</td>
                            <td style="color: #1b5e20; font-size: 14px; font-weight: 700;">{{ number_format($plan->tokens_limit) }} characters</td>
                        </tr>
                        <tr>
                            <td style="color: #1b5e20; font-size: 14px;">Team Members:</td>
                            <td style="color: #1b5e20; font-size: 14px; font-weight: 700;">{{ $plan->max_team_members }}</td>
                        </tr>
                        <tr>
                            <td style="color: #1b5e20; font-size: 14px;">API Access:</td>
                            <td style="color: #1b5e20; font-size: 14px; font-weight: 700;">{{ $plan->api_access ? '✅ Yes' : '❌ No' }}</td>
                        </tr>
                        <tr>
                            <td style="color: #1b5e20; font-size: 14px;">Priority Support:</td>
                            <td style="color: #1b5e20; font-size: 14px; font-weight: 700;">{{ $plan->priority_support ? '✅ Yes' : '❌ No' }}</td>
                        </tr>
                    </table>
                </div>

                <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin-bottom: 25px; border-radius: 4px;">
                    <h3 style="color: #333; font-size: 16px; margin: 0 0 15px;">🚀 What's Next?</h3>
                    <ul style="margin: 0; padding: 0 0 0 20px; color: #495057; font-size: 14px; line-height: 1.8;">
                        <li style="margin-bottom: 8px;">Start translating documents with enhanced limits</li>
                        <li style="margin-bottom: 8px;">Explore API integration for your applications</li>
                        <li style="margin-bottom: 8px;">Invite team members to collaborate</li>
                        <li style="margin-bottom: 8px;">Access priority customer support</li>
                        <li>Download your invoices from the dashboard</li>
                    </ul>
                </div>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="https://culturaltranslate.com/dashboard" 
                       style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 6px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3); margin-right: 10px;">
                        Go to Dashboard
                    </a>
                    <a href="https://culturaltranslate.com/pricing" 
                       style="display: inline-block; background-color: #6c757d; color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 6px; font-weight: 600; font-size: 16px;">
                        View All Plans
                    </a>
                </div>

                <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #0c5460; font-size: 14px; line-height: 1.6;">
                        <strong>💳 Billing Info:</strong> Your subscription will automatically renew unless cancelled. Manage your subscription anytime from your dashboard.
                    </p>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f9fa; padding: 25px 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 0 0 10px; color: #333; font-size: 14px; font-weight: 600;">Questions About Your Subscription?</p>
                <p style="margin: 0; color: #6c757d; font-size: 12px;">
                    Contact us: <a href="mailto:billing@culturaltranslate.com" style="color: #667eea; text-decoration: none;">billing@culturaltranslate.com</a>
                </p>
                <p style="margin: 15px 0 0; color: #6c757d; font-size: 12px;">© {{ date('Y') }} Cultural Translate. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
