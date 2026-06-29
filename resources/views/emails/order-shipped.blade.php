@php
    $accentColor = \App\Models\Setting::getVal('admin_accent_color', '#3b82f6');
    $logoPath = \App\Models\Setting::getVal('admin_logo_path');
    $logoExists = $logoPath && file_exists(public_path(ltrim($logoPath, '/\\')));
    $siteName = \App\Models\Setting::getVal('admin_site_name', config('app.name', 'Urška'));
    $tagline = \App\Models\Setting::getVal('admin_tagline', 'Personalised Books for Children');
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Has Shipped! #{{ $order->order_number }}</title>
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
        .intro { font-size: 15px; color: #5a5a55; line-height: 1.75; margin-bottom: 36px; }

        /* Tracking Box */
        .section-label { font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #9ca3af; margin-bottom: 14px; }
        .tracking-box { border: 1.5px solid #e5e7eb; border-radius: 16px; overflow: hidden; margin-bottom: 28px; }
        .tracking-box-inner { padding: 24px; display: flex; align-items: flex-start; gap: 20px; }
        .track-icon { width: 48px; height: 48px; background: #f3f4f6; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .track-info { flex: 1; }
        .track-label { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .track-value { font-size: 16px; font-weight: 700; color: #1b1b18; font-family: monospace; }

        /* Delivery Steps */
        .steps { display: flex; gap: 0; margin-bottom: 32px; }
        .step { flex: 1; text-align: center; padding: 16px 8px; background: #f9fafb; border: 1px solid #e5e7eb; position: relative; }
        .step:first-child { border-radius: 12px 0 0 12px; }
        .step:last-child { border-radius: 0 12px 12px 0; }
        .step.active { background: #f3f4f6; border-color: {{ $accentColor }}; }
        .step-icon { font-size: 20px; margin-bottom: 6px; }
        .step-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #6b7280; }
        .step.active .step-label { color: {{ $accentColor }}; }

        /* Address */
        .address-box { background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 16px; padding: 20px; margin-bottom: 28px; }
        .address-text { font-size: 14px; color: #374151; line-height: 1.8; }

        /* CTA */
        .cta-wrapper { text-align: center; margin-bottom: 36px; }
        .cta-btn { display: inline-block; background: {{ $accentColor }}; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 16px 36px; border-radius: 50px; letter-spacing: 0.5px; }
        .cta-note { font-size: 11px; color: #9ca3af; margin-top: 10px; }

        /* Divider */
        .divider { border: none; border-top: 1px solid #f0f0ee; margin: 32px 0; }

        /* Footer */
        .footer { margin-top: 40px; border-top: 1px solid #f0f0ee; padding-top: 28px; }
        .footer-text { font-size: 12px; color: #9ca3af; line-height: 1.8; }

        @media (max-width: 480px) {
            .body { padding: 28px 24px; }
            .header { padding: 28px 24px; }
            .steps { flex-direction: column; }
            .step:first-child { border-radius: 12px 12px 0 0; }
            .step:last-child { border-radius: 0 0 12px 12px; }
        }
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
            <div class="badge">🚚 Order Shipped</div>
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">Great news, {{ $order->full_name }}!</p>
            <p class="intro">
                Your personalised book is on its way! Your order <strong>#{{ $order->order_number }}</strong>
                has been handed to our shipping partner and is heading to you now.
            </p>

            {{-- Delivery Status Steps --}}
            <div class="steps">
                <div class="step">
                    <div class="step-icon">📋</div>
                    <div class="step-label">Ordered</div>
                </div>
                <div class="step">
                    <div class="step-icon">🎨</div>
                    <div class="step-label">Printed</div>
                </div>
                <div class="step active">
                    <div class="step-icon">📦</div>
                    <div class="step-label">Shipped</div>
                </div>
                <div class="step">
                    <div class="step-icon">🏠</div>
                    <div class="step-label">Delivered</div>
                </div>
            </div>

            {{-- Tracking Info --}}
            @if($order->tracking_number || $order->tracking_link)
            <p class="section-label">Tracking Information</p>
            <div class="tracking-box">
                <div class="tracking-box-inner">
                    <div class="track-icon" style="background: {{ $accentColor }}20;">📦</div>
                    <div class="track-info">
                        @if($order->tracking_number)
                        <div style="margin-bottom: 12px;">
                            <div class="track-label">Tracking Number</div>
                            <div class="track-value">{{ $order->tracking_number }}</div>
                        </div>
                        @endif
                        @if($order->tracking_link)
                        <div>
                            <div class="track-label">Track Your Package</div>
                            <a href="{{ $order->tracking_link }}" style="color:{{ $accentColor }};font-weight:600;font-size:14px;word-break:break-all;">
                                {{ $order->tracking_link }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Delivery Address --}}
            <p class="section-label">Delivering To</p>
            <div class="address-box">
                <div class="address-text">
                    <strong>{{ $order->full_name }}</strong><br>
                    {{ $order->address }}<br>
                    {{ $order->postal_code }} {{ $order->city }}<br>
                    {{ $order->country ?? 'Slovenia' }}
                </div>
            </div>

            {{-- Track CTA --}}
            @if($order->tracking_link)
            <div class="cta-wrapper">
                <a href="{{ $order->tracking_link }}" class="cta-btn">📍 Track My Package</a>
                <p class="cta-note">Click to get real-time tracking updates.</p>
            </div>
            @endif

            <hr class="divider">
            <p style="font-size:14px;color:#6b7280;line-height:1.75;">
                If you have any questions, simply reply to this email — we're always happy to help!<br>
                Thank you for choosing {{ $siteName }}. We hope your little one loves it! 🌟
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p class="footer-text">
                © {{ date('Y') }} {{ $siteName }} · All rights reserved.<br>
                You're receiving this email because you placed an order on our website.
            </p>
        </div>

    </div>
</body>
</html>
