<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; padding: 40px 20px; }
        .wrapper { max-width: 520px; margin: 0 auto; }
        .card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
        .header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 40px 40px 32px; text-align: center; }
        .header-logo { font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; margin-bottom: 8px; }
        .header-sub { font-size: 13px; color: rgba(255,255,255,0.75); font-weight: 500; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 12px; }
        .text { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 28px; }
        .otp-label { font-size: 11px; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 12px; }
        .otp-box { background: #f1f5f9; border: 2px dashed #c7d2fe; border-radius: 14px; padding: 24px; text-align: center; margin-bottom: 28px; }
        .otp-code { font-size: 42px; font-weight: 800; color: #4f46e5; letter-spacing: 10px; font-family: 'Courier New', monospace; }
        .expiry-badge { display: inline-block; background: #fef3c7; color: #92400e; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 99px; margin-bottom: 28px; }
        .warning { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 14px 16px; font-size: 13px; color: #dc2626; margin-bottom: 24px; }
        .footer { padding: 24px 40px; background: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center; }
        .footer-text { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .divider { height: 1px; background: #f1f5f9; margin: 0 40px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div class="header-logo">LYMETALES</div>
                <div class="header-sub">Admin Portal · Password Reset</div>
            </div>

            <div class="body">
                <p class="greeting">Hello, {{ $adminName }} 👋</p>
                <p class="text">
                    We received a request to reset the password for your admin account. 
                    Use the one-time code below to verify your identity and set a new password.
                </p>

                <div class="otp-label">Your One-Time Password (OTP)</div>
                <div class="otp-box">
                    <div class="otp-code">{{ $otp }}</div>
                </div>

                <div style="text-align:center; margin-bottom: 28px;">
                    <span class="expiry-badge">⏱ This code expires in 10 minutes</span>
                </div>

                <div class="warning">
                    <strong>Security Notice:</strong> If you did not request this password reset, please ignore this email. Your password will remain unchanged.
                </div>

                <p style="font-size:13px; color:#94a3b8; line-height:1.6">
                    For security reasons, never share this code with anyone. Lymetales staff will never ask for your OTP.
                </p>
            </div>

            <div class="footer">
                <p class="footer-text">
                    This is an automated email from <strong>Lymetales Admin Portal</strong>.<br>
                    Please do not reply to this email.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
