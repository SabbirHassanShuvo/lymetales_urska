@php
    $accentColor = \App\Models\Setting::getVal('admin_accent_color', '#4f46e5');
    $logoPath = \App\Models\Setting::getVal('admin_logo_path');
    $siteName = \App\Models\Setting::getVal('admin_site_name', config('app.name', 'Urška'));
    $tagline = \App\Models\Setting::getVal('admin_tagline', 'Personalised Books for Children');
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailData['subject'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f4f4f0; padding: 32px 16px; color: #1b1b18; }
        .wrapper { max-width: 620px; margin: 0 auto; }

        /* Header */
        .header { background: #1b1b18; border-radius: 20px 20px 0 0; padding: 40px 48px 32px; text-align: center; }
        .logo { font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 4px; }
        .logo-tagline { font-size: 12px; color: rgba(255,255,255,0.45); letter-spacing: 1.5px; text-transform: uppercase; }

        /* Body */
        .body { background: #ffffff; padding: 48px; border-radius: 0 0 20px 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .greeting { font-size: 22px; font-weight: 700; color: #1b1b18; margin-bottom: 12px; }
        .intro { font-size: 15px; color: #5a5a55; line-height: 1.75; margin-bottom: 32px; }

        /* Coupon Box */
        .coupon-container { background: #fafaf9; border: 2px dashed #d1d5db; border-radius: 16px; padding: 32px 24px; text-align: center; margin-bottom: 32px; }
        .coupon-label { font-size: 12px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #9ca3af; margin-bottom: 12px; }
        .coupon-code { font-size: 28px; font-weight: 900; color: {{ $accentColor }}; letter-spacing: 1px; font-family: monospace; background: #ffffff; display: inline-block; padding: 12px 28px; border: 1.5px solid #e5e7eb; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .coupon-expiry { font-size: 12px; color: #9ca3af; margin-top: 16px; }

        /* CTA Button */
        .cta-wrapper { text-align: center; margin-bottom: 24px; }
        .cta-btn { display: inline-block; background: {{ $accentColor }}; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 16px 36px; border-radius: 50px; letter-spacing: 0.5px; }

        /* Footer */
        .footer { margin-top: 40px; border-top: 1px solid #f0f0ee; padding-top: 28px; }
        .regards { font-size: 14px; color: #5a5a55; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Header --}}
        <div class="header">
            @if($logoPath)
                <div style="margin-bottom: 8px;">
                    <img src="{{ asset($logoPath) }}" alt="{{ $siteName }}" style="max-height: 60px; max-width: 220px; display: inline-block;">
                </div>
            @else
                <div class="logo">{{ $siteName }}</div>
            @endif
            @if($tagline)
                <div class="logo-tagline">{{ $tagline }}</div>
            @endif
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">{{ $emailData['greeting'] }}</p>
            <p class="intro">{{ $emailData['intro'] }}</p>

            {{-- Coupon Section --}}
            <div class="coupon-container">
                <div class="coupon-label">{{ $emailData['coupon_label'] }}</div>
                <div class="coupon-code">{{ $couponCode }}</div>
                <div class="coupon-expiry">{{ $emailData['expiry'] }}</div>
            </div>

            {{-- CTA Button --}}
            <div class="cta-wrapper">
                <a href="{{ config('app.url') }}" class="cta-btn">{{ $emailData['cta'] }}</a>
            </div>

            {{-- Regards --}}
            <div class="footer">
                <p class="regards">
                    {{ $emailData['footer'] }}
                </p>
            </div>
        </div>

    </div>
</body>
</html>
