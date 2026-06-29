@php
    $accentColor = \App\Models\Setting::getVal('admin_accent_color', '#4f46e5');
    $logoPath = \App\Models\Setting::getVal('admin_logo_path');
    $logoExists = $logoPath && file_exists(public_path($logoPath));
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
        .header { background: #ffffff; border-radius: 20px 20px 0 0; padding: 32px 48px; text-align: center; border-bottom: 1px solid #f0f0ee; }
        .logo { font-size: 28px; font-weight: 900; color: {{ $accentColor }}; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 4px; }
        .logo-tagline { font-size: 12px; color: #9ca3af; letter-spacing: 1.5px; text-transform: uppercase; }
        .badge { display: inline-block; margin-top: 16px; background: {{ $accentColor }}; color: #ffffff; font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; padding: 6px 18px; border-radius: 50px; }

        /* Body */
        .body { background: #ffffff; padding: 48px; border-radius: 0 0 20px 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .greeting { font-size: 22px; font-weight: 700; color: #1b1b18; margin-bottom: 12px; }
        .intro { font-size: 15px; color: #5a5a55; line-height: 1.75; margin-bottom: 32px; }

        /* Order Info Box */
        .section-label { font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #9ca3af; margin-bottom: 14px; }
        .order-box { border: 1.5px solid #e5e7eb; border-radius: 16px; padding: 20px; margin-bottom: 28px; }
        .order-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 8px; }
        .order-row:last-child { margin-bottom: 0; }
        .order-label { color: #6b7280; }
        .order-value { font-weight: 700; color: #1b1b18; }

        /* Address Box */
        .address-box { background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 16px; padding: 20px; margin-bottom: 28px; }
        .address-text { font-size: 14px; color: #374151; line-height: 1.8; }

        /* CTA */
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
            @if($logoExists)
                <div style="margin-bottom: 8px;">
                    <img src="{{ asset($logoPath) }}" alt="{{ $siteName }}" style="max-height: 50px; max-width: 200px; display: inline-block;">
                </div>
            @else
                <div class="logo">{{ $siteName }}</div>
            @endif
            @if($tagline)
                <div class="logo-tagline">{{ $tagline }}</div>
            @endif
            <div class="badge">{{ $emailData['badge'] }}</div>
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">Hi {{ $order->full_name }},</p>
            <p class="intro">{{ $emailData['message'] }}</p>

            {{-- Order details --}}
            <p class="section-label">Order Details</p>
            <div class="order-box">
                <div class="order-row">
                    <span class="order-label">Order Number</span>
                    <span class="order-value">#{{ $order->order_number }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Date</span>
                    <span class="order-value">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="order-row">
                    <span class="order-label">Status</span>
                    <span class="order-value" style="text-transform: capitalize;">{{ $order->order_status }}</span>
                </div>
            </div>

            {{-- Shipping Address --}}
            <p class="section-label">Shipping Address</p>
            <div class="address-box">
                <div class="address-text">
                    <strong>{{ $order->full_name }}</strong><br>
                    {{ $order->address }}<br>
                    {{ $order->postal_code }} {{ $order->city }}<br>
                    {{ $order->country ?? 'Slovenia' }}
                </div>
            </div>

            {{-- CTA Button --}}
            <div class="cta-wrapper">
                <a href="{{ config('app.url') }}" class="cta-btn">Visit Our Website</a>
            </div>

            {{-- Regards --}}
            <div class="footer">
                <p class="regards">
                    Best regards,<br>
                    <strong>The {{ $siteName }} Team</strong>
                </p>
            </div>
        </div>

    </div>
</body>
</html>
