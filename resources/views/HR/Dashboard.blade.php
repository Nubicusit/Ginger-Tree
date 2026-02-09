@extends('HR.layout.app')

@section('title', 'HR Dashboard')
@section('page-title', 'Welcome, HR Team')

@section('content')
<div class="bg-[#F3F4F6]">
<div class="max-w-full mx-auto">

    <!-- Top Cards Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">

        <!-- Employees Management -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#2196F3] font-bold text-base">Employees</h3>
                    <div class="bg-[#2196F3] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Total Employees :</span>
                        <span class="text-gray-800">245 Nos</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">New This Month :</span>
                        <span class="text-gray-800">12 Nos</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition">
                    Detail
                </button>
            </div>
        </div>

        <!-- Attendance -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#FF9800] font-bold text-base">Attendance</h3>
                    <div class="bg-[#FF9800] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Present Today :</span>
                        <span class="text-gray-800">{{ $todayPresent ?? 232 }} Nos</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Absent :</span>
                        <span class="text-gray-800">13 Nos</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition">
                    Detail
                </button>
            </div>
        </div>

        <!-- Leave Requests -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#4CAF50] font-bold text-base">Leave Requests</h3>
                    <div class="bg-[#4CAF50] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m-7 4h8a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Pending :</span>
                        <span class="text-gray-800">17 Nos</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Approved :</span>
                        <span class="text-gray-800">45 Nos</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition">
                    Detail
                </button>
            </div>
        </div>

        <!-- Payroll -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#9C27B0] font-bold text-base">Payroll</h3>
                    <div class="bg-[#9C27B0] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 1.343-3 3h6c0-1.657-1.343-3-3-3z"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">This Month :</span>
                        <span class="text-gray-800">₹4.25 Cr</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Pending :</span>
                        <span class="text-gray-800">3 Nos</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition">
                    Detail
                </button>
            </div>
        </div>

        <!-- Announcements -->
        <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-[#00BCD4] font-bold text-base">Announcements</h3>
                    <div class="bg-[#00BCD4] rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5h2m-1 0v14"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Active :</span>
                        <span class="text-gray-800">6 Nos</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-700">Draft :</span>
                        <span class="text-gray-800">2 Nos</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition">
                    Detail
                </button>
            </div>
        </div>

    </div>

</div>
</div>
@endsection
