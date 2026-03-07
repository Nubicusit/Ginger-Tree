@extends('Estimator.layout.app')

@section('title', 'Estimator Dashboard')

@section('page-title', 'Welcome, Estimator')

@section('content')
<div class="bg-[#F3F4F6]">
    <div class="max-w-full mx-auto">

        <!-- Top Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <!-- Assigned Projects -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#4CAF50] font-bold text-base">Assigned Projects</h3>
                        <div class="bg-[#4CAF50] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Total Assigned :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">In Progress :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Completed :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('design-status.index') }}"
                        class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#FF8A80] font-bold text-base">Pending Review</h3>
                        <div class="bg-[#FF8A80] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Awaiting Approval :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Revision Needed :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('design-status.index') }}"
                        class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Approved Designs -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#2196F3] font-bold text-base">Approved Designs</h3>
                        <div class="bg-[#2196F3] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">This Month :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Total :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('design-status.index') }}"
                        class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#A855F7] font-bold text-base">Upcoming Deadlines</h3>
                        <div class="bg-[#A855F7] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Due Today :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Due This Week :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Overdue :</span>
                            <span class="text-gray-800">0 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('design-status.index') }}"
                        class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

        </div>

        <!-- Charts and Notifications Section -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Charts -->
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Design Stage Chart -->
                <div class="bg-white rounded-xl card-shadow p-4 sm:p-8">
                    <h3 class="text-gray-900 font-bold text-lg sm:text-xl mb-4 sm:mb-8">Design Stage Overview</h3>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-12">
                        <div class="w-48 h-48 sm:w-64 sm:h-64 relative flex-shrink-0">
                            <canvas id="designStageChart"></canvas>
                        </div>
                        <div class="hidden sm:block w-px h-48 sm:h-56 bg-gray-300"></div>
                        <div class="space-y-3 sm:space-y-4 min-w-max sm:min-w-[160px] text-xs sm:text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-gray-600">In Progress</span>
                                <div class="w-4 h-4 rounded-full bg-[#42A5F5]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-gray-600">Pending Review</span>
                                <div class="w-4 h-4 rounded-full bg-[#FFA726]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-gray-600">Approved</span>
                                <div class="w-4 h-4 rounded-full bg-[#9CCC65]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-gray-600">Revision</span>
                                <div class="w-4 h-4 rounded-full bg-[#EF5350]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Status Chart -->
                <div class="bg-white rounded-xl card-shadow p-4 sm:p-8">
                    <h3 class="text-gray-900 font-bold text-lg sm:text-xl mb-4 sm:mb-8 text-right">Project Status Chart</h3>
                    <div class="flex flex-col items-center justify-center h-full pb-6 sm:pb-8">
                        <div class="w-48 h-48 sm:w-64 sm:h-64 relative mb-4 sm:mb-8">
                            <canvas id="projectChart"></canvas>
                        </div>
                        <div class="flex flex-wrap justify-center gap-4 sm:gap-8 w-full">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-[#9CCC65] rounded-full"></div>
                                <span class="text-xs sm:text-sm text-gray-600">Active</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-[#42A5F5] rounded-full"></div>
                                <span class="text-xs sm:text-sm text-gray-600">In Progress</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-[#FFA726] rounded-full"></div>
                                <span class="text-xs sm:text-sm text-gray-600">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Notifications + Tasks -->
            <div class="space-y-8">

                <!-- Notifications -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-gray-800 font-semibold text-base mb-6">Notifications</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-blue-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">New project assigned</p>
                                <p class="text-xs text-gray-500">2 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-green-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Design approved</p>
                                <p class="text-xs text-gray-500">1 hour ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-red-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Revision requested</p>
                                <p class="text-xs text-gray-500">3 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Tasks -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-gray-800 font-semibold text-base mb-6">Upcoming Tasks</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-red-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Submit bedroom design</p>
                                <p class="text-xs text-gray-500">Today, 5:00 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-yellow-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Client review meeting</p>
                                <p class="text-xs text-gray-500">Tomorrow, 10:00 AM</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-blue-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Send revised mockups</p>
                                <p class="text-xs text-gray-500">Friday, 11:00 AM</p>
                            </div>
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
    new Chart(document.getElementById('designStageChart'), {
        type: 'doughnut',
        data: {
            labels: ['In Progress', 'Pending Review', 'Approved', 'Revision'],
            datasets: [{
                data: [5, 3, 7, 2],
                backgroundColor: ['#42A5F5', '#FFA726', '#9CCC65', '#EF5350'],
                borderWidth: 0
            }]
        },
        options: { cutout: '70%', plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('projectChart'), {
        type: 'doughnut',
        data: {
            labels: ['Active', 'In Progress', 'Completed'],
            datasets: [{
                data: [4, 5, 7],
                backgroundColor: ['#9CCC65', '#42A5F5', '#FFA726'],
                borderWidth: 0
            }]
        },
        options: { cutout: '70%', plugins: { legend: { display: false } } }
    });
</script>
@endpush

@endsection
