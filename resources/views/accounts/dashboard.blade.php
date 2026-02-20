@extends('accounts.layout.app')
@section('title', 'Accounts Dashboard')

@section('page-title', 'Accounts Dashboard')

@section('content')
<div class="bg-[#F3F4F6]">
<div class="max-w-full mx-auto">

    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Income & Expenses -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#2196F3] font-bold text-base">Income & Expenses</h3>
                    <div class="bg-[#2196F3] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Total Income :</span>
                        <span class="text-gray-800">₹5,20,000</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Total Expenses :</span>
                        <span class="text-gray-800">₹2,80,000</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Net :</span>
                        <span class="text-green-600">₹2,40,000</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">Detail</button>
            </div>
        </div>

        <!-- Invoices -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#A855F7] font-bold text-base">Invoices</h3>
                    <div class="bg-[#A855F7] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Total :</span>
                        <span class="text-gray-800">48 Nos</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Paid :</span>
                        <span class="text-gray-800">35 Nos</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Pending :</span>
                        <span class="text-gray-800">10 Nos</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Overdue :</span>
                        <span class="text-red-600">03 Nos</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">Detail</button>
            </div>
        </div>

        <!-- Payroll Summary -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#4CAF50] font-bold text-base">Payroll Summary</h3>
                    <div class="bg-[#4CAF50] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">This Month :</span>
                        <span class="text-gray-800">₹2,40,000</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Paid :</span>
                        <span class="text-gray-800">₹2,10,000</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Pending :</span>
                        <span class="text-red-600">₹30,000</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">Detail</button>
            </div>
        </div>

        <!-- Profit & Loss -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#FF8A80] font-bold text-base">Profit & Loss</h3>
                    <div class="bg-[#FF8A80] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Gross Profit :</span>
                        <span class="text-green-600">₹3,50,000</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Net Profit :</span>
                        <span class="text-green-600">₹2,40,000</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Loss :</span>
                        <span class="text-red-600">₹10,000</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">Detail</button>
            </div>
        </div>
    </div>

    <!-- Chart + Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Chart -->
        <div class="lg:col-span-3 bg-white rounded-xl card-shadow p-6">
            <h3 class="text-gray-900 font-bold text-lg mb-6">Income vs Expenses</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="accountsChart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl card-shadow p-6">
            <h3 class="text-gray-800 font-semibold text-base mb-6">Recent Transactions</h3>
            <div class="space-y-5">
                <div class="flex items-start gap-3">
                    <div class="bg-green-100 rounded-full p-2 shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Invoice #1042 Paid</p>
                        <p class="text-xs text-green-600 font-semibold">+ ₹45,000</p>
                        <p class="text-xs text-gray-400">Today, 10:30 AM</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="bg-red-100 rounded-full p-2 shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Salary Disbursed</p>
                        <p class="text-xs text-red-600 font-semibold">- ₹2,10,000</p>
                        <p class="text-xs text-gray-400">Yesterday, 5:00 PM</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="bg-green-100 rounded-full p-2 shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Invoice #1041 Paid</p>
                        <p class="text-xs text-green-600 font-semibold">+ ₹28,000</p>
                        <p class="text-xs text-gray-400">2 days ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="bg-red-100 rounded-full p-2 shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Office Rent Paid</p>
                        <p class="text-xs text-red-600 font-semibold">- ₹35,000</p>
                        <p class="text-xs text-gray-400">3 days ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('accountsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Income',
                    data: [42000, 55000, 48000, 60000, 52000, 70000, 65000, 58000, 72000, 68000, 75000, 80000],
                    backgroundColor: '#4CAF50',
                    borderRadius: 6,
                },
                {
                    label: 'Expenses',
                    data: [30000, 38000, 35000, 42000, 38000, 45000, 40000, 42000, 48000, 44000, 50000, 52000],
                    backgroundColor: '#FF8A80',
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₹' + (value/1000) + 'k'
                    }
                }
            }
        }
    });
</script>
@endpush

@endsection