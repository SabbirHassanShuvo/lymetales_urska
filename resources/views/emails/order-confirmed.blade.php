@php
    $accentColor = \App\Models\Setting::getVal('admin_accent_color', '#4f46e5');
    $logoPath = \App\Models\Setting::getVal('admin_logo_path');
    $logoExists = $logoPath && file_exists(public_path($logoPath));
    $siteName = \App\Models\Setting::getVal('admin_site_name', config('app.name', 'Urška'));
    $tagline = \App\Models\Setting::getVal('admin_tagline', 'Personalised Books for Children');
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed #{{ $order->order_number }}</title>
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

        /* Order Summary Box */
        .section-label { font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #9ca3af; margin-bottom: 14px; }
        .order-box { border: 1.5px solid #e5e7eb; border-radius: 16px; overflow: hidden; margin-bottom: 28px; }
        .order-box-header { background: #f9fafb; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; }
        .order-number { font-size: 13px; font-weight: 700; color: #1b1b18; }
        .order-date { font-size: 12px; color: #9ca3af; }

        /* Items */
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table td { padding: 14px 20px; font-size: 14px; vertical-align: top; border-bottom: 1px solid #f3f4f6; }
        .items-table tr:last-child td { border-bottom: none; }
        .item-title { font-weight: 600; color: #1b1b18; margin-bottom: 4px; }
        .item-meta { font-size: 12px; color: #9ca3af; line-height: 1.5; }
        .item-price { text-align: right; font-weight: 700; color: #1b1b18; white-space: nowrap; }

        /* Totals */
        .totals { border-top: 1.5px solid #e5e7eb; }
        .total-row { display: flex; justify-content: space-between; padding: 10px 20px; font-size: 14px; }
        .total-row.grand { background: #f9fafb; font-weight: 800; font-size: 16px; }
        .total-label { color: #6b7280; }
        .total-label.grand { color: #1b1b18; }
        .total-value { color: #1b1b18; }
        .total-value.discount { color: #22c55e; }

        /* Address Box */
        .address-box { background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 16px; padding: 20px; margin-bottom: 28px; }
        .address-text { font-size: 14px; color: #374151; line-height: 1.8; }

        /* Invoice Button */
        .cta-wrapper { text-align: center; margin-bottom: 36px; }
        .cta-btn { display: inline-block; background: {{ $accentColor }}; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 16px 36px; border-radius: 50px; letter-spacing: 0.5px; }
        .cta-note { font-size: 11px; color: #9ca3af; margin-top: 10px; }

        /* Divider */
        .divider { border: none; border-top: 1px solid #f0f0ee; margin: 32px 0; }

        /* Footer */
        .footer { margin-top: 40px; border-top: 1px solid #f0f0ee; padding-top: 28px; }
        .footer-text { font-size: 12px; color: #9ca3af; line-height: 1.8; }
        .footer-text a { color: #6366f1; text-decoration: none; }

        @media (max-width: 480px) {
            .body { padding: 28px 24px; }
            .header { padding: 28px 24px; }
            .footer { padding: 20px 24px; }
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
            <div class="badge">✅ Order Confirmed</div>
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">Hi {{ $order->full_name }},</p>
            <p class="intro">
                Thank you for your order! We've received it and our team is already preparing your personalised book with love and care.
                You'll receive another email as soon as your order ships.
            </p>

            {{-- Order Details --}}
            <p class="section-label">Order Summary</p>
            <div class="order-box">
                <div class="order-box-header">
                    <span class="order-number">#{{ $order->order_number }}</span>
                    <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                </div>

                @php
                    $items = is_array($order->items) ? $order->items : json_decode($order->items, true);
                @endphp

                <table class="items-table">
                    @forelse($items ?? [] as $item)
                    <tr>
                        <td>
                            <div class="item-title">{{ $item['title'] ?? 'Personalised Book' }}</div>
                            @php
                                $pers = $item['personalisation'] ?? null;
                            @endphp
                            @if($pers)
                                <div class="item-meta">
                                    @if(!empty($pers['child_name'])) Child Name: {{ $pers['child_name'] }}<br>@endif
                                    @if(!empty($pers['dedication'])) Dedication: {{ $pers['dedication'] }}<br>@endif
                                    @if(!empty($pers['fields']) && is_array($pers['fields']))
                                        @foreach($pers['fields'] as $fKey => $fVal)
                                            {{ ucwords(str_replace('_', ' ', $fKey)) }}: {{ $fVal }}<br>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                            <div class="item-meta">Qty: {{ $item['quantity'] ?? 1 }}</div>
                        </td>
                        <td class="item-price">{{ config('shop.currency_symbol', '€') }}{{ number_format($item['line_total'] ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="padding:16px 20px; color:#9ca3af;">—</td></tr>
                    @endforelse
                </table>

                <div class="totals">
                    <div class="total-row">
                        <span class="total-label">Subtotal</span>
                        <span class="total-value">{{ config('shop.currency_symbol', '€') }}{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->shipping_fee > 0)
                    <div class="total-row">
                        <span class="total-label">Shipping</span>
                        <span class="total-value">{{ config('shop.currency_symbol', '€') }}{{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    @endif
                    @if($order->fast_production_fee > 0)
                    <div class="total-row">
                        <span class="total-label">Fast Production</span>
                        <span class="total-value">{{ config('shop.currency_symbol', '€') }}{{ number_format($order->fast_production_fee, 2) }}</span>
                    </div>
                    @endif
                    @if($order->discount > 0)
                    <div class="total-row">
                        <span class="total-label">Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                        <span class="total-value discount">−{{ config('shop.currency_symbol', '€') }}{{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="total-row grand">
                        <span class="total-label grand">Total Paid</span>
                        <span class="total-value">{{ config('shop.currency_symbol', '€') }}{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <p class="section-label">Shipping To</p>
            <div class="address-box">
                <div class="address-text">
                    <strong>{{ $order->full_name }}</strong><br>
                    {{ $order->address }}<br>
                    {{ $order->postal_code }} {{ $order->city }}<br>
                    {{ $order->country ?? 'Slovenia' }}
                </div>
            </div>

            {{-- Invoice Download CTA --}}
            <div class="cta-wrapper">
                <a href="{{ $invoiceUrl }}" class="cta-btn">⬇ Download Invoice (PDF)</a>
                <p class="cta-note">Link valid for 7 days — no account required.</p>
            </div>

            <hr class="divider">
            <p style="font-size:14px;color:#6b7280;line-height:1.75;">
                Have a question about your order? Simply reply to this email or contact us at
                <a href="mailto:{{ config('mail.from.address') }}" style="color:{{ $accentColor }};">{{ config('mail.from.address') }}</a>.
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
