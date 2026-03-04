@extends('sales_executive.layout.app')

@section('title', 'Dashboard')

@section('page-title', 'Welcome, Sales')

@section('content')

<div class="bg-[#F3F4F6]">
  <!-- Dashboard -->
    <div class="max-w-full mx-auto">
        <!-- Top Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Customers Management -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#2196F3] font-bold text-base">Customers Management</h3>
                        <div class="bg-[#2196F3] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Total Customers :</span>
                            <span class="text-gray-800">102 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">New Customers :</span>
                            <span class="text-gray-800">02 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </button>
                </div>
            </div>

            <!-- Leads Management -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#A855F7] font-bold text-base">Leads Management</h3>
                        <div class="bg-[#A855F7] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Total Leads :</span>
                            <span class="text-gray-800">55 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">In Progress :</span>
                            <span class="text-gray-800">02 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Lost :</span>
                            <span class="text-gray-800">40 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">New :</span>
                            <span class="text-gray-800">03 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </button>
                </div>
            </div>

            <!-- Site Visits -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#FF8A80] font-bold text-base">Site Visits</h3>
                        <div class="bg-[#FF8A80] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Upcoming :</span>
                            <span class="text-gray-800">102 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Completed :</span>
                            <span class="text-gray-800">04 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </button>
                </div>
            </div>

            <!-- Design Stages -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#4CAF50] font-bold text-base">Design Stages</h3>
                        <div class="bg-[#4CAF50] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">In Progress :</span>
                            <span class="text-gray-800">05 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Finished :</span>
                            <span class="text-gray-800">02 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Approved :</span>
                            <span class="text-gray-800">07 Nos</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </button>
                </div>
            </div>

            <!-- Delivery & Production -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#00BCD4] font-bold text-base">Delivery & Production</h3>
                        <div class="bg-[#00BCD4] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Deliveries :</span>
                            <span class="text-gray-800">102 Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Installation :</span>
                            <span class="text-gray-800">25 Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </button>
                </div>
            </div>
        </div>

        <!-- Charts and Notifications Section -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Charts Section -->
            <!-- CHANGED HERE: Added md:grid-cols-2 so charts sit side-by-side on medium screens -->
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Total Lead Conversion Chart -->
                <div class="bg-white rounded-xl card-shadow p-4 sm:p-8">
                    <h3 class="text-gray-900 font-bold text-lg sm:text-xl mb-4 sm:mb-8">Total Lead Conversion Chart</h3>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-12">
                        <div class="w-48 h-48 sm:w-64 sm:h-64 md:w-72 md:h-72 relative flex-shrink-0">
                            <canvas id="leadChart"></canvas>
                        </div>
                        <!-- Vertical Separator -->
                        <div class="hidden sm:block w-px h-48 sm:h-56 bg-gray-300"></div>

                        <div class="space-y-3 sm:space-y-4 min-w-max sm:min-w-[160px] text-xs sm:text-sm">
                            <div class="flex items-center justify-end gap-3 mb-2 sm:mb-3">
                                <span class="text-xs sm:text-sm text-gray-500">Lead Source</span>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Cold Call</span>
                                <div class="w-4 h-4 rounded-full bg-[#FFA726]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Email</span>
                                <div class="w-4 h-4 rounded-full bg-[#FFB74D]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Email Campaign</span>
                                <div class="w-4 h-4 rounded-full bg-[#4DD0E1]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Web</span>
                                <div class="w-4 h-4 rounded-full bg-[#42A5F5]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Phone Inquiry</span>
                                <div class="w-4 h-4 rounded-full bg-[#5C6BC0]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Referral</span>
                                <div class="w-4 h-4 rounded-full bg-[#AB47BC]"></div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Other</span>
                                <div class="w-4 h-4 rounded-full bg-[#EF5350]"></div>
                            </div>
                             <div class="flex items-center justify-end gap-3">
                                <span class="text-xs sm:text-sm text-gray-600">Other</span>
                                <div class="w-4 h-4 rounded-full bg-[#EF5350]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Status Chart -->
                <div class="bg-white rounded-xl card-shadow p-4 sm:p-8">
                    <h3 class="text-gray-900 font-bold text-lg sm:text-xl mb-4 sm:mb-8 text-right">Project Status Chart</h3>
                    <div class="flex flex-col items-center justify-center h-full pb-6 sm:pb-8">
                        <div class="w-48 h-48 sm:w-64 sm:h-64 md:w-72 md:h-72 relative mb-4 sm:mb-8">
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

            <!-- Sidebar Section -->
            <div class="space-y-8">
                <!-- Notifications -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-gray-800 font-semibold text-base mb-6">Notifications</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-blue-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">New lead added</p>
                                <p class="text-xs text-gray-500">2 minutes ago</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="bg-green-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Lead converted</p>
                                <p class="text-xs text-gray-500">1 hour ago</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="bg-purple-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Follow-up scheduled</p>
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
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Call John Doe</p>
                                <p class="text-xs text-gray-500">Today, 2:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="bg-yellow-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Send proposal to Jane</p>
                                <p class="text-xs text-gray-500">Tomorrow, 10:00 AM</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="bg-blue-100 rounded-full p-3 shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Email follow-up</p>
                                <p class="text-xs text-gray-500">Friday, 11:00 AM</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- checkin section -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-gray-800 font-semibold text-base mb-6">Check In</h3>
                    <div class="space-y-6">
                        <button id="quickCheckinBtn" class="block w-full p-3 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition-colors text-left flex items-center">
                        <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ⏰ Self Check-in Now
                    </button>
                    <button id="openLeaveModal"
                        class="block w-full p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors text-left flex items-center mt-4">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
                        </svg>
                        📝 Apply Leave
                    </button>
                    {{-- Leave Status Section --}}
