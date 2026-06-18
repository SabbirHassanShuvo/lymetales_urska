@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Row 1: Users Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">{{ __('admin.total_users') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
            </div>
        </div>

        <!-- Approved Users -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">{{ __('admin.approved') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $approvedUsers }}</p>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">{{ __('admin.pending_requests') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pendingUsers }}</p>
            </div>
        </div>
    </div>

    <!-- Row 2: E-Commerce Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Main Categories -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">{{ __('admin.parent_categories') ?? 'Parent Categories' }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $parentCategories }}</p>
            </div>
        </div>

        <!-- Subcategories -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 bg-pink-50 text-pink-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">{{ __('admin.subcategories') ?? 'Subcategories' }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $subCategories }}</p>
            </div>
        </div>

        <!-- Active Coupons -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm-2 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">{{ __('admin.active_coupons') ?? 'Active Coupons' }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $activeCoupons }}</p>
            </div>
        </div>

        <!-- Books / Products -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500">Books / Products</p>
                <div class="flex items-baseline space-x-2">
                    <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</p>
                    <span class="text-xs text-green-600 font-bold">({{ $activeProducts }} Active)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Revenue Analytics Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Revenue Analytics</h3>
                <p class="text-gray-500 text-sm mt-1">Total Lifetime Revenue: <span class="font-bold text-green-600">&euro;{{ number_format($totalRevenue, 2) }}</span></p>
            </div>
            <div class="flex space-x-2">
                <button onclick="updateChart('weekly')" id="btn-weekly" class="px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-50 text-indigo-700 transition-colors">Weekly</button>
                <button onclick="updateChart('monthly')" id="btn-monthly" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">Monthly</button>
                <button onclick="updateChart('yearly')" id="btn-yearly" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">Yearly</button>
            </div>
        </div>
        
        <div class="relative h-[350px] w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const weeklyData = @json($weeklyRevenue);
    const monthlyData = @json($monthlyRevenue);
    const yearlyData = @json($yearlyRevenue);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Gradient for the line chart fill
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // indigo-600 low opacity
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    let revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: weeklyData.labels,
            datasets: [{
                label: 'Revenue (€)',
                data: weeklyData.data,
                borderColor: '#4f46e5', // indigo-600
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // smooth curves
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    callbacks: {
                        label: function(context) {
                            return '€' + parseFloat(context.parsed.y).toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f3f4f6',
                        drawBorder: false,
                    },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 12 },
                        color: '#6b7280',
                        callback: function(value) {
                            return '€' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 12 },
                        color: '#6b7280'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    function updateChart(period) {
        // Reset button styles
        ['weekly', 'monthly', 'yearly'].forEach(p => {
            const btn = document.getElementById('btn-' + p);
            btn.className = 'px-4 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:bg-gray-50 transition-colors';
        });

        // Set active button style
        const activeBtn = document.getElementById('btn-' + period);
        activeBtn.className = 'px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-50 text-indigo-700 transition-colors';

        // Update chart data
        let newData = {};
        if (period === 'weekly') newData = weeklyData;
        else if (period === 'monthly') newData = monthlyData;
        else if (period === 'yearly') newData = yearlyData;

        revenueChart.data.labels = newData.labels;
        revenueChart.data.datasets[0].data = newData.data;
        
        // Adjust points for monthly so it doesn't look too cluttered
        revenueChart.data.datasets[0].pointRadius = period === 'monthly' ? 2 : 4;
        
        revenueChart.update();
    }
</script>
@endpush
@endsection
