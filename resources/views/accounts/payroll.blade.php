@extends('accounts.layout.app')


@section('title', 'Payroll Summary')

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Payroll Summary</h1>
        <p class="text-gray-500 text-sm">Read-only overview of employee payroll managed by HR</p>
    </div>
    <!-- Month Filter -->
    <form method="GET" action="{{ route('accounts.payroll') }}" class="mt-4 sm:mt-0 flex items-center gap-2">
        <input
            type="month"
            name="month"
            value="{{ $selectedMonth }}"
            class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
        />
        <button type="submit"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow transition">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-users text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Employees</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalEmployees }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-money-bill-wave text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Net Salary</p>
            <p class="text-xl font-bold text-green-600">₹{{ number_format($totalNetSalary, 2) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-minus text-red-500 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Deductions</p>
            <p class="text-xl font-bold text-red-500">₹{{ number_format($payrolls->sum('deductions'), 2) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-building text-purple-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Basic Salary</p>
            <p class="text-xl font-bold text-purple-600">₹{{ number_format($payrolls->sum('basic_salary'), 2) }}</p>
        </div>
    </div>
</div>

<!-- Payroll Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-700">
            Payroll for {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}
        </h2>
        <span class="text-sm text-gray-400">{{ $payrolls->count() }} records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Department</th>
                    <th class="px-6 py-4">Basic Salary</th>
                    <th class="px-6 py-4">Present Days</th>
                    <th class="px-6 py-4">Absent Days</th>
                    <th class="px-6 py-4">Leave Days</th>
                    <th class="px-6 py-4">Deductions</th>
                    <th class="px-6 py-4">Net Salary</th>
                    <th class="px-6 py-4">Salary Type</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($payrolls as $index => $payroll)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $payroll->first_name ?? '-' }} {{ $payroll->last_name ?? '' }}
                        <div class="text-xs text-gray-400">{{ $payroll->employee_id ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $payroll->department->name ?? '-' }}</td>
                    <td class="px-6 py-4">₹{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                            {{ $payroll->present_days }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-600">
                            {{ $payroll->absent_days }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-600">
                            {{ $payroll->leave_days }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-red-500">-₹{{ number_format($payroll->deductions, 2) }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">₹{{ number_format($payroll->net_salary, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full
                            {{ $payroll->payment_method === 'monthly' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ ucfirst($payroll->payment_method ?? '-') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-12 text-gray-500">
                        No payroll records found for {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($payrolls->count() > 0)
            <tfoot class="bg-gray-50 font-semibold text-sm">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-gray-700">Totals</td>
                    <td class="px-6 py-4">₹{{ number_format($payrolls->sum('basic_salary'), 2) }}</td>
                    <td class="px-6 py-4">{{ $payrolls->sum('present_days') }}</td>
                    <td class="px-6 py-4">{{ $payrolls->sum('absent_days') }}</td>
                    <td class="px-6 py-4">{{ $payrolls->sum('leave_days') }}</td>
                    <td class="px-6 py-4 text-red-500">-₹{{ number_format($payrolls->sum('deductions'), 2) }}</td>
                    <td class="px-6 py-4 text-gray-800">₹{{ number_format($payrolls->sum('net_salary'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection