@extends('HR.layout.app')

@section('title', 'HR Dashboard')
@section('page-title', 'Welcome, HR Team')

@section('content')
<div class="bg-[#F3F4F6]">
    <div class="max-w-full mx-auto p-6">
        <!-- Top Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
            
            <!-- Employees Management -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#2196F3] font-bold text-base">Employees</h3>
                        <div class="bg-[#2196F3] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Total Employees :</span>
                            <span class="text-gray-800">{{ $totalEmployees ?? 245 }} Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">New This Month :</span>
                            <span class="text-gray-800">{{ $newEmployees ?? 12 }} Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('hr.employees') }}" class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Self Check-in/Check-out Card -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#FF9800] font-bold text-base">Self Check-in/Out</h3>
                        <div class="bg-[#FF9800] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Today Check-ins :</span>
                            <span class="text-green-600 font-bold">{{ $todayCheckIns ?? 228 }} Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Late Check-ins :</span>
                            <span class="text-orange-600 font-bold">{{ $lateCheckIns ?? 8 }} Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Avg Check-in Time:</span>
                            <span class="text-blue-600 font-bold">{{ $avgCheckInTime ?? '9:15 AM' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('hr.self.checkin') }}" class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Attendance Today -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#10B981] font-bold text-base">Attendance Today</h3>
                        <div class="bg-[#10B981] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Present Today :</span>
                            <span class="text-green-600 font-bold">{{ $todayPresent ?? 232 }} Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Absent :</span>
                            <span class="text-red-600 font-bold">{{ $todayAbsent ?? 13 }} Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('hr.attendance') }}" class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Leave Requests -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#4CAF50] font-bold text-base">Leave Requests</h3>
                        <div class="bg-[#4CAF50] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-7 4h8a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Pending :</span>
                            <span class="text-orange-600 font-bold">{{ $pendingLeaves ?? 17 }} Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Approved :</span>
                            <span class="text-green-600 font-bold">{{ $approvedLeaves ?? 45 }} Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="#" class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Payroll -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#9C27B0] font-bold text-base">Payroll</h3>
                        <div class="bg-[#9C27B0] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3h6c0-1.657-1.343-3-3-3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">This Month :</span>
                            <span class="text-gray-800 font-bold">₹{{ number_format($monthlyPayroll ?? 42.5, 1) }} Cr</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Pending :</span>
                            <span class="text-orange-600 font-bold">{{ $pendingPayroll ?? 3 }} Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="#" class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Announcements -->
            <div class="bg-white rounded-xl card-shadow p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-[#00BCD4] font-bold text-base">Announcements</h3>
                        <div class="bg-[#00BCD4] rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Active :</span>
                            <span class="text-blue-600 font-bold">{{ $activeAnnouncements ?? 6 }} Nos</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold">
                            <span class="text-gray-700">Draft :</span>
                            <span class="text-gray-600 font-bold">{{ $draftAnnouncements ?? 2 }} Nos</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="#" class="bg-[#2962FF] text-white px-6 py-2 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors">
                        Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Section with MODAL Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-xl card-shadow p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Attendance Overview</h3>
                <p>Charts and graphs will go here...</p>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('hr.employees') }}" class="block p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                        👥 Manage Employees
                    </a>
                    <a href="{{ route('hr.attendance') }}" class="block p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                        📊 View Attendance
                    </a>
                    <!-- ✅ MODAL TRIGGER BUTTON -->
                    <button id="quickCheckinBtn" class="block w-full p-3 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition-colors text-left flex items-center">
                        <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ⏰ Self Check-in Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ SELF CHECK-IN MODAL -->
    <div id="checkinModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const quickCheckinBtn = document.getElementById('quickCheckinBtn');
    const checkinModal = document.getElementById('checkinModal');
    const closeCheckinModal = document.getElementById('closeCheckinModal');
    const statusCheck = document.getElementById('statusCheck');
    const checkinSection = document.getElementById('checkinSection');
    const alreadyCheckedSection = document.getElementById('alreadyCheckedSection');
    const performCheckin = document.getElementById('performCheckin');
    const performCheckout = document.getElementById('performCheckout');
    const checkinTimeDisplay = document.getElementById('checkinTimeDisplay');

    // Open Modal
    quickCheckinBtn.addEventListener('click', function() {
        checkinModal.classList.remove('hidden');
        loadCheckinStatus();
    });

    // Close Modal
    closeCheckinModal.addEventListener('click', closeModal);
    checkinModal.addEventListener('click', function(e) {
        if (e.target === checkinModal) closeModal();
    });

    function closeModal() {
        checkinModal.classList.add('hidden');
        statusCheck.classList.remove('hidden');
        checkinSection.classList.add('hidden');
        alreadyCheckedSection.classList.add('hidden');
    }

    function loadCheckinStatus() {
        statusCheck.classList.remove('hidden');
        checkinSection.classList.add('hidden');
        alreadyCheckedSection.classList.add('hidden');

        fetch('{{ route("hr.attendance.status") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            statusCheck.classList.add('hidden');
            if (data.checked_in_today) {
                checkinTimeDisplay.textContent = `Checked-in at ${data.checkin_time}`;
                alreadyCheckedSection.classList.remove('hidden');
            } else {
                checkinSection.classList.remove('hidden');
            }
        })
        .catch(error => {
            statusCheck.innerHTML = '<p class="text-red-600">Status check failed!</p>';
        });
    }

    // Perform Check-in
    performCheckin.addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin w-6 h-6 mr-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                         Checking in...`;

        fetch('{{ route("hr.self.checkin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                btn.innerHTML = `<svg class="w-6 h-6 mr-2 mx-auto text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                ${data.message}`;
                setTimeout(() => {
                    closeModal();
                    location.reload();
                }, 2000);
            } else {
                alert(data.message || 'Check-in failed!');
                btn.disabled = false;
                btn.innerHTML = 'Check-in Now';
            }
        })
        .catch(error => {
            alert('Check-in failed! Please try again.');
            btn.disabled = false;
            btn.innerHTML = 'Check-in Now';
        });
    });
});
</script>
@endpush
