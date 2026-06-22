<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #697843; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { vertical-align: top; line-height: 1.6; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { border-bottom: 1px solid #eee; padding: 9px 6px; text-align: left; }
        .items-table th { background-color: #f9fafb; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-table { width: 50%; float: right; border-collapse: collapse; }
        .totals-table td { padding: 8px 6px; }
        .totals-table .total-row td { font-size: 16px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 50px; clear: both; border-top: 1px solid #eee; padding-top: 15px; }
        .personalisation-block { font-size: 11px; color: #555; margin-top: 4px; padding: 4px 6px; background: #f5f7f0; border-left: 2px solid #697843; border-radius: 2px; }
        .personalisation-block span { font-weight: bold; color: #444; }
        .discount-badge { display: inline-block; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 3px; padding: 1px 6px; font-size: 11px; font-weight: bold; }
        .section-label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.04em; }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logoPath = \App\Models\Setting::getVal('footer_logo_path', '');
            $fullPath = $logoPath && file_exists(public_path($logoPath)) ? public_path($logoPath) : null;
            $base64 = null;
            if ($fullPath) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp
        @if($base64)
            <img src="{{ $base64 }}" alt="Lymetales Logo" style="max-height: 60px;">
        @else
            <div class="logo">Lymetales</div>
        @endif
        <div style="color: #666; margin-top: 5px; font-size: 15px; font-weight: 600;">Order Receipt</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Order Details:</strong><br>
                Order #: {{ $order->order_number }}<br>
                Date: {{ $order->created_at->format('M d, Y H:i') }}<br>
                Payment Method: {{ strtoupper($order->payment_method) }}<br>
                Payment Status: {{ ucfirst($order->payment_status) }}<br>
                Order Status: {{ ucfirst($order->order_status) }}
            </td>
            <td width="50%">
                <strong>Customer Info:</strong><br>
                {{ $order->full_name }}<br>
                {{ $order->email }}<br>
                {{ $order->address }}<br>
                {{ $order->city }}, {{ $order->postal_code }}<br>
                {{ $order->country }}<br>
                Phone: {{ $order->phone }}
            </td>
        </tr>
    </table>

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                @php
                    $itemName       = $item['title'] ?? ($item['name'] ?? 'Product');
                    $itemQty        = $item['quantity'] ?? 1;
                    $unitPrice      = $item['unit_price'] ?? ($item['price'] ?? 0);
                    $lineTotal      = $item['line_total'] ?? ($unitPrice * $itemQty);
                    $personalisation = $item['personalisation'] ?? null;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $itemName }}</strong>
                        @if($item['type'] ?? 'product' === 'gift')
                            <span style="font-size: 11px; color: #697843; margin-left: 4px;">[Gift]</span>
                        @endif

                    </td>
                    <td class="text-center">{{ $itemQty }}</td>
                    <td class="text-right">&euro;{{ number_format($unitPrice, 2) }}</td>
                    <td class="text-right">&euro;{{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    @php
        // Look up coupon type if coupon_code exists
        $couponInfo = null;
        if ($order->coupon_code) {
            $couponModel = \App\Models\Coupon::where('code', $order->coupon_code)->first();
            $couponInfo  = $couponModel;
        }
    @endphp

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">&euro;{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Shipping:</td>
            <td class="text-right">&euro;{{ number_format($order->shipping_fee, 2) }}</td>
        </tr>
        @if($order->fast_production_fee > 0)
        <tr>
            <td>Fast Production:</td>
            <td class="text-right">&euro;{{ number_format($order->fast_production_fee, 2) }}</td>
        </tr>
        @endif
        @if($order->discount > 0)
        <tr>
            <td>
                Discount
                @if($order->coupon_code)
                    <br>
                    <span class="section-label">Coupon: <strong>{{ $order->coupon_code }}</strong></span><br>
                    @if($couponInfo)
                        @php
                            $discountTypeLabel = match($couponInfo->type) {
                                'percent'       => 'Percentage (' . number_format($couponInfo->value, 0) . '%)',
                                'fixed'         => 'Fixed Amount',
                                'free_shipping' => 'Free Shipping',
                                default         => ucfirst($couponInfo->type),
                            };
                        @endphp
                        <span class="discount-badge">{{ $discountTypeLabel }}</span>
                    @endif
                @endif
            </td>
            <td class="text-right" style="color: #b91c1c;">-&euro;{{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td>Total:</td>
            <td class="text-right">&euro;{{ number_format($order->total, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for shopping with Lymetales!
    </div>
</body>
</html>
