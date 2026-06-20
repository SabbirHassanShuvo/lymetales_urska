@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Revenue Reports</h2>
            <p class="text-sm text-gray-500 mt-1">Detailed breakdown of all revenue-generating orders.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
            Back to Dashboard
        </a>
    </div>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</p>
            <p class="text-3xl font-bold text-green-600 mt-2">&euro;{{ number_format($totalRevenue, 2) }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Average Order Value</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">&euro;{{ number_format($averageOrderValue, 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Stripe Revenue</p>
            <p class="text-3xl font-bold text-indigo-600 mt-2">&euro;{{ number_format($stripeRevenue, 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">COD Revenue</p>
            <p class="text-3xl font-bold text-amber-600 mt-2">&euro;{{ number_format($codRevenue, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 flex-wrap gap-4">
            <h3 class="text-lg font-bold text-gray-800">Paid Orders Detail</h3>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search orders..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <!-- Export Options -->
                <div class="relative animate-fade-in" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg z-50 overflow-hidden">
                        <button type="button" @click="exportRevenueToExcel(); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center font-semibold">
                            Excel Spreadsheet
                        </button>
                        <button type="button" @click="exportRevenueToPDF(); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center font-semibold">
                            PDF Document
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table id="revenueTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors" data-name="{{ strtolower($order->full_name) }}" data-email="{{ strtolower($order->email) }}" data-code="{{ strtolower($order->order_number) }}">
                        <td class="px-6 py-4 text-sm font-semibold text-indigo-600">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $order->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-800">{{ $order->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if(strtolower($order->payment_method) === 'stripe')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 text-xs font-bold uppercase">
                                    Stripe
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold uppercase">
                                    COD
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800 text-right">
                            &euro;{{ number_format($order->total, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No paid orders found yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="tablePagination" class="px-6 py-4 border-t border-gray-100"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#revenueTable', '#searchInput', '#tablePagination', 10);
    });

    // ── Table Data Exports ─────────────────────────────────────────────────
    function getRevenueData() {
        const table = document.getElementById('revenueTable');
        if (!table) return [];
        
        const headers = ["Order #", "Date", "Customer Name", "Customer Email", "Payment Method", "Revenue"];
        const rows = Array.from(table.querySelectorAll('tbody tr')).filter(row => !row.classList.contains('no-results-row'));
        const data = [headers];
        
        rows.forEach(row => {
            if (row.style.display === 'none') return;

            const cells = row.cells;
            if (cells.length < 5) return;

            const orderNumber = cells[0].innerText.trim();
            const date = cells[1].innerText.trim();
            const name = cells[2].querySelector('.text-sm, .font-medium')?.innerText.trim() || '';
            const email = cells[2].querySelector('.text-xs')?.innerText.trim() || '';
            const paymentMethod = cells[3].innerText.trim();
            const revenue = cells[4].innerText.trim();
            
            data.push([orderNumber, date, name, email, paymentMethod, revenue]);
        });
        return data;
    }

    function exportRevenueToExcel() {
        const data = getRevenueData();
        if (data.length <= 1) {
            Swal.fire('Info', 'No data to export.', 'info');
            return;
        }
        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Revenue");
        XLSX.writeFile(workbook, `revenue_report_${new Date().toISOString().slice(0,10)}.xlsx`);
    }

    function exportRevenueToPDF() {
        const data = getRevenueData();
        if (data.length <= 1) {
            Swal.fire('Info', 'No data to export.', 'info');
            return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');
        
        doc.setFontSize(16);
        doc.text("Revenue Report", 40, 40);
        doc.setFontSize(10);
        doc.text(`Generated on: ${new Date().toLocaleString()}`, 40, 55);
        
        doc.autoTable({
            head: [data[0]],
            body: data.slice(1),
            startY: 70,
            styles: { fontSize: 8 },
            headStyles: { fillColor: [79, 70, 229] }
        });
        
        doc.save(`revenue_report_${new Date().toISOString().slice(0,10)}.pdf`);
    }
</script>
@endpush
@endsection