@if(isset($leaves) && count($leaves) > 0)
    <div class="mt-4 border-t pt-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-2">
            Your Leave Requests
        </h4>

        @foreach($leaves as $leave)
            <div class="flex justify-between items-center text-sm p-2 rounded-lg mb-2
                @if($leave->status == 'approved') bg-green-50
                @elseif($leave->status == 'rejected') bg-red-50
                @elseif($leave->status == 'hold') bg-yellow-50
                @else bg-blue-50
                @endif">

                <span>
                    {{ ucfirst($leave->leave_type) }}
                    ({{ $leave->from_date }} to {{ $leave->to_date }})
                </span>

                <span class="font-semibold capitalize
                    @if($leave->status == 'approved') text-green-600
                    @elseif($leave->status == 'rejected') text-red-600
                    @elseif($leave->status == 'hold') text-yellow-600
                    @else text-blue-600
                    @endif">
                    {{ $leave->status }}
                </span>
            </div>
        @endforeach
    </div>
@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="leaveModal" class="fixed inset-0 bg-black/30 backdrop-blur-md z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-6">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white rounded-t-3xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Apply Leave</h3>
                <button id="closeLeaveModal" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-xl">
                    ✕
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <form action="{{ route('sales.leave.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Leave Type</label>
                        <select name="leave_type" class="w-full border rounded p-2" required>
                            <option value="">Select Leave Type</option>
                            <option value="sick">Sick</option>
                            <option value="casual">Casual</option>
                            <option value="annual">Annual</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">From Date</label>
                        <input type="date" name="from_date" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">To Date</label>
                        <input type="date" name="to_date" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Reason</label>
                        <textarea name="reason" class="w-full border rounded p-2"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                        Submit Leave Request
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
        <div id="checkinModal" class="fixed inset-0 bg-black/30 backdrop-blur-md z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-6">
            <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">Self Check-in</h3>
                            <p class="text-orange-100 mt-1">{{ now()->format('M d, Y - h:i A') }}</p>
                        </div>
                        <button id="closeCheckinModal" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-8">
                    <!-- Status Check -->
                    <div id="statusCheck" class="text-center mb-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto mb-4"></div>
                        <p class="text-lg text-gray-600">Checking status...</p>
                    </div>

                    <!-- Check-in Button -->
                    <div id="checkinSection" class="hidden text-center">
                        <div class="mb-8">
                            <div class="w-20 h-20 bg-orange-100 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10h14M5 14h14"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Ready to Check-in</h3>
                            <p class="text-gray-600">Click below to record your attendance</p>
                        </div>
                        <button id="performCheckin" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 px-8 rounded-2xl text-lg font-bold hover:from-orange-600 hover:to-orange-700 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Check-in Now
                        </button>
                    </div>

                    <!-- Already Checked -->
                    <div id="alreadyCheckedSection" class="hidden text-center">
                        <div class="bg-green-50 rounded-2xl p-8 mb-6">
                            <div class="w-20 h-20 bg-green-100 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-green-800 mb-2">Already Checked-in!</h3>
                            <p id="checkinTimeDisplay" class="text-lg font-semibold text-green-700"></p>
                        </div>
                        <button id="performCheckout" class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white py-4 px-8 rounded-2xl text-lg font-bold hover:from-red-600 hover:to-red-700 transition-all shadow-xl">
                            Check-out Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    // LEAVE MODAL
const openLeaveModal = document.getElementById('openLeaveModal');
const leaveModal = document.getElementById('leaveModal');
const closeLeaveModal = document.getElementById('closeLeaveModal');

openLeaveModal.addEventListener('click', function () {
    leaveModal.classList.remove('hidden');
});

closeLeaveModal.addEventListener('click', function () {
    leaveModal.classList.add('hidden');
});

// Close when clicking outside
leaveModal.addEventListener('click', function (e) {
    if (e.target === leaveModal) {
        leaveModal.classList.add('hidden');
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const quickCheckinBtn = document.getElementById('quickCheckinBtn');
    const checkinModal = document.getElementById('checkinModal');
    const closeCheckinModal = document.getElementById('closeCheckinModal');
    const statusCheck = document.getElementById('statusCheck');
    const checkinSection = document.getElementById('checkinSection');
    const alreadyCheckedSection = document.getElementById('alreadyCheckedSection');
    const performCheckin = document.getElementById('performCheckin');
    const performCheckout = document.getElementById('performCheckout');
    const checkinTimeDisplay = document.getElementById('checkinTimeDisplay');

    // OPEN MODAL
    quickCheckinBtn.addEventListener('click', function() {
        checkinModal.classList.remove('hidden');
        loadCheckinStatus();
    });

    // CLOSE MODAL
    closeCheckinModal.addEventListener('click', closeModal);

    function closeModal() {
        checkinModal.classList.add('hidden');
        statusCheck.classList.remove('hidden');
        checkinSection.classList.add('hidden');
        alreadyCheckedSection.classList.add('hidden');
    }

    function loadCheckinStatus() {
        fetch('{{ route("sales.attendance.status") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            statusCheck.classList.add('hidden');
            if (data.checked_in_today) {
                checkinTimeDisplay.textContent = `Checked-in at ${data.checkin_time}`;
                alreadyCheckedSection.classList.remove('hidden');
            } else {
                checkinSection.classList.remove('hidden');
            }
        });
    }

    // ✅ CHECK-IN
    performCheckin.addEventListener('click', function () {
        fetch('{{ route("sales.attendance.self-check-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
    });

    // ✅ CHECK-OUT
    performCheckout.addEventListener('click', function () {
        fetch('{{ route("sales.attendance.self-check-out") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
    });
});
</script>
@endsection
