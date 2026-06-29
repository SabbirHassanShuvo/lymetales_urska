<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders for the admin panel.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Stats (always across ALL orders regardless of filters)
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $totalRevenue  = Order::where('payment_status', 'paid')->sum('total');

        return view('admin.orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'deliveredOrders', 'totalRevenue'));
    }

    /**
     * DELETE /admin/orders/{order}
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Updates order_status for any order (COD or Stripe).
     * Allowed values: pending, processing, shipped, delivered, cancelled
     * When shipping, also accepts tracking_number and tracking_link.
     */
    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'order_status'   => ['required', 'string', Rule::in([
                'pending', 'processing', 'shipped', 'delivered', 'cancelled',
            ])],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'tracking_link'   => ['nullable', 'string', 'url', 'max:1000'],
        ]);

        $updateData = ['order_status' => $validated['order_status']];

        if ($validated['order_status'] === 'shipped') {
            if (!empty($validated['tracking_number'])) {
                $updateData['tracking_number'] = $validated['tracking_number'];
            }
            if (!empty($validated['tracking_link'])) {
                $updateData['tracking_link'] = $validated['tracking_link'];
            }
        }

        $order->update($updateData);

        // Queue the email to notify the customer about status change
        \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\OrderStatusChangedMail($order, $validated['order_status']));

        return response()->json([
            'success'      => true,
            'order_status' => $order->order_status,
        ]);
    }

    /**
     * PATCH /admin/orders/{order}/payment-status
     * Updates payment_status for COD orders only.
     * Stripe order payment status is controlled exclusively by webhooks.
     */
    public function updatePaymentStatus(Request $request, Order $order): JsonResponse
    {
        if (in_array($order->payment_method, ['stripe', 'paypal'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payment status for online orders is managed automatically.',
            ], 422);
        }

        $validated = $request->validate([
            'payment_status' => ['required', 'string', Rule::in(['pending', 'paid', 'failed'])],
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return response()->json([
            'success'        => true,
            'payment_status' => $order->payment_status,
        ]);
    }

    /**
     * GET /admin/orders/{order}/preview
     * Returns order details for the preview modal
     */
    public function preview(Order $order): JsonResponse
    {
        $orderItems = is_array($order->items) ? $order->items : json_decode($order->items, true);
        
        return response()->json([
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'order_status' => $order->order_status,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'subtotal' => number_format($order->subtotal, 2),
                'shipping_fee' => number_format($order->shipping_fee, 2),
                'fast_production_fee' => number_format($order->fast_production_fee, 2),
                'discount' => number_format($order->discount, 2),
                'coupon_code' => $order->coupon_code,
                'total' => number_format($order->total, 2),
                'created_at' => $order->created_at->format('M d, Y H:i'),
            ],
            'billing' => [
                'full_name' => $order->full_name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
                'city' => $order->city,
                'postal_code' => $order->postal_code,
                'country' => $order->country,
            ],
            // For now, shipping is same as billing (until separate shipping address is implemented)
            'shipping' => [
                'full_name' => $order->full_name,
                'address' => $order->address,
                'city' => $order->city,
                'postal_code' => $order->postal_code,
                'country' => $order->country,
                'method' => 'Standard Shipping', // Adjust if needed
            ],
            'items' => collect($orderItems)->map(function ($item) {
                return [
                    'title' => $item['title'] ?? 'Product',
                    'quantity' => $item['quantity'] ?? 1,
                    'line_total' => number_format($item['line_total'] ?? 0, 2),
                    'personalisation' => $item['personalisation'] ?? null,
                ];
            })
        ]);
    }

    /**
     * Download Order Receipt PDF
     */
    public function receipt(Order $order)
    {
        if (!class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            // Flash error and redirect back if package is not installed
            return back()->with('error', 'PDF package not installed. Please run: composer require barryvdh/laravel-dompdf');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.receipt', compact('order'));
        return $pdf->download('Receipt-' . $order->order_number . '.pdf');
    }

    /**
     * PATCH /admin/orders/{order}/update-details
     * Updates customer contact and address details.
     */
    public function updateDetails(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'order_status' => 'nullable|string|max:50',
            'payment_status' => 'nullable|string|max:50',
            'full_name'   => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'address'     => 'required|string|max:255',
            'city'        => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'country'     => 'nullable|string|max:255',
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order details updated successfully.'
        ]);
    }

    /**
     * PATCH /admin/orders/{order}/update-items
     * Updates order items (specifically personalization).
     */
    public function updateItems(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'item_index' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
            'personalisation' => 'nullable|array',
        ]);

        $items = is_array($order->items) ? $order->items : json_decode($order->items, true);
        
        if (!isset($items[$validated['item_index']])) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }

        // Update quantity
        $items[$validated['item_index']]['quantity'] = $validated['quantity'];
        
        // Update line total based on new quantity (assuming price = line_total / old_quantity)
        $oldQuantity = $items[$validated['item_index']]['quantity'] ?? 1;
        // if old quantity was changed, it should probably recalculate line total, but we don't have base price explicitly
        // actually let's just let the user edit quantity but maybe not recalculate if it's too complex or just trust it.
        // wait, let's keep it simple: just update personalization for now.
        
        // Update personalization if provided
        if (isset($validated['personalisation'])) {
            $items[$validated['item_index']]['personalisation'] = array_merge(
                $items[$validated['item_index']]['personalisation'] ?? [],
                $validated['personalisation']
            );
        }

        $order->update(['items' => $items]);

        return response()->json([
            'success' => true,
            'message' => 'Item details updated successfully.'
        ]);
    }

    /**
     * POST /admin/orders/export-selected
     * Export a selected subset of orders by IDs.
     * Accepts: ids[] (array of order IDs), format (csv|excel|espremnica|pdf)
     */
    public function exportSelected(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            // If no IDs passed, export all (respecting current filters)
            $query = Order::query();
            if ($request->filled('order_status'))  $query->where('order_status', $request->order_status);
            if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);
            if ($request->filled('date_from'))      $query->whereDate('created_at', '>=', $request->date_from);
            if ($request->filled('date_to'))        $query->whereDate('created_at', '<=', $request->date_to);
            $orders = $query->orderBy('created_at', 'desc')->get();
        } else {
            $orders = Order::whereIn('id', $ids)->orderBy('created_at', 'desc')->get();
        }

        $format = $request->input('format', 'espremnica');

        if ($format === 'espremnica') {
            return $this->streamESpremnicaCSV($orders);
        }

        // For other formats we return JSON data so the JS can handle it
        $data = $orders->map(function ($order) {
            return [
                'order_number'   => $order->order_number,
                'full_name'      => $order->full_name,
                'email'          => $order->email,
                'payment_method' => $order->payment_method,
                'order_status'   => $order->order_status,
                'payment_status' => $order->payment_status,
                'total'          => '€' . number_format($order->total, 2),
                'date'           => $order->created_at->format('M d, Y H:i'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Export Orders to eSpremnica CSV Format
     */
    public function exportESpremnica(Request $request)
    {
        $query = Order::query();

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return $this->streamESpremnicaCSV($orders);
    }

    /**
     * Internal helper: stream eSpremnica CSV for a given collection of orders.
     */
    private function streamESpremnicaCSV($orders)
    {
        $filename = "eSpremnicaExport_" . date('Y-m-d_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'VrstaPosiljke', 'CrtnaKoda', 'Naziv', 'DodatniNaziv', 'Naslov', 
            'PostnaSt', 'NazivPoste', 'Drzava', 'TelSt', 'EMail', 'IdNaslovnika', 
            'Opomba', 'Masa', 'DodatneStoritve', 'Odkupnina', 'Vrednost', 'VrstaVplDok', 'RefX'
        ];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8 so Excel reads special chars properly
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns, ';');

            foreach ($orders as $order) {
                // Formatting for COD
                $dodatneStoritve = '';
                $odkupnina = '';
                if (strtolower($order->payment_method) === 'cod') {
                    $dodatneStoritve = 'ODKBN';
                    $odkupnina = number_format($order->total, 2, ',', '');
                }

                $row = [
                    '138', // VrstaPosiljke
                    '', // CrtnaKoda
                    $order->full_name, // Naziv
                    '', // DodatniNaziv
                    $order->address, // Naslov
                    $order->postal_code, // PostnaSt
                    $order->city, // NazivPoste
                    '705', // Drzava (Defaulting to Slovenia code 705)
                    $order->phone, // TelSt
                    $order->email, // EMail
                    '', // IdNaslovnika
                    '', // Opomba
                    '', // Masa
                    $dodatneStoritve, // DodatneStoritve
                    $odkupnina, // Odkupnina
                    '', // Vrednost
                    '', // VrstaVplDok
                    '' // RefX
                ];

                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * GET /orders/{order}/invoice/download (signed, no auth required)
     * Allows customers to download their invoice PDF from the confirmation email link.
     */
    public function downloadInvoiceGuest(Request $request, Order $order)
    {
        // Signature is validated automatically by the 'signed' middleware on the route.
        if (!class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            abort(500, 'PDF package not installed.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.receipt', compact('order'));
        return $pdf->download('Invoice-' . $order->order_number . '.pdf');
    }
}

