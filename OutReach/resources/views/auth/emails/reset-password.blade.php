<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - OutReach</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b;-webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); overflow: hidden; border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%); padding: 40px 20px; text-align: center;">
                            <div style="width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.15); border-radius: 14px; display: inline-block; margin-bottom: 16px; text-align: center; vertical-align: middle;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-top: 13px;"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                            </div>
                            <h1 style="color: #ffffff; font-size: 24px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">OutReach Password Reset</h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">
                            <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin-top: 0; margin-bottom: 12px;">Hello {{ $user->name }},</p>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin-top: 0; margin-bottom: 24px;">
                                We received a request to reset the password for your OutReach account. No problem, we've got you covered! Click the button below to choose a new password.
                            </p>
                            
                            <!-- Call to Action Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetLink }}" target="_blank" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%); color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 14px 32px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; line-height: 1.5; color: #64748b; margin-bottom: 20px;">
                                <strong style="color: #475569;">Note:</strong> This link is only valid for <span style="font-weight: 600;">1 hour</span>. If you did not make this request, you can safely ignore this email; your password will remain secure and unchanged.
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid #f1f5f9;"></div>
                        </td>
                    </tr>

                    <!-- Secondary link explanation -->
                    <tr>
                        <td style="padding: 20px 40px 40px 40px;">
                            <p style="font-size: 12px; line-height: 1.5; color: #94a3b8; margin: 0;">
                                If you are having trouble clicking the button, copy and paste this URL into your web browser:
                            </p>
                            <p style="font-size: 12px; font-family: monospace; word-break: break-all; color: #6366f1; margin-top: 8px; margin-bottom: 0;">
                                {{ $resetLink }}
                            </p>
                        </td>
                    </tr>

                </table>

                <!-- Footer Info -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="margin-top: 20px; text-align: center;">
                    <tr>
                        <td style="padding: 0 20px;">
                            <p style="font-size: 12px; color: #94a3b8; margin: 0 0 8px 0;">&copy; {{ date('Y') }} OutReach. All rights reserved.</p>
                            <p style="font-size: 11px; color: #cbd5e1; margin: 0;">Social Media Based P2P Customer Outreach Platform</p>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
</body>
</html>
