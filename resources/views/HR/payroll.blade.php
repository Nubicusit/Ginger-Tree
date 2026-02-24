@extends('HR.layout.app')

@section('title', 'Payroll')

@section('content')

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

        <!-- Filter Type -->
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Period</label>
            <select name="filter" id="filterType" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" onchange="toggleFilter()">
                <option value="monthly" {{ $filter === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="weekly"  {{ $filter === 'weekly'  ? 'selected' : '' }}>Weekly</option>
            </select>
        </div>

        <!-- Monthly Picker -->
        <div id="monthPicker" style="{{ $filter === 'weekly' ? 'display:none' : '' }}">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Month</label>
            <input type="month" name="month" value="{{ $month }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Weekly Picker -->
        <div id="weekPicker" style="{{ $filter === 'monthly' ? 'display:none' : '' }}">
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Week Starting</label>
            <input type="date" name="week_start" value="{{ $weekStart }}"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold transition">
            <i class="fa-solid fa-filter mr-1"></i> Apply
        </button>

        <a href="{{ route('hr.payroll') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">
            Reset
        </a>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total Employees</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ count($employees) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase font-semibold">Total Gross</p>
        <p class="text-2xl font-bold text-green-600 mt-1">₹{{ number_format($employees->sum('gross_salary'), 2) }}</p>
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
                    <th class="px-4 py-4 text-right">Deduction</th>
                    <th class="px-4 py-4 text-right">Net Payable</th>
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
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                {{ $emp['present_days'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 {{ $emp['absent_days'] > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-400' }} rounded-full text-xs font-semibold">
                                {{ $emp['absent_days'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 {{ $emp['leave_days'] > 0 ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-400' }} rounded-full text-xs font-semibold">
                                {{ $emp['leave_days'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-1 {{ $emp['late_days'] > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-400' }} rounded-full text-xs font-semibold">
                                {{ $emp['late_days'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center text-xs text-gray-500">
                            @if($emp['total_late_minutes'] > 0)
                                {{ floor($emp['total_late_minutes'] / 60) }}h {{ $emp['total_late_minutes'] % 60 }}m
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right font-medium text-gray-700">
                            ₹{{ number_format($emp['gross_salary'], 2) }}
                        </td>
                        <td class="px-4 py-4 text-right font-medium text-red-500">
                            {{ $emp['deductions'] > 0 ? '- ₹' . number_format($emp['deductions'], 2) : '—' }}
                        </td>
                        <td class="px-4 py-4 text-right font-bold text-blue-600">
                            ₹{{ number_format($emp['net_salary'], 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-12 text-gray-400">
                            <i class="fa-solid fa-file-invoice-dollar text-4xl mb-2 block"></i>
                            No payroll data found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleFilter() {
    const filter = document.getElementById('filterType').value;
    document.getElementById('monthPicker').style.display = filter === 'monthly' ? 'block' : 'none';
    document.getElementById('weekPicker').style.display  = filter === 'weekly'  ? 'block' : 'none';
}
</script>

@endsection