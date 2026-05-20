<!DOCTYPE html>
<html>
<head>
    <title>Password Reset OTP</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .otp { font-size: 24px; font-weight: bold; color: #4F46E5; letter-spacing: 5px; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello!</h2>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <p>Your 4-digit Verification Code (OTP) is:</p>
        <div class="otp">{{ $otp }}</div>
        <p>This code will expire in 60 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>
        <div class="footer">
            Regards,<br>
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
