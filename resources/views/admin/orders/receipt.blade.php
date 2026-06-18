<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { vertical-align: top; line-height: 1.5; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { border-bottom: 1px solid #eee; padding: 10px 5px; text-align: left; }
        .items-table th { background-color: #f9fafb; font-weight: bold; }
        .text-right { text-align: right; }
        .totals-table { width: 50%; float: right; border-collapse: collapse; }
        .totals-table td { padding: 8px 5px; }
        .totals-table .total-row { font-size: 18px; font-weight: bold; border-top: 2px solid #333; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 50px; clear: both; }
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
            <div class="logo" style="color: #697843;">Lymetales</div>
        @endif
        <div style="color: #666; margin-top: 5px;">Order Receipt</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Order Details:</strong><br>
                Order #: {{ $order->order_number }}<br>
                Date: {{ $order->created_at->format('M d, Y H:i') }}<br>
                Payment Method: {{ strtoupper($order->payment_method) }}<br>
                Payment Status: {{ ucfirst($order->payment_status) }}
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

    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item['name'] ?? 'Product' }}
                        @if(isset($item['personalisation']['child_name']))
                            <div style="font-size: 12px; color: #666;">
                                Personalised for: {{ $item['personalisation']['child_name'] }}
                            </div>
                        @endif
                    </td>
                    <td class="text-right">{{ $item['quantity'] ?? 1 }}</td>
                    <td class="text-right">&euro;{{ number_format(($item['price'] ?? 0), 2) }}</td>
                    <td class="text-right">&euro;{{ number_format((($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

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
            <td>Discount:</td>
            <td class="text-right">-&euro;{{ number_format($order->discount, 2) }}</td>
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
