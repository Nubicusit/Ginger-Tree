@extends('HR.layout.app')

@section('title', 'Payroll')

@section('content')

<!-- Flash Messages -->
@if(session('success'))
    <div id="flashMsg" class="fixed top-5 right-5 z-50 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div id="flashMsg" class="fixed top-5 right-5 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-circle-xmark"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Payroll</h1>
        <p class="text-gray-500 text-sm">Salary summary based on attendance</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('hr.payroll') }}" class="flex flex-col md:flex-row gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Period</label>
            <select name="filter" id="filterType"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                onchange="toggleFilter()">
                <option value="monthly" {{ $filter === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="weekly"  {{ $filter === 'weekly'  ? 'selected' : '' }}>Weekly</option>
            </select>
        </div>
        <div id="monthPicker" style="{{ $filter === 'weekly' ? 'display:none' : '' }}">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Month</label>
            <input type="month" name="month" value="{{ $month }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>
        <div id="weekPicker" style="{{ $filter === 'monthly' ? 'display:none' : '' }}">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Week Starting</label>
            <input type="date" name="week_start" value="{{ $weekStart }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold transition">
            <i class="fa-solid fa-filter mr-1"></i> Apply
        </button>
        <a href="{{ route('hr.payroll') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">Reset</a>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total Employees</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ count($employees) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total Gross</p>
        <p class="text-2xl font-bold text-green-600 mt-1">₹{{ number_format($employees->sum('gross_salary'), 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total OT</p>
        <p class="text-2xl font-bold text-orange-500 mt-1">₹{{ number_format($employees->sum('ot_amount'), 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total Deductions</p>
        <p class="text-2xl font-bold text-red-500 mt-1">₹{{ number_format($employees->sum('deductions'), 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total Net Payable</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">₹{{ number_format($employees->sum('net_salary'), 2) }}</p>
    </div>
</div>

<!-- Payroll Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-4">Employee</th>
                    <th class="px-4 py-4">Department</th>
                    <th class="px-4 py-4 text-center">Present</th>
                    <th class="px-4 py-4 text-center">Absent</th>
                    <th class="px-4 py-4 text-center">Leave</th>
                    <th class="px-4 py-4 text-center">Late</th>
                    <th class="px-4 py-4 text-center">Late Mins</th>
                    <th class="px-4 py-4 text-right">Gross</th>
                    <th class="px-4 py-4 text-right text-orange-600">OT</th>
                    <th class="px-4 py-4 text-right text-purple-600">Advance</th>
                    <th class="px-4 py-4 text-right">Deduction</th>
                    <th class="px-4 py-4 text-right">Net Payable</th>
                    <th class="px-4 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($employees as $emp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div class="font-semibold text-gray-800">{{ $emp['name'] }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $emp['employee_id'] }}</div>
                        </td>
                        <td class="px-4 py-4 text-gray-600">{{ $emp['department'] }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">{{ $emp['present_days'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 {{ $emp['absent_days'] > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-400' }} rounded-full text-xs font-semibold">{{ $emp['absent_days'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 {{ $emp['leave_days'] > 0 ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-400' }} rounded-full text-xs font-semibold">{{ $emp['leave_days'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 {{ $emp['late_days'] > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-400' }} rounded-full text-xs font-semibold">{{ $emp['late_days'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-center text-xs text-gray-500">
                            @if($emp['total_late_minutes'] > 0)
                                {{ floor($emp['total_late_minutes'] / 60) }}h {{ $emp['total_late_minutes'] % 60 }}m
                            @else —
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right font-medium text-gray-700">
                            ₹{{ number_format($emp['gross_salary'], 2) }}
                        </td>
                        <td class="px-4 py-4 text-right">
                            @if($emp['ot_amount'] > 0)
                                <span class="font-medium text-orange-500">+ ₹{{ number_format($emp['ot_amount'], 2) }}</span>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $emp['ot_hours_total'] }}h OT</div>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right">
                            @if($emp['advance_deducted'] > 0)
                                <span class="font-medium text-purple-500">- ₹{{ number_format($emp['advance_deducted'], 2) }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right font-medium text-red-500">
                            {{ $emp['deductions'] > 0 ? '- ₹' . number_format($emp['deductions'], 2) : '—' }}
                        </td>
                        <td class="px-4 py-4 text-right font-bold text-blue-600">
                            ₹{{ number_format($emp['net_salary'], 2) }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- OT Button --}}
                                <button type="button"
                                    onclick="openOtModal({{ $emp['id'] }}, '{{ addslashes($emp['name']) }}', '{{ $month }}')"
                                    class="px-2 py-1 bg-orange-100 hover:bg-orange-200 text-orange-700 rounded-lg text-xs font-semibold transition"
                                    title="Update OT">
                                    <i class="fa-solid fa-clock mr-1"></i>OT
                                </button>
                                {{-- Advance Button --}}
                                <button type="button"
                                    onclick="openAdvanceModal({{ $emp['id'] }}, '{{ addslashes($emp['name']) }}', '{{ $month }}')"
                                    class="px-2 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg text-xs font-semibold transition"
                                    title="Add Advance">
                                    <i class="fa-solid fa-money-bill-transfer mr-1"></i>Advance
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center py-12 text-gray-400">
                            <i class="fa-solid fa-file-invoice-dollar text-4xl mb-2 block"></i>
                            No payroll data found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<!-- ===== OT MODAL ===== -->
<div id="otModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">
                <i class="fa-solid fa-clock text-orange-500 mr-2"></i>Update OT
            </h2>
            <button onclick="closeModal('otModal')" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <p class="text-sm text-gray-500 mb-4">Employee: <span id="otEmpName" class="font-semibold text-gray-700"></span></p>

        <form method="POST" action="{{ route('hr.payroll.ot.update') }}">
            @csrf
            <input type="hidden" name="employee_id" id="otEmpId">
            <input type="hidden" name="month" id="otMonth">

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date</label>
                <input type="date" name="date" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-400 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">OT Hours</label>
                <select name="ot_hours" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-400 focus:outline-none">
                    <option value="0">No OT</option>
                    <option value="2">2 Hours (1.25×)</option>
                    <option value="3">3 Hours (1.50×)</option>
                    <option value="4">4 Hours (2.00×)</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-check mr-1"></i> Save OT
                </button>
                <button type="button" onclick="closeModal('otModal')"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-semibold transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ===== ADVANCE MODAL ===== -->
<div id="advanceModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">
                <i class="fa-solid fa-money-bill-transfer text-purple-500 mr-2"></i>Add Advance
            </h2>
            <button onclick="closeModal('advanceModal')" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <p class="text-sm text-gray-500 mb-4">Employee: <span id="advEmpName" class="font-semibold text-gray-700"></span></p>

        <form method="POST" action="{{ route('hr.payroll.advance.store') }}">
            @csrf
            <input type="hidden" name="employee_id" id="advEmpId">
            <input type="hidden" name="deducted_month" id="advMonth">

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Amount (₹)</label>
                <input type="number" name="amount" min="1" step="0.01" required placeholder="e.g. 5000"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-400 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date Given</label>
                <input type="date" name="date" required value="{{ now()->format('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-400 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Reason (optional)</label>
                <input type="text" name="note" placeholder="e.g. Personal emergency"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-400 focus:outline-none">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-check mr-1"></i> Save Advance
                </button>
                <button type="button" onclick="closeModal('advanceModal')"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-semibold transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


<script>
// Auto hide flash message
const flash = document.getElementById('flashMsg');
if (flash) setTimeout(() => flash.style.display = 'none', 4000);

function toggleFilter() {
    const filter = document.getElementById('filterType').value;
    document.getElementById('monthPicker').style.display = filter === 'monthly' ? 'block' : 'none';
    document.getElementById('weekPicker').style.display  = filter === 'weekly'  ? 'block' : 'none';
}

function openOtModal(empId, empName, month) {
    document.getElementById('otEmpId').value   = empId;
    document.getElementById('otEmpName').textContent = empName;
    document.getElementById('otMonth').value   = month;
    document.getElementById('otModal').classList.remove('hidden');
}

function openAdvanceModal(empId, empName, month) {
    document.getElementById('advEmpId').value        = empId;
    document.getElementById('advEmpName').textContent = empName;
    document.getElementById('advMonth').value        = month;
    document.getElementById('advanceModal').classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// Close modal on backdrop click
['otModal', 'advanceModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModal(id);
    });[[]]
});
</script>

@endsection